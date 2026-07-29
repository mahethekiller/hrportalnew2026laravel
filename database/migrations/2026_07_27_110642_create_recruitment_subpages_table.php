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
        if (!Schema::hasTable('recruitment_subpages')) {
            Schema::create('recruitment_subpages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id');
            $table->string('sub_page_title');
            $table->text('sub_page_details');
            $table->integer('status');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitment_subpages');
    }
};
