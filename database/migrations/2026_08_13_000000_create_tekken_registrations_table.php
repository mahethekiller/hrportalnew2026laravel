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
        Schema::create('tekken_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('department');
            $table->boolean('festive_green')->default(false);
            $table->integer('matches')->default(1);
            $table->decimal('fee_paid', 8, 2);
            $table->string('utr_number');
            $table->enum('status', ['in_queue', 'playing', 'completed'])->default('in_queue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tekken_registrations');
    }
};
