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
        if (!Schema::hasTable('holidays')) {
            Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('event_name');
            $table->text('description');
            $table->string('start_date');
            $table->string('end_date');
            $table->boolean('is_publish');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
