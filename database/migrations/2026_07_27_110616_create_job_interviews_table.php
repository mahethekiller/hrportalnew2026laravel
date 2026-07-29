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
        Schema::create('job_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id');
            $table->foreignId('application_id');
            $table->string('interviewers_id');
            $table->string('interview_mode')->default('F2F');
            $table->string('interview_place');
            $table->string('interview_date');
            $table->string('interview_date2')->nullable();
            $table->string('new_date')->nullable();
            $table->string('next_round_date');
            $table->string('interview_time');
            $table->string('interviewees_id');
            $table->string('expected_doj');
            $table->string('offered_ctc');
            $table->text('description');
            $table->text('remarks');
            $table->string('status')->default('pending');
            $table->string('offer_status')->default('none');
            $table->foreignId('salary_template_id')->default(0);
            $table->integer('convert_to_employee')->default(0);
            $table->foreignId('employee_id')->nullable();
            $table->integer('added_by');
            $table->string('updated_date');
            $table->integer('updated_by');
            $table->integer('show_status')->default(1);
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('salary_template_id')->references('id')->on('salary_templates')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_interviews');
    }
};
