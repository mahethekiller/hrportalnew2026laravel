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
        Schema::create('xin_documents', function (Blueprint $table) {
            $table->id('file_id');
            $table->foreignId('company_id');
            $table->string('file_type');
            $table->text('file_desc');
            $table->foreignId('user_id');
            $table->string('file_name');
            $table->string('file_extension');
            $table->string('file_size');
            $table->string('added_date');
            $table->foreignId('added_by');
            $table->integer('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_documents');
    }
};
