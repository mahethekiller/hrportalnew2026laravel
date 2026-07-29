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
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('employee_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('date_of_birth');
            $table->string('gender');
            $table->integer('e_status');
            $table->foreignId('department_id');
            $table->foreignId('company_id')->nullable();
            $table->string('date_of_joining');
            $table->string('date_of_leaving');
            $table->string('salary');
            $table->text('address');
            $table->string('contact_no');
            $table->string('employee_source')->default('Recruiter');
            $table->foreignId('ref_emp_id')->default(0);
            $table->integer('created_by');
            $table->string('category');
            $table->string('address_com');
            $table->string('city_temp');
            $table->string('city');
            $table->string('state_temp');
            $table->string('state');
            $table->string('pin_temp');
            $table->string('pincode');
            $table->string('nationality');
            $table->string('religion');
            $table->string('college');
            $table->string('project');
            $table->string('tpa');
            $table->string('em_name');
            $table->string('em_relation');
            $table->string('em_contact');
            $table->string('reporting_location');
            $table->integer('show_status')->default(1);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
