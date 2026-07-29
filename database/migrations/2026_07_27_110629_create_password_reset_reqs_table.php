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
        if (!Schema::hasTable('password_reset_reqs')) {
            Schema::create('password_reset_reqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->string('last_pass');
            $table->string('req_date');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_reqs');
    }
};
