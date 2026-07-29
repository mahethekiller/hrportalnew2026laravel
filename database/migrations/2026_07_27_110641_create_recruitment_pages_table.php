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
        if (!Schema::hasTable('recruitment_pages')) {
            Schema::create('recruitment_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->text('page_details');
            $table->integer('status');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitment_pages');
    }
};
