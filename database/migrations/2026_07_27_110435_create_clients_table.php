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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('client_username');
            $table->string('client_password');
            $table->string('client_profile');
            $table->string('contact_number');
            $table->string('company_name');
            $table->string('gender');
            $table->string('website_url');
            $table->text('address_1');
            $table->text('address_2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->integer('country');
            $table->integer('is_active');
            $table->string('last_logout_date');
            $table->string('last_login_date');
            $table->string('last_login_ip');
            $table->integer('is_logged_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
