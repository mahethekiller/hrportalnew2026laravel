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
        Schema::create('xin_designations', function (Blueprint $table) {
            $table->id('designation_id');
            $table->foreignId('top_designation_id')->default(0);
            $table->foreignId('department_id');
            $table->foreignId('company_id');
            $table->string('designation_name');
            $table->integer('added_by');
            $table->boolean('status')->default(1);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};
