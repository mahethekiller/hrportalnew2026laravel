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
        Schema::create('hr_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code');
            $table->string('ticket_priority');
            $table->foreignId('company_id');
            $table->string('subject');
            $table->foreignId('employee_id');
            $table->text('description');
            $table->text('remarks');
            $table->integer('ticket_status')->default(1);
            $table->string('created_by');
            $table->string('updated_date');
            $table->integer('show_status');
            $table->integer('updated_by');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_tickets');
    }
};
