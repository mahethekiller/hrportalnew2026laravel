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
        if (!Schema::hasTable('make_payments')) {
            Schema::create('make_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->foreignId('department_id');
            $table->foreignId('company_id');
            $table->foreignId('location_id');
            $table->foreignId('designation_id');
            $table->string('payment_date');
            $table->string('basic_salary');
            $table->string('payment_amount');
            $table->string('gross_salary');
            $table->string('total_allowances');
            $table->string('total_deductions');
            $table->string('net_salary');
            $table->string('house_rent_allowance');
            $table->string('medical_allowance');
            $table->string('travelling_allowance');
            $table->string('dearness_allowance');
            $table->string('provident_fund');
            $table->string('tax_deduction');
            $table->string('security_deposit');
            $table->string('overtime_rate');
            $table->integer('is_advance_salary_deduct');
            $table->string('advance_salary_amount');
            $table->boolean('is_payment');
            $table->integer('payment_method');
            $table->string('hourly_rate');
            $table->string('total_hours_work');
            $table->text('comments');
            $table->boolean('status');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('make_payments');
    }
};
