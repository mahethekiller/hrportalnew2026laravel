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
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('client_id');
            $table->string('start_date');
            $table->string('end_date');
            $table->foreignId('company_id');
            $table->text('assigned_to');
            $table->string('priority');
            $table->text('summary');
            $table->text('description');
            $table->integer('added_by');
            $table->string('project_progress');
            $table->text('project_note');
            $table->integer('status');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
