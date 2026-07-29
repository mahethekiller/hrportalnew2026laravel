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
        if (!Schema::hasTable('office_shifts')) {
            Schema::create('office_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('shift_name');
            $table->integer('default_shift');
            $table->string('monday_in_time');
            $table->string('monday_out_time');
            $table->string('tuesday_in_time');
            $table->string('tuesday_out_time');
            $table->string('wednesday_in_time');
            $table->string('wednesday_out_time');
            $table->string('thursday_in_time');
            $table->string('thursday_out_time');
            $table->string('friday_in_time');
            $table->string('friday_out_time');
            $table->string('saturday_in_time');
            $table->string('saturday_out_time');
            $table->string('sunday_in_time');
            $table->string('sunday_out_time');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_shifts');
    }
};
