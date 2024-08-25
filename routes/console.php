<?php

use App\Models\DailyGlobalStats;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;


Artisan::command('save-daily-global-stats', function () {

    $totalTransactionsCreated = Transaction::whereDate('created_at', Carbon::today())->count();
    $totalTransactionsClaimed = Transaction::whereDate('claimed_at', Carbon::today())->count();
    $totalAmount = Transaction::whereDate('claimed_at', Carbon::today())->sum('points') / 100;

    $stats = new DailyGlobalStats();
    $stats->date = now();
    $stats->total_transactions_created = $totalTransactionsCreated;
    $stats->total_transactions_claimed = $totalTransactionsClaimed;
    $stats->total_amount = $totalAmount;
    $stats->save();

})->dailyAt('23:00');

if (config('app.env') === 'production') {
    Artisan::command('test', function () {
        throw new Exception("Can't test in production env.");
    })->purpose("Should not run test in production environment, as it may clear the database.");
}
