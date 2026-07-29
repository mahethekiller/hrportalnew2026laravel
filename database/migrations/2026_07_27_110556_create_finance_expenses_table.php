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
        if (!Schema::hasTable('finance_expenses')) {
            Schema::create('finance_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id');
            $table->foreignId('account_type_id');
            $table->string('amount');
            $table->string('expense_date');
            $table->foreignId('category_id');
            $table->foreignId('payee_id');
            $table->integer('payment_method');
            $table->string('expense_reference');
            $table->string('expense_file');
            $table->text('description');
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_expenses');
    }
};
