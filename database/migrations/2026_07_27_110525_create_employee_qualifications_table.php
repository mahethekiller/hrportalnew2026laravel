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
        Schema::create('xin_employee_qualification', function (Blueprint $table) {
            $table->id('qualification_id');
            $table->foreignId('employee_id')->nullable();
            $table->foreignId('interview_id')->nullable();
            $table->string('name');
            $table->foreignId('education_level_id');
            $table->string('from_year');
            $table->foreignId('language_id');
            $table->string('to_year');
            $table->text('skill_id');
            $table->string('specialization')->nullable();
            $table->text('description');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_qualifications');
    }
};
