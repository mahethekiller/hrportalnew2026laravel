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
        Schema::create('employee_qualification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qualification_id');
            $table->foreignId('employee_id');
            $table->string('name');
            $table->foreignId('education_level_id');
            $table->string('from_year');
            $table->foreignId('language_id');
            $table->string('to_year');
            $table->text('skill_id');
            $table->text('description');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_qualification_logs');
    }
};
