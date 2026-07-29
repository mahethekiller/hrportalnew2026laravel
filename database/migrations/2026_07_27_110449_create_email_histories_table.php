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
        if (!Schema::hasTable('email_histories')) {
            Schema::create('email_histories', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('message');
            $table->string('from_email');
            $table->string('to_emails');
            $table->string('sent_date');
            $table->string('mail_type');
            $table->foreignId('mail_type_id');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('email_histories');
    }
};
