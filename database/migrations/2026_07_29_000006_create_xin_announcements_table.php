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
        if (!Schema::hasTable('xin_announcements')) {
            Schema::create('xin_announcements', function (Blueprint $table) {
                $table->id('announcement_id');
                $table->string('title');
                $table->string('announcement_type');
                $table->text('acceptance_message')->nullable();
                $table->string('start_date');
                $table->string('end_date');
                $table->foreignId('company_id');
                $table->integer('department_id')->default(0);
                $table->string('published_by');
                $table->text('summary');
                $table->text('description');
                $table->string('image')->nullable();
                $table->integer('is_active')->default(1);
                $table->string('created_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_announcements');
    }
};
