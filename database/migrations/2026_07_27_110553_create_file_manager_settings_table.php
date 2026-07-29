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
        if (!Schema::hasTable('file_manager_settings')) {
            Schema::create('file_manager_settings', function (Blueprint $table) {
            $table->id();
            $table->text('allowed_extensions');
            $table->string('maximum_file_size');
            $table->string('is_enable_all_files');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_manager_settings');
    }
};
