<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPreference;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserPreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function get_user_preferences()
    {
        // Create a user
        $user = User::factory()->create();

        // Create user preferences for the user
        $userPreferences = UserPreference::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // Act as the created user
        Sanctum::actingAs($user);

        $response = $this->get(route('user-preferences.index'));

        // Assert the response status and JSON structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'user_id',
                    // Add other fields of the UserPreference model
                ],
            ]);

        // Optional: assert the response data matches the created user preferences
        $responseData = $response->json();
        for ($i = 0; $i < count($userPreferences); $i++) {
            $this->assertEquals($userPreferences[$i]->id, $responseData[$i]['id']);
            $this->assertEquals($userPreferences[$i]->user_id, $responseData[$i]['user_id']);
            // Add other assertions for other fields of the UserPreference model
        }
    }

    /**
     * @test
     */
    public function store_user_preference()
    {
        // Create a user and log them in
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a category
        $category = Category::factory()->create();

        $createdData = [
            'key' => 'category',
            'value' => (string) $category->id,
        ];

        // Send a request to store user preference
        $response = $this->postJson(route('user-preferences.store'), $createdData);

        // Assert the response status and message
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'User preference created successfully'
            ]);

        // Assert the user preference is stored in the database
        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'key' => $category->name,
            'value' => $category->id,
        ]);
    }

    /**
     * @test
     */
    public function store_user_preference_fails_with_invalid_key()
    {
        // Create a user and log them in
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Send a request with an invalid key
        $response = $this->postJson(route('user-preferences.store'), [
            'key' => 'invalid_key',
            'value' => 'invalid_value',
        ]);

        // Assert the response status and message
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'message' => 'User preference created not successfully',
            ]);
    }

    /**
     * @test
     */
    public function update_user_preference()
    {
        // Create user, category, and user preference
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $preference = UserPreference::factory()->create(['user_id' => $user->id]);

        // Login as the created user
        Sanctum::actingAs($user);

        // Prepare data to update
        $updatedData = [
            'key' => 'category',
            'value' => (string) $category->id,
        ];

        // Send a PUT request to update the user preference
        $response = $this->putJson(route('user.preferences.update', $preference->id), $updatedData);

        // Assert that the response is successful and contains the updated data
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User preference updated successfully'
            ]);

        // Assert that the preference was updated in the database
        $this->assertDatabaseHas('user_preferences', array_merge(['id' => $preference->id], $updatedData));
    }

    /**
     * @test
     */
    public function delete_user_preference()
    {
        // Create user and user preference
        $user = User::factory()->create();
        $preference = UserPreference::factory()->create(['user_id' => $user->id]);

        // Login as the created user
        Sanctum::actingAs($user);

        // Send a DELETE request to delete the user preference
        $response = $this->deleteJson(route('user.preferences.destroy', $preference->id));

        // Assert that the response is successful and contains the expected message
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User preference deleted successfully',
            ]);

        // Assert that the preference was deleted from the database
        $this->assertDatabaseMissing('user_preferences', ['id' => $preference->id]);
    }
}
