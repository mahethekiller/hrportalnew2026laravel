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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_role')->default('administrator');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name');
            $table->string('company_logo');
            $table->integer('user_type');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('profile_photo');
            $table->string('profile_background');
            $table->string('contact_number');
            $table->string('gender');
            $table->text('address_1');
            $table->text('address_2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->integer('country');
            $table->string('last_login_date');
            $table->string('last_login_ip');
            $table->integer('is_logged_in');
            $table->integer('is_active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
