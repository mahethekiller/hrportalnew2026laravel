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
        Schema::create('designation_change_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->string('old_designation');
            $table->string('new_designation');
            $table->string('update_date');
            $table->integer('added_by');
            $table->integer('show_status')->default(1);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designation_change_details');
    }
};
