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
        Schema::create('xin_employee_bankaccount', function (Blueprint $table) {
            $table->id('bankaccount_id');
            $table->foreignId('employee_id');
            $table->integer('is_primary');
            $table->string('account_title');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('bank_code');
            $table->text('bank_branch');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_bankaccounts');
    }
};
