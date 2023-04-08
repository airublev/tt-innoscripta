<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use jcobhams\NewsApi\NewsApi;

class NewsServiceProvider extends ServiceProvider
{

    public function __construct()
    {
        $this->api = new NewsApi(env('NEWS_API_KEY'));
    }
    public function getArticles($country = 'us', $categories = null, $pageSize = 10, $page = 1, $fromDate = '2023-04-04')
    {
        $results = [];

        foreach ($categories as $category) {
            $response = $this->api->getSources($category, 'en', $country);
            $sources = json_decode(json_encode($response), true);

            $sources = isset($sources['sources']) ? $sources['sources'] : [];

            foreach ($sources as $source) {
                $articlesResponse = $this->api->getEverything(null, $source['id'], null, $fromDate);
                $articles = json_decode(json_encode($articlesResponse), true);
                $articles = isset($articles['articles']) ? $articles['articles'] : [];

                if (!empty($articles)) {
                    foreach ($articles as $article) {
                        $article['source'] = $source;
                        $results[] = $article;
                    }
                }
            }
        }

        return $results;
    }

}
