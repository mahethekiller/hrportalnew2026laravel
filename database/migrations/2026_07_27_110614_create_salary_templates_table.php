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
        Schema::create('salary_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('salary_grades');
            $table->string('basic_salary');
            $table->string('overtime_rate');
            $table->string('house_rent_allowance');
            $table->string('medical_allowance');
            $table->string('travelling_allowance');
            $table->string('dearness_allowance');
            $table->string('security_deposit');
            $table->string('provident_fund');
            $table->string('tax_deduction');
            $table->string('gross_salary');
            $table->string('total_allowance');
            $table->string('total_deduction');
            $table->string('net_salary');
            $table->integer('added_by');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_templates');
    }
};
