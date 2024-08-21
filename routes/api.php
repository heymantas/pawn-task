<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AlwaysAcceptJson;
use App\Http\Middleware\APIAuthentication;
use App\Http\Middleware\CheckIfVpn;
use App\Http\Middleware\UpdateUserProfileOnceAday;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => [AlwaysAcceptJson::class, CheckIfVpn::class]], function () {
    //Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    //Authenticated routes
    Route::group(['middleware' => APIAuthentication::class], function () {
        //Profiling questions
        Route::get('/get-questions', [QuestionController::class, 'getQuestions']);

        //Retrieve user pts transactions
        Route::get('/get-transactions', [TransactionController::class, 'getTransactions']);

        //Claim transaction
        Route::post('/claim-transaction', [TransactionController::class, 'claimTransactions']);

        //Get user wallet
        Route::get('/get-user-wallet', [UserController::class, 'getUserWallet']);

        Route::group(['middleware' => UpdateUserProfileOnceAday::class], function () {
            //Update user profile
            Route::post('/update-user-profile', [UserController::class, 'updateUserProfile']);
        });
    });
});
