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
        Schema::create('employee_bankaccount_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bankaccount_id');
            $table->foreignId('employee_id');
            $table->integer('is_primary');
            $table->string('account_title');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('bank_code');
            $table->text('bank_branch');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_bankaccount_logs');
    }
};
