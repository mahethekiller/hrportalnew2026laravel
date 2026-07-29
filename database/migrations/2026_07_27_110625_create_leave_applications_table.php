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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('employee_id');
            $table->foreignId('manager_id')->nullable();
            $table->foreignId('leave_type_id');
            $table->string('start_duration')->default('Full');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('end_duration')->default('Full');
            $table->string('applied_on');
            $table->decimal('casual_deducted', 10, 2);
            $table->decimal('earned_deducted', 10, 2);
            $table->text('reason');
            $table->text('remarks');
            $table->boolean('status')->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
