<?php

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_paginated_list_of_articles()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $articles = Article::factory(10)->create();

        $response = $this->getJson(route('articles.index'));

        $response->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    [
                        'id',
                        'title',
                        'description',
                        'url',
                        'url_to_image',
                        'published_at',
                        'author',
                        'content',
                        'category_id',
                        'article_source_id',
                        'created_at',
                        'updated_at',
                        'article_source' => [
                            'id',
                            'identifier_source',
                            'name',
                            'description',
                            'url',
                            'language',
                            'country',
                            'created_at',
                            'updated_at',
                        ],
                        'category' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    [
                        'url',
                        'label',
                        'active'
                    ],
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }

    /** @test */
    public function it_returns_articles_without_filtering()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $articles = Article::factory()->count(5)->create();

        $response = $this->getJson(route('articles.index'));

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_returns_articles_filtered_by_search_query()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $articles = Article::factory()->count(5)->create();
        $matchingArticle = Article::factory()->create(['title' => 'matching']);

        $response = $this->getJson(route('articles.index', ['search' => 'matching']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'matching']);
    }

    /** @test */
    public function it_returns_articles_filtered_by_category()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $articles1 = Article::factory()->count(3)->create(['category_id' => $category1->id]);
        $articles2 = Article::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->getJson(route('articles.index', ['category' => $category1->id]));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['category_id' => $category1->id]);
    }

    /** @test */
    public function it_returns_articles_filtered_by_search_query_and_category()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $articles1 = Article::factory()->count(3)->create(['category_id' => $category1->id]);
        $articles2 = Article::factory()->count(2)->create(['category_id' => $category2->id]);

        $matchingArticle = Article::factory()->create([
            'title' => 'matching',
            'category_id' => $category1->id
        ]);

        $response = $this->getJson(route('articles.index', [
            'search' => 'matching',
            'category' => $category1->id
        ]));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'title' => 'matching',
                'category_id' => $category1->id
            ]);
    }

    /** @test */
    public function it_returns_a_single_article()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $article = Article::factory()->create();

        $response = $this->getJson(route('articles.show', $article->id));

        $response->assertOk()
            ->assertJsonFragment(['title' => $article->title]);
    }
}

