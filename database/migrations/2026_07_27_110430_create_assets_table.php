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
        if (!Schema::hasTable('assets')) {
            Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assets_category_id');
            $table->foreignId('company_id');
            $table->foreignId('employee_id');
            $table->string('company_asset_code');
            $table->string('name');
            $table->string('purchase_date');
            $table->string('invoice_number');
            $table->string('manufacturer');
            $table->string('serial_number');
            $table->string('warranty_end_date');
            $table->text('asset_note');
            $table->string('asset_image');
            $table->integer('is_working');
            $table->foreign('assets_category_id')->references('id')->on('assets_categories')->onDelete('cascade');
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
        Schema::dropIfExists('assets');
    }
};
