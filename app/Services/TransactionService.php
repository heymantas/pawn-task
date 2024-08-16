<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function createTransaction($userId, $points)
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->points = $points;
        $transaction->save();
    }
}
