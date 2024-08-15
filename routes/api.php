<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\APIAuthentication;
use Illuminate\Support\Facades\Route;


//Authentication

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => APIAuthentication::class], function () {

    //Profiling questions
    Route::get('/get-questions', [QuestionController::class, 'getQuestions']);

    //Retrieve user pts transactions
    Route::get('/get-transactions', [TransactionController::class, 'getTransactions']);

    //Get user wallet

    Route::get('/get-user-wallet', [UserController::class, 'getUserWallet']);


});
