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
        if (!Schema::hasTable('projects_discussions')) {
            Schema::create('projects_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignId('user_id');
            $table->foreignId('client_id');
            $table->string('attachment_file');
            $table->text('message');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_discussions');
    }
};
