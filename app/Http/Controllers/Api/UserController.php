<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserWalletResource;
use App\Models\UserWallet;
use App\Services\QuestionAnswerService;
use App\Services\TransactionService;
use App\Services\UserWalletService;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getUserWallet(): UserWalletResource
    {
        $user = auth('sanctum')->user();

        (new UserWalletService())->updateUserWallet($user->id);
        $userWallet = UserWallet::where('user_id', $user->id)->firstOrFail();

        return new UserWalletResource($userWallet);
    }

    public function updateUserProfile(UpdateUserProfileRequest $request): SuccessResource
    {
        $user = auth('sanctum')->user();

        (new QuestionAnswerService())->saveQuestionAnswers($request['answers'], $user);
        (new TransactionService())->createTransaction($user->id, 5); //reward 5pts for updating
        (new UserWalletService())->updateUserWallet($user->id);

        $user->last_profile_update = Carbon::now();
        $user->save();

        return new SuccessResource('Profile updated successfully.');

    }
}
