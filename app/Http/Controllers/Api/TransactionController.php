<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimTransactionRequest;
use App\Models\Transaction;
use App\Services\UserWalletService;

class TransactionController extends Controller
{
    public function getTransactions()
    {
        $transactions = Transaction::where('user_id', auth('sanctum')->user())
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'transactions' => $transactions,
        ]);
    }

    public function claimTransactions(ClaimTransactionRequest $request)
    {
        $user = auth('sanctum')->user();

        $transactions = Transaction::whereIn('id', $request->transaction_ids)
            ->where('user_id', $user->id)
            ->where('is_claimed', false)
            ->get();

        $totalPoints = 0;

        foreach ($transactions as $transaction) {
            $totalPoints += $transaction->points;
            $transaction->is_claimed = true;
            $transaction->save();
        }

        (new UserWalletService())->updateUserWallet($user->id, $totalPoints);

        return response()->json([
            'status' => 'success',
            'message' => 'Transactions successfully claimed',
        ]);
    }
}
