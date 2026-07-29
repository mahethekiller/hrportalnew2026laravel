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
        Schema::create('tickets_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id');
            $table->integer('upload_by');
            $table->string('file_title');
            $table->text('file_description');
            $table->text('attachment_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_attachments');
    }
};
