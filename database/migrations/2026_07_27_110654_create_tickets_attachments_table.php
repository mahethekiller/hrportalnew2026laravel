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
        Schema::create('xin_tickets_attachment', function (Blueprint $table) {
            $table->id('ticket_attachment_id');
            $table->foreignId('ticket_id');
            $table->integer('upload_by');
            $table->string('file_title');
            $table->text('file_description');
            $table->text('attachment_file');
            $table->string('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_tickets_attachment');
    }
};
