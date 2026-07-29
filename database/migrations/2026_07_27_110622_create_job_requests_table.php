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
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->string('post_name');
            $table->integer('vacancies');
            $table->foreignId('company_id');
            $table->foreignId('department_id');
            $table->string('team');
            $table->string('position_level');
            $table->string('min_experience');
            $table->string('max_experience');
            $table->string('job_role');
            $table->string('min_salary');
            $table->string('max_salary');
            $table->string('ctc_budget');
            $table->string('shift_timings');
            $table->string('timing_details');
            $table->string('work_days');
            $table->string('priority');
            $table->integer('interview_rounds');
            $table->string('interview_round_details');
            $table->string('questionare');
            $table->string('competitor');
            $table->text('profile_description');
            $table->text('project_description')->nullable();
            $table->string('certification');
            $table->string('education');
            $table->string('skills');
            $table->string('gender_preference');
            $table->text('description');
            $table->string('added_by');
            $table->string('updated_date')->nullable();
            $table->string('updated_by')->nullable();
            $table->integer('approve_status')->default(0);
            $table->string('status')->default('Pending');
            $table->integer('show_status')->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};
