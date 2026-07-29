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
        if (!Schema::hasTable('employee_contract_logs')) {
            Schema::create('employee_contract_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id');
            $table->foreignId('employee_id');
            $table->foreignId('contract_type_id');
            $table->string('from_date');
            $table->foreignId('designation_id');
            $table->string('title');
            $table->string('to_date');
            $table->text('description');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('contract_type_id')->references('id')->on('contract_types')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contract_logs');
    }
};
