<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\ArticleSource;
use App\Models\Category;
use App\Models\ScheduleLog;
use Illuminate\Console\Command;
use App\Providers\NewsServiceProvider;
use Carbon\Carbon;

class UpdateNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-news-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update news articles from various sources';

    public function __construct(NewsServiceProvider $newsApiService)
    {
        parent::__construct();
        $this->newsApiService = $newsApiService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // @todo need double check when schedule run from by last day:time schedule
        $dateLastSchedule = ScheduleLog::where('commandText', '=', 'app:update-news-articles')
            ->orderBy('created_at', 'desc')
            ->pluck('created_at')
            ->first();
        $dateLastSchedule = Carbon::parse($dateLastSchedule)->format('Y-m-d');

        $categories = Category::query()->pluck('name')->toArray();
        $articles = $this->newsApiService->getArticles('us', $categories, 100, 1, $dateLastSchedule);

        foreach ($articles as $articleData) {
            $category = Category::firstOrCreate(
                ['name' => $articleData['source']['category']]
            );

            $source = ArticleSource::firstOrCreate(
                ['identifier_source' => $articleData['source']['id']],
                [
                    'identifier_source' => $articleData['source']['id'],
                    'name' => $articleData['source']['name'],
                    'description' => $articleData['source']['description'],
                    'url' => $articleData['source']['url'],
                    'language' => $articleData['source']['language'],
                    'country' => $articleData['source']['country'],
                ]
            );

            $published_at = Carbon::parse($articleData['publishedAt'])->format('Y-m-d H:i:s');

            $article = Article::updateOrCreate(
                ['title' => $articleData['title'] ?? 'Default Title : '.$published_at],
                [
                    'description' => $articleData['description'],
                    'url' => $articleData['url'],
                    'url_to_image' => $articleData['urlToImage'],
                    'published_at' => $published_at,
                    'author' => $articleData['author'],
                    'content' => $articleData['content'],
                    'category_id' => $category->id,
                    'article_source_id' => $source->id,
                ]
            );
        }

        $this->info('News articles and categories updated successfully.');
    }
}
