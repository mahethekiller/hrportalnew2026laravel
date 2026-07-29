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
        if (!Schema::hasTable('xin_departments')) {
            Schema::create('xin_departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->string('department_name');
            $table->foreignId('company_id');
            $table->foreignId('location_id');
            $table->foreignId('employee_id');
            $table->integer('added_by');
            $table->boolean('status')->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
