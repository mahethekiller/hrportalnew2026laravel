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
        Schema::create('employee_work_experience_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_experience_id');
            $table->foreignId('employee_id');
            $table->string('company_name');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('post');
            $table->text('description');
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
        Schema::dropIfExists('employee_work_experience_logs');
    }
};
