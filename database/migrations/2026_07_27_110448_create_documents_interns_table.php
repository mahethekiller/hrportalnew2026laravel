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
        if (!Schema::hasTable('documents_interns')) {
            Schema::create('documents_interns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_desc');
            $table->foreignId('user_id');
            $table->string('file_name');
            $table->integer('added_by');
            $table->integer('active')->default(1);
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_interns');
    }
};
