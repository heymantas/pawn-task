<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function createTransaction($userId, $points): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->points = $points;
        $transaction->save();
    }

    /**
     * @param $transactions
     * @return int
     */
    public function calculateAndSaveClaimedPoints($transactions): int
    {
        $totalPoints = 0;

        foreach ($transactions as $transaction) {
            $totalPoints += $transaction->points;
            $transaction->is_claimed = true;
            $transaction->claimed_at = now();
            $transaction->save();
        }
        return $totalPoints;
    }
}
