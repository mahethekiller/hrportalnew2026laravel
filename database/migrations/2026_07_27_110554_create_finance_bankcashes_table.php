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
        Schema::create('finance_bankcashes', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('account_balance');
            $table->string('account_number');
            $table->string('branch_code');
            $table->text('bank_branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_bankcashes');
    }
};
