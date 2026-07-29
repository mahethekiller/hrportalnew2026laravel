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
        if (!Schema::hasTable('xin_office_location')) {
            Schema::create('xin_office_location', function (Blueprint $table) {
            $table->id('location_id');
            $table->foreignId('company_id');
            $table->integer('location_head');
            $table->integer('location_manager');
            $table->string('location_name');
            $table->string('email');
            $table->string('phone');
            $table->string('fax');
            $table->text('address_1');
            $table->text('address_2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->integer('country');
            $table->integer('added_by');
            $table->boolean('status')->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_locations');
    }
};
