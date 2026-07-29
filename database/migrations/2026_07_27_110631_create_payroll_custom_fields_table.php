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
        Schema::create('payroll_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('allow_custom_1');
            $table->integer('is_active_allow_1');
            $table->string('allow_custom_2');
            $table->integer('is_active_allow_2');
            $table->string('allow_custom_3');
            $table->integer('is_active_allow_3');
            $table->string('allow_custom_4');
            $table->integer('is_active_allow_4');
            $table->string('allow_custom_5');
            $table->integer('is_active_allow_5');
            $table->string('deduct_custom_1');
            $table->integer('is_active_deduct_1');
            $table->string('deduct_custom_2');
            $table->integer('is_active_deduct_2');
            $table->string('deduct_custom_3');
            $table->integer('is_active_deduct_3');
            $table->string('deduct_custom_4');
            $table->integer('is_active_deduct_4');
            $table->string('deduct_custom_5');
            $table->integer('is_active_deduct_5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_custom_fields');
    }
};
