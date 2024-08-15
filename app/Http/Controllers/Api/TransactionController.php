<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function getTransactions()
    {
        $user = auth('sanctum')->user();
        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'status' => 'success',
            'transactions' => $transactions,
        ]);
    }
}
