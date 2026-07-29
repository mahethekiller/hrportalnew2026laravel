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
        if (!Schema::hasTable('api_access_tokens')) {
            Schema::create('api_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('accessToken');
            $table->integer('status')->default(1);
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_access_tokens');
    }
};
