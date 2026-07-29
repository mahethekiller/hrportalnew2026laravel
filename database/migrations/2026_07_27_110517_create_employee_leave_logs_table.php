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
        Schema::create('employee_leave_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_id');
            $table->foreignId('employee_id');
            $table->foreignId('contract_id');
            $table->string('casual_leave');
            $table->string('medical_leave');
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
        Schema::dropIfExists('employee_leave_logs');
    }
};
