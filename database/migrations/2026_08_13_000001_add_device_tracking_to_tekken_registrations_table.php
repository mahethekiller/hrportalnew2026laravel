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
        Schema::table('tekken_registrations', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('status');
            $table->string('mac_address', 50)->nullable()->after('ip_address');
            $table->string('device_name', 150)->nullable()->after('mac_address');
            $table->string('device_hash', 100)->nullable()->after('device_name');
            $table->text('user_agent')->nullable()->after('device_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tekken_registrations', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'mac_address', 'device_name', 'device_hash', 'user_agent']);
        });
    }
};
