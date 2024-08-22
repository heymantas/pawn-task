<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimTransactionRequest;
use App\Http\Resources\Collections\TransactionCollection;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Services\UserWalletService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function getTransactions(): TransactionCollection
    {
        $user = auth('sanctum')->user();
        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        return new TransactionCollection($transactions);
    }

    public function claimTransactions(ClaimTransactionRequest $request): JsonResponse
    {
        $user = auth('sanctum')->user();

        $transactions = Transaction::whereIn('id', $request->transaction_ids)
            ->where('user_id', $user->id)
            ->where('is_claimed', false)
            ->get();

        $totalPoints = ((new TransactionService()))->calculateAndSaveClaimedPoints($transactions);
        (new UserWalletService())->updateUserWallet($user->id, $totalPoints);

        return response()->json([
            'status' => 'success',
            'message' => 'Transactions successfully claimed',
        ]);
    }
}
