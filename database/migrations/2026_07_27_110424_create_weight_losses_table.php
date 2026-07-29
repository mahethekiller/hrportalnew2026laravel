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
        if (!Schema::hasTable('weight_losses')) {
            Schema::create('weight_losses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('filename');
            $table->foreignId('user_id');
            $table->string('weight');
            $table->integer('show_status')->default(1);
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_losses');
    }
};
