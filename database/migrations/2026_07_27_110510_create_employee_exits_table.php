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
        if (!Schema::hasTable('employee_exits')) {
            Schema::create('employee_exits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('employee_id');
            $table->string('exit_date');
            $table->foreignId('exit_type_id');
            $table->integer('exit_interview');
            $table->integer('is_inactivate_account');
            $table->text('reason');
            $table->integer('added_by');
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
        Schema::dropIfExists('employee_exits');
    }
};
