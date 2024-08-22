<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimTransactionRequest;
use App\Http\Resources\Collections\TransactionCollection;
use App\Http\Resources\SuccessResource;
use App\Mail\SuccessClaimTransaction;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Services\UserWalletService;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    public function getTransactions(): TransactionCollection
    {
        $user = auth('sanctum')->user();
        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        return new TransactionCollection($transactions);
    }

    public function claimTransactions(ClaimTransactionRequest $request): SuccessResource
    {
        $user = auth('sanctum')->user();

        $transactions = Transaction::whereIn('id', $request->transaction_ids)
            ->where('user_id', $user->id)
            ->where('is_claimed', false)
            ->get();

        $totalPoints = ((new TransactionService()))->calculateAndSaveClaimedPoints($transactions);
        (new UserWalletService())->updateUserWallet($user->id, $totalPoints);

        Mail::to($user->email)->queue(new SuccessClaimTransaction($totalPoints));

        return new SuccessResource('Transactions successfully claimed');
    }
}
