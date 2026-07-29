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
        // 1. Change employee_id column type to string (VARCHAR 200) on employees table
        if (Schema::hasColumn('employees', 'employee_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('employee_id', 200)->nullable()->change();
            });

            // 2. Update employee_id with username value where username exists
            DB::statement("UPDATE employees SET employee_id = username WHERE username IS NOT NULL AND username != '' AND username != '0'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for down migration
    }
};
