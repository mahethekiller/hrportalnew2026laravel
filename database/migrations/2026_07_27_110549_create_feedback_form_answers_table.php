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
        if (!Schema::hasTable('feedback_form_answers')) {
            Schema::create('feedback_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('form_id');
            $table->foreignId('question_id');
            $table->string('answer');
            $table->text('feedback');
            $table->integer('rating');
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
        Schema::dropIfExists('feedback_form_answers');
    }
};
