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
        if (!Schema::hasTable('hourly_templates')) {
            Schema::create('hourly_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('hourly_grade');
            $table->string('hourly_rate');
            $table->integer('added_by');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hourly_templates');
    }
};
