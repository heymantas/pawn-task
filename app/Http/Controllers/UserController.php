<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\QuestionAnswer;
use App\Models\Transaction;
use App\Services\TransactionService;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getUserWallet()
    {
        $user = auth('sanctum')->user();
        $balance = $user->balance;

        $unclaimedTransactions = Transaction::where('user_id', $user->id)
            ->where('is_claimed', false)
            ->selectRaw('COUNT(*) as count, SUM(points) as points')
            ->first();

        $unclaimedTransactionsCount = $unclaimedTransactions->count ?? 0;
        $unclaimedPoints = $unclaimedTransactions->points ?? 0;

        $pendingBalance = $unclaimedPoints * 0.01; // 1 point = 0.01 USD

        return response()->json([
            'status' => 'success',
            'balance' => $balance,
            'unclaimed_transactions_count' => $unclaimedTransactionsCount,
            'pending_balance' => $pendingBalance,
        ]);
    }

    public function updateUserProfile(UpdateUserProfileRequest $request)
    {
        $user = auth('sanctum')->user();

        foreach ($request['answers'] as $answerData) {
            $questionAnswer = new QuestionAnswer();
            $questionAnswer->user_id = $user->id;
            $questionAnswer->question_id = $answerData['question_id'];
            $questionAnswer->answer_text = $answerData['answer_text'];
            $questionAnswer->save();
        }

        //Award 5 points after complete
        (new TransactionService())->createTransaction($user->id, 5);

        $user->last_profile_update = Carbon::now();
        $user->save();


        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.',
        ]);
    }
}
