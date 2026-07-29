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
        Schema::create('employee_exit_type_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exit_type_id');
            $table->foreignId('company_id');
            $table->string('type');
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
        Schema::dropIfExists('employee_exit_type_logs');
    }
};
