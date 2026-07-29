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
        Schema::create('interview_salary_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_template_id');
            $table->foreignId('company_id');
            $table->foreignId('job_interview_id');
            $table->string('salary_grades');
            $table->string('basic_salary');
            $table->string('overtime_rate');
            $table->string('house_rent_allowance');
            $table->string('meal_allowance');
            $table->string('car_allowance');
            $table->string('books_allowance');
            $table->string('uniform_allowance');
            $table->string('special_allowance');
            $table->string('security_deposit');
            $table->string('provident_fund');
            $table->string('tax_deduction');
            $table->string('gross_salary');
            $table->string('total_allowance');
            $table->string('total_deduction');
            $table->string('net_salary');
            $table->integer('added_by');
            $table->foreign('salary_template_id')->references('id')->on('salary_templates')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('job_interview_id')->references('id')->on('job_interviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_salary_templates');
    }
};
