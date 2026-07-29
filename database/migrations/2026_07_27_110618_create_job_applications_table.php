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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id');
            $table->string('candidate_name');
            $table->string('email');
            $table->string('gender');
            $table->string('experience');
            $table->foreignId('user_id');
            $table->text('message');
            $table->text('job_resume');
            $table->string('source')->nullable();
            $table->string('sub_source')->nullable();
            $table->string('referral_name');
            $table->string('date_cv_sourced')->nullable();
            $table->integer('company');
            $table->foreignId('department_id');
            $table->string('current_location')->nullable();
            $table->string('current_package')->nullable();
            $table->string('expected_package')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('change_reason')->nullable();
            $table->string('current_company')->nullable();
            $table->string('application_status');
            $table->text('application_remarks')->nullable();
            $table->text('hr_remarks');
            $table->string('covid_status')->default('No');
            $table->string('profile_picture')->nullable();
            $table->string('reason_to_leave')->nullable();
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->string('updated_date')->nullable();
            $table->integer('show_status')->default(1);
            $table->text('remarks');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
