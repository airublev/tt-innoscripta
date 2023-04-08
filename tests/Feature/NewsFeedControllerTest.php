<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NewsFeedControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function news_feed_index()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $articles = Article::factory(3)->create([
            'category_id' => $category->id,
        ]);
        $userPreference = UserPreference::factory()->create([
            'user_id' => $user->id,
            'key' => $category->name,
            'value' => $category->id,
        ]);

        Sanctum::actingAs($user);
        $response = $this->getJson(route('news-feed'));

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }
}
