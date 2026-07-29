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
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('logo_second');
            $table->string('sign_in_logo');
            $table->string('favicon');
            $table->text('website_url');
            $table->string('starting_year');
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_contact');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->text('address_1');
            $table->text('address_2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->integer('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_infos');
    }
};
