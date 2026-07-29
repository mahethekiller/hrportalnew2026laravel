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
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('announcement_type')->default('announcement');
            $table->text('acceptance_message');
            $table->string('start_date');
            $table->string('end_date');
            $table->foreignId('company_id');
            $table->foreignId('department_id');
            $table->integer('published_by');
            $table->text('summary');
            $table->text('description');
            $table->string('image');
            $table->boolean('is_active');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
