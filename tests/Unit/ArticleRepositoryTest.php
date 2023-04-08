<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Category;
use App\Repositories\ArticleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_filtered_articles_by_search_query()
    {
        $repository = new ArticleRepository();
        $articles = Article::factory()->count(5)->create();
        $matchingArticle = Article::factory()->create(['title' => 'matching']);

        $request = new Request(['search' => 'matching']);
        $query = $repository->getFilteredArticles($request);

        $this->assertCount(1, $query->get());
        $this->assertEquals($matchingArticle->id, $query->first()->id);
    }

    public function test_it_returns_filtered_articles_by_category()
    {
        $repository = new ArticleRepository();

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $articles1 = Article::factory()->count(3)->create(['category_id' => $category1->id]);
        $articles2 = Article::factory()->count(2)->create(['category_id' => $category2->id]);

        $request = new Request(['category' => $category1->id]);
        $query = $repository->getFilteredArticles($request);

        $this->assertCount(3, $query->get());
        $this->assertEquals($category1->id, $query->first()->category_id);
    }
}
