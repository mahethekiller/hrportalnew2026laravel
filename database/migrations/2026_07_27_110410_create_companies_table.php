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
        Schema::create('xin_companies', function (Blueprint $table) {
            $table->id('company_id');
            $table->foreignId('type_id');
            $table->string('name');
            $table->string('trading_name');
            $table->string('username');
            $table->string('password');
            $table->string('registration_no');
            $table->string('government_tax');
            $table->string('email');
            $table->string('logo');
            $table->string('contact_number');
            $table->string('website_url');
            $table->text('address_1');
            $table->text('address_2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->integer('country');
            $table->integer('is_active');
            $table->integer('added_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
