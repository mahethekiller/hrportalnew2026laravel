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
        Schema::create('emp_today_attendance_olds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('card_no');
            $table->date('punch_date');
            $table->dateTime('check_in_datetime');
            $table->dateTime('check_out_datetime');
            $table->bigInteger('badgenumber');
            $table->string('check_in_time');
            $table->string('check_out_time');
            $table->integer('show_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_today_attendance_olds');
    }
};
