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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('from_id')->default('');
            $table->string('to_id')->default('');
            $table->string('message_frm');
            $table->integer('is_read')->default(0);
            $table->text('message_content');
            $table->string('message_date')->nullable();
            $table->boolean('recd')->default(0);
            $table->string('message_type')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
