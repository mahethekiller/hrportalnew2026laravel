<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('portal_roles')) {
            Schema::create('portal_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->default(1);
                $table->string('role_name');
                $table->string('role_access');
                $table->text('role_resources')->nullable();
                $table->timestamps();
            });
        }

        // Copy existing roles from xin_user_roles
        if (Schema::hasTable('xin_user_roles')) {
            $legacyRoles = DB::table('xin_user_roles')->get();
            foreach ($legacyRoles as $legacy) {
                // Check if ID already exists to prevent duplicate key issues
                if (!DB::table('portal_roles')->where('id', $legacy->role_id)->exists()) {
                    DB::table('portal_roles')->insert([
                        'id' => $legacy->role_id,
                        'role_name' => $legacy->role_name,
                        'role_access' => $legacy->role_access,
                        'role_resources' => $legacy->role_resources,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_roles');
    }
};
