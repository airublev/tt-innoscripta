<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_categories()
    {
        $user = User::factory()->create();
        $categories = Category::factory(5)->create();

        // Make sure the user is authenticated
        Sanctum::actingAs($user);

        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    /**
     * @test
     */
    public function get_available_categories()
    {
        $user = User::factory()->create();
        $categories = Category::factory(5)->create();

        // Set two categories as user preferences
        UserPreference::factory()->create([
            'user_id' => $user->id,
            'key' => $categories[0]->name,
            'value' => $categories[0]->id
        ]);

        UserPreference::factory()->create([
            'user_id' => $user->id,
            'key' => $categories[1]->name,
            'value' => $categories[1]->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(route('categories.available'));

        $response->assertStatus(200);
        // Expecting 3 categories as available (5 - 2 user preferences)
        $response->assertJsonCount(3);
    }
}
