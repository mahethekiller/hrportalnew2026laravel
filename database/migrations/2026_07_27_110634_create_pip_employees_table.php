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
        if (!Schema::hasTable('pip_employees')) {
            Schema::create('pip_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->integer('pip_status')->default(0);
            $table->integer('added_by');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pip_employees');
    }
};
