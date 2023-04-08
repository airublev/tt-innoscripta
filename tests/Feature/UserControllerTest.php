<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('user-details.index'));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * @test
     */
    public function update_updates_user_name()
    {
        $user = User::factory()->create();

        $updatedName = 'Updated Name';

        $response = $this->actingAs($user)->putJson(route('user-details.update'), [
            'name' => $updatedName,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => $updatedName,
        ]);
    }

    /**
     * @test
     */
    public function change_password_changes_user_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('current_password'),
        ]);

        $response = $this->actingAs($user)->putJson(route('user.change.password'), [
            'current_password' => 'current_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Password changed successfully']);

        $user->refresh();
        $this->assertTrue(Hash::check('new_password', $user->password));
    }
}
