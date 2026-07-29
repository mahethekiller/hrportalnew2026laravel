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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_type_id');
            $table->foreignId('deposit_id');
            $table->foreignId('expense_id');
            $table->foreignId('transfer_id');
            $table->string('transaction_type');
            $table->string('total_amount');
            $table->string('transaction_debit');
            $table->string('transaction_credit');
            $table->string('transaction_date');
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
