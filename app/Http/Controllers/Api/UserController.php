<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserWalletResource;
use App\Models\QuestionAnswer;
use App\Models\Transaction;
use App\Models\UserWallet;
use App\Services\TransactionService;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getUserWallet()
    {
        $userWallet = UserWallet::where('user_id', auth('sanctum')->id())->firstOrFail();
        return new UserWalletResource($userWallet);
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
