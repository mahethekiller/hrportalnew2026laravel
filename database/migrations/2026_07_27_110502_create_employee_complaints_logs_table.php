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
        Schema::create('employee_complaints_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id');
            $table->foreignId('company_id');
            $table->integer('complaint_from');
            $table->string('title');
            $table->string('complaint_date');
            $table->text('complaint_against');
            $table->text('description');
            $table->integer('status');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_complaints_logs');
    }
};
