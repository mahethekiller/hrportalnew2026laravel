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
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->foreignId('company_id');
            $table->string('expense_name');
            $table->text('billcopy_file');
            $table->string('outgoing_amount');
            $table->string('incoming_amount');
            $table->string('balance');
            $table->string('purchase_date');
            $table->text('remarks');
            $table->boolean('status')->default(0);
            $table->text('status_remarks');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
