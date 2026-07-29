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
        if (!Schema::hasTable('xin_employee_work_experience')) {
            Schema::create('xin_employee_work_experience', function (Blueprint $table) {
            $table->id('work_experience_id');
            $table->foreignId('employee_id');
            $table->foreignId('interview_id')->nullable();
            $table->string('company_name');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('post');
            $table->text('description');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_work_experiences');
    }
};
