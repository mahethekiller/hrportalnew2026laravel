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
        if (!Schema::hasTable('conference_room_bookings')) {
            Schema::create('conference_room_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('room_name');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('booking_date');
            $table->integer('added_by');
            $table->text('purpose')->nullable();
            $table->integer('show_status')->default(1);
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_room_bookings');
    }
};
