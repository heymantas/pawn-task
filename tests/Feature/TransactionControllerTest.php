<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    public function test_get_transactions_with_authentication()
    {
        $user = User::factory()->create();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->points = 1;
        $transaction->save();

        Sanctum::actingAs($user);
        $response = $this->getJson('/api/get-transactions');

        $user->delete();

        $response->assertStatus(200);
    }

    public function test_user_can_claim_transactions_but_validation_fails()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/claim-transactions');

        $user->delete();

        $response->assertStatus(422);
    }

    public function test_user_can_claim_transactions()
    {
        $user = User::factory()->create();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->points = 1;
        $transaction->save();

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/claim-transactions', [
                'transaction_ids' => [$transaction->id]
            ]
        );

        $user->delete();

        $response->assertStatus(200);
    }

    public function test_user_cant_claim_same_transaction()
    {
        $user = User::factory()->create();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->points = 1;
        $transaction->is_claimed = true;
        $transaction->save();

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/claim-transactions', [
                'transaction_ids' => [$transaction->id]
            ]
        );

        $user->delete();

        $response->assertStatus(422);
    }

}
