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
        if (!Schema::hasTable('xin_tickets_comments')) {
            Schema::create('xin_tickets_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->foreignId('ticket_id');
            $table->foreignId('user_id');
            $table->text('ticket_comments');
            $table->string('created_at');
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_tickets_comments');
    }
};
