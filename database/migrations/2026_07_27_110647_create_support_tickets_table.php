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
        Schema::create('xin_support_tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->foreignId('company_id');
            $table->string('ticket_code');
            $table->string('subject');
            $table->foreignId('employee_id');
            $table->string('ticket_priority');
            $table->foreignId('department_id');
            $table->text('assigned_to');
            $table->text('message');
            $table->text('description');
            $table->text('ticket_remarks');
            $table->string('ticket_status');
            $table->text('ticket_note');
            $table->string('created_at');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_support_tickets');
    }
};
