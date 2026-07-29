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
        if (!Schema::hasTable('attendance_times')) {
            Schema::create('attendance_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->string('attendance_date');
            $table->string('clock_in');
            $table->string('clock_in_ip_address');
            $table->string('clock_out');
            $table->string('clock_out_ip_address');
            $table->string('clock_in_out');
            $table->string('time_late');
            $table->string('early_leaving');
            $table->string('overtime');
            $table->string('total_work');
            $table->string('total_rest');
            $table->string('attendance_status');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_times');
    }
};
