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
        Schema::create('income_documents', function (Blueprint $table) {
            $table->id();
            $table->string('doc_type');
            $table->string('file');
            $table->string('financial_year');
            $table->integer('added_by');
            $table->integer('show_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_documents');
    }
};
