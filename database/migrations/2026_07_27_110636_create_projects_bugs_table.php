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
        if (!Schema::hasTable('projects_bugs')) {
            Schema::create('projects_bugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignId('user_id');
            $table->string('attachment_file');
            $table->string('title');
            $table->boolean('status');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_bugs');
    }
};
