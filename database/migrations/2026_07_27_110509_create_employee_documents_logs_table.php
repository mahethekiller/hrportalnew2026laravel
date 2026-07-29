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
        if (!Schema::hasTable('employee_documents_logs')) {
            Schema::create('employee_documents_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id');
            $table->foreignId('employee_id');
            $table->foreignId('document_type_id');
            $table->string('date_of_expiry');
            $table->string('title');
            $table->string('notification_email');
            $table->boolean('is_alert');
            $table->text('description');
            $table->string('document_file');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('document_types')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents_logs');
    }
};
