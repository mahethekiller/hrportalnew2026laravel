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
        Schema::create('employee_travels_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_id');
            $table->foreignId('company_id');
            $table->foreignId('employee_id');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('visit_purpose');
            $table->string('visit_place');
            $table->integer('travel_mode')->nullable();
            $table->integer('arrangement_type')->nullable();
            $table->string('expected_budget');
            $table->string('actual_budget');
            $table->text('description');
            $table->integer('status');
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_travels_logs');
    }
};
