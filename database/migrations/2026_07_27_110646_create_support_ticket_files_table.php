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
        Schema::create('xin_support_ticket_files', function (Blueprint $table) {
            $table->id('ticket_file_id');
            $table->foreignId('ticket_id');
            $table->foreignId('employee_id');
            $table->string('ticket_files');
            $table->string('file_size');
            $table->string('created_at');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_support_ticket_files');
    }
};
