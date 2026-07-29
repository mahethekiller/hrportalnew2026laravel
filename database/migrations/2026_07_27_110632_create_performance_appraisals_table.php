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
        if (!Schema::hasTable('performance_appraisals')) {
            Schema::create('performance_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('employee_id');
            $table->foreignId('manager_id')->nullable();
            $table->string('appraisal_year_month');
            $table->integer('customer_experience')->nullable();
            $table->integer('marketing')->nullable();
            $table->integer('management')->nullable();
            $table->integer('administration')->nullable();
            $table->integer('presentation_skill')->nullable();
            $table->integer('quality_of_work')->nullable();
            $table->integer('efficiency')->nullable();
            $table->integer('integrity')->nullable();
            $table->integer('professionalism')->nullable();
            $table->integer('team_work')->nullable();
            $table->integer('critical_thinking')->nullable();
            $table->integer('conflict_management')->nullable();
            $table->integer('attendance')->nullable();
            $table->integer('attendance_emp')->nullable();
            $table->integer('job_knowledge')->nullable();
            $table->integer('job_knowledge_emp')->nullable();
            $table->integer('quality_of_work_emp')->nullable();
            $table->integer('teamwork')->nullable();
            $table->integer('teamwork_emp')->nullable();
            $table->integer('communication')->nullable();
            $table->integer('communication_emp')->nullable();
            $table->integer('problem_solving')->nullable();
            $table->integer('problem_solving_emp')->nullable();
            $table->integer('ability_to_meet_deadline')->nullable();
            $table->text('remarks');
            $table->text('remarks_emp')->nullable();
            $table->text('area_strength')->nullable();
            $table->text('area_imp')->nullable();
            $table->text('future_goals')->nullable();
            $table->integer('added_by');
            $table->string('manager_update_date')->nullable();
            $table->string('emp_update_date')->nullable();
            $table->integer('show_status')->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_appraisals');
    }
};
