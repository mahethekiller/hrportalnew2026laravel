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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('project_id');
            $table->integer('created_by');
            $table->string('task_name');
            $table->string('assigned_to');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('task_hour');
            $table->string('task_progress');
            $table->text('description');
            $table->integer('task_status');
            $table->text('task_note');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
