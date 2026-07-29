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
        if (!Schema::hasTable('internal_job_posts')) {
            Schema::create('internal_job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_name');
            $table->integer('vacancies');
            $table->integer('company');
            $table->text('description');
            $table->integer('status')->default(1);
            $table->integer('show_status')->default(1);
            $table->integer('added_by');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_job_posts');
    }
};
