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
        if (!Schema::hasTable('employee_immigration_logs')) {
            Schema::create('employee_immigration_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immigration_id');
            $table->foreignId('employee_id');
            $table->foreignId('document_type_id');
            $table->string('document_number');
            $table->string('document_file');
            $table->string('issue_date');
            $table->string('expiry_date');
            $table->foreignId('country_id');
            $table->string('eligible_review_date');
            $table->text('comments');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('document_types')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_immigration_logs');
    }
};
