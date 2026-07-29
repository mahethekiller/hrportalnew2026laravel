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
        if (!Schema::hasTable('tasks_attachments')) {
            Schema::create('tasks_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id');
            $table->integer('upload_by');
            $table->string('file_title');
            $table->text('file_description');
            $table->text('attachment_file');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_attachments');
    }
};
