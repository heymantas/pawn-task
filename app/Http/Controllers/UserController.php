<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\TransactionController;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserWallet()
    {
        //current balance
        $user = auth('sanctum')->user();
        $balance = User::where('id', $user->id)->value('balance');

        //unclaimed points
        $unclaimedTransactionsCount = Transaction::where('user_id', $user->id)
            ->where('is_claimed', 0)
            ->count();

        $unclaimedPoints = Transaction::where('user_id', $user->id)
            ->where('is_claimed', 0)
            ->sum('points');

        //pending balance

        $pendingBalance =  $unclaimedPoints / 100; //1 point = 0.01 USD

        return response()->json([
           'status' => 'success',
           'balance' => $balance,
           'unclaimed_transactions_count' => $unclaimedTransactionsCount,
           'pending_balance' => $pendingBalance,
        ]);

    }
}
