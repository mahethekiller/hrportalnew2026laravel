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
        if (!Schema::hasTable('hrsale_invoices_items')) {
            Schema::create('hrsale_invoices_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id');
            $table->foreignId('project_id');
            $table->string('item_name');
            $table->string('item_tax_type');
            $table->string('item_tax_rate');
            $table->string('item_qty');
            $table->string('item_unit_price');
            $table->string('item_sub_total');
            $table->string('sub_total_amount');
            $table->string('total_tax');
            $table->integer('discount_type');
            $table->string('discount_figure');
            $table->string('total_discount');
            $table->string('grand_total');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrsale_invoices_items');
    }
};
