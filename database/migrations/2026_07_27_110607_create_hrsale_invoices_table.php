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
        if (!Schema::hasTable('hrsale_invoices')) {
            Schema::create('hrsale_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->foreignId('project_id');
            $table->string('invoice_date');
            $table->string('invoice_due_date');
            $table->string('sub_total_amount');
            $table->string('discount_type');
            $table->string('discount_figure');
            $table->string('total_tax');
            $table->string('total_discount');
            $table->string('grand_total');
            $table->text('invoice_note');
            $table->boolean('status');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrsale_invoices');
    }
};
