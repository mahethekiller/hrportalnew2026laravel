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
        Schema::create('employee_contacts_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id');
            $table->foreignId('employee_id');
            $table->string('relation');
            $table->integer('is_primary');
            $table->integer('is_dependent');
            $table->string('contact_name');
            $table->string('work_phone');
            $table->string('work_phone_extension');
            $table->string('mobile_phone');
            $table->string('home_phone');
            $table->string('work_email');
            $table->string('personal_email');
            $table->text('address_1');
            $table->text('address_2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('country');
            $table->string('age')->nullable();
            $table->string('occupation')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contacts_logs');
    }
};
