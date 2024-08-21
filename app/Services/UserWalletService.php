<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\UserWallet;

class UserWalletService
{

    public function createUserWallet($userId, $balance = 0): void
    {
        $userWallet = new UserWallet();
        $userWallet->user_id = $userId;
        $userWallet->balance = $balance;
        $userWallet->save();
    }

    public function updateUserWallet($userId, $claimedPoints = 0): void
    {
        $unclaimedTransactions = Transaction::where('user_id', $userId)
            ->where('is_claimed', false)
            ->selectRaw('COUNT(*) as count, SUM(points) as points')
            ->first();

        $unclaimedTransactionsCount = $unclaimedTransactions->count ?? 0;
        $unclaimedPoints = $unclaimedTransactions->points ?? 0;
        $pendingBalance = $unclaimedPoints * 0.01; // 1 point = 0.01 USD

        $currentBalance = UserWallet::where('user_id', $userId)->value('balance');
        $usdAmount = $claimedPoints * 0.01;

        UserWallet::where('user_id', $userId)->update([
            'balance' => $currentBalance + $usdAmount,
            'pending_balance' => $pendingBalance,
            'unclaimed_transactions_count' => $unclaimedTransactionsCount,
        ]);
    }
}
