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
        if (!Schema::hasTable('finance_transfers')) {
            Schema::create('finance_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_account_id');
            $table->foreignId('to_account_id');
            $table->string('transfer_date');
            $table->string('transfer_amount');
            $table->string('payment_method');
            $table->string('transfer_reference');
            $table->text('description');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transfers');
    }
};
