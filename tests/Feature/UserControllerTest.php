<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserWallet;
use App\Services\UserService;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    public function test_user_can_get_wallet(): void
    {
        $user = User::factory()->create();
        $wallet = new UserWallet();
        $wallet->user_id = $user->id;
        $wallet->save();

        Sanctum::actingAs($user);
        $response = $this->getJson('/api/get-user-wallet');

        $user->delete();
        $response->assertStatus(200);
    }

    public function test_user_can_update_profile_but_validation_fails(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/update-user-profile', []);

        $user->delete();

        $response->assertStatus(422);
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $wallet = new UserWallet();
        $wallet->user_id = $user->id;
        $wallet->save();

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/update-user-profile', [
            'answers' => [
                [
                    'question_id' => 1,
                    'answer_text' => 'Male',
                ]
            ]
        ]);


        $user->delete();

        $response->assertStatus(200);
    }

    public function test_user_can_update_profile_but_only_once_a_day_error(): void
    {
        $user = User::factory()->create();

        $wallet = new UserWallet();
        $wallet->user_id = $user->id;
        $wallet->save();

        $user->last_profile_update = now();
        $user->save();

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/update-user-profile', [
            'answers' => [
                [
                    'question_id' => 1,
                    'answer_text' => 'Male',
                ]
            ]
        ]);


        $user->delete();

        $response->assertStatus(403);
    }

    public function test_can_get_user_ip_address()
    {
        $userService = new UserService();
        $response = $userService->getUserIPAddress();
        $this->assertNotEmpty($response);
    }

    public function test_can_get_user_ip_from_server_variable()
    {
        // Test case where a valid IP is set in a $_SERVER variable
        $_SERVER['HTTP_CLIENT_IP'] = '8.8.8.8';
        $userService = new UserService();
        $response = $userService->getUserIPAddress();
        $this->assertEquals('8.8.8.8', $response);
    }

    public function test_a_wallet_belongs_to_user()
    {
        $user = User::factory()->create();
        $wallet = new UserWallet();
        $wallet->user_id = $user->id;
        $wallet->save();

        $this->assertInstanceOf(User::class, $wallet->user);

    }
}
