<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_can_register_but_validation_fails(): void
    {
        $response = $this->postJson('/api/register', []);
        $response->assertStatus(422);
    }

    public function test_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'device_name' => 'TestDevice123'
        ]);

        $response->assertStatus(200);
    }

    public function test_can_register_but_unexpected_error(): void
    {
        //mock error 500 for coverage :))
        $this->mock(UserService::class, function ($mock) {
            $mock->shouldReceive('createUser')
                ->once()
                ->andThrow(new \Exception('Unexpected error'));
        });

        $response = $this->postJson('/api/register', [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'device_name' => 'TestDevice123'
        ]);

        $response->assertStatus(500);
    }

    public function test_user_can_login(): void
    {
        //fake user
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'TestDevice123'
        ]);

        $user->delete();

        $response->assertStatus(200);
    }

    public function test_user_can_login_but_invalid_creds()
    {
        // Fake user creation
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'TestDevice123'
        ]);

        $user->delete();

        $response->assertStatus(401);
    }

    public function test_user_can_login_but_unexpected_error()
    {
        // Fake user creation
        $user = User::factory()->create();

        //mock error 500 for coverage :))
        $this->mock(UserService::class, function ($mock) {
            $mock->shouldReceive('attemptLogin')
                ->once()
                ->andThrow(new \Exception('Unexpected error'));
        });

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'TestDevice123'
        ]);

        $user->delete();

        $response->assertStatus(500);
    }

    public function test_can_logout_but_user_not_found()
    {
        $response = $this->postJson('/api/logout', []);
        $response->assertStatus(401);
    }

    public function test_user_can_logout ()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson('/api/logout');
        $user->delete();
        $response->assertStatus(200);
    }
}
