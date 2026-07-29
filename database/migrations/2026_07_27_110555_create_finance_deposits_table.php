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
        Schema::create('finance_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_type_id');
            $table->string('amount');
            $table->string('deposit_date');
            $table->foreignId('category_id');
            $table->foreignId('payer_id');
            $table->integer('payment_method');
            $table->string('deposit_reference');
            $table->string('deposit_file');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_deposits');
    }
};
