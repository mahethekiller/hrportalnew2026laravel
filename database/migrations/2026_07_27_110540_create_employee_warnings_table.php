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
        Schema::create('employee_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->integer('warning_to');
            $table->integer('warning_by');
            $table->string('warning_date');
            $table->foreignId('warning_type_id');
            $table->string('subject');
            $table->text('description');
            $table->integer('status');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('warning_type_id')->references('id')->on('warning_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_warnings');
    }
};
