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
        if (!Schema::hasTable('emp_devices')) {
            Schema::create('emp_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('device');
            $table->text('service_tag');
            $table->integer('added_by');
            $table->integer('show_status')->default(1);
            $table->text('description');
            $table->string('phone_no')->nullable();
            $table->string('return_status')->default('Pending');
            $table->string('return_date');
            $table->integer('last_updated_by')->nullable();
            $table->string('last_updated_date')->nullable();
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_devices');
    }
};
