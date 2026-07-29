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
        Schema::create('job_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('job_code');
            $table->string('position');
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->string('status')->default('active');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_codes');
    }
};
