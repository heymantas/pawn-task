<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Support\Facades\Route;


//Authentication

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
