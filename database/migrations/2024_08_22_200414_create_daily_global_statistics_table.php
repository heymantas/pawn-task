<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_global_statistics', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->integer('total_transactions_created')->default(0);
            $table->integer('total_transactions_claimed')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_global_statistics');
    }
};
