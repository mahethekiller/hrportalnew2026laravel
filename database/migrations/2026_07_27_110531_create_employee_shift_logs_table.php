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
        if (!Schema::hasTable('employee_shift_logs')) {
            Schema::create('employee_shift_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_shift_id');
            $table->foreignId('employee_id');
            $table->foreignId('shift_id');
            $table->string('from_date');
            $table->string('to_date');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_shift_logs');
    }
};
