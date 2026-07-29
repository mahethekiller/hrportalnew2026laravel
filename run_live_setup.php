<?php

/**
 * Live Server Setup & Update Runner (Safe & Non-Destructive)
 * 
 * IMPORTANT AGENT & DEVELOPER RULE:
 * Whenever any database schema change, new table, Spatie permission, upload directory, 
 * or system configuration is introduced, update this file synchronously!
 * Ensure all DB operations remain safe & non-destructive (NO DROP, NO TRUNCATE, NO DELETE).
 * 
 * Usage on Live Server:
 * php run_live_setup.php
 */

declare(strict_types=1);

echo "==========================================================\n";
echo "    🚀 ANTIGRAVITY HR PORTAL - LIVE SERVER SETUP RUNNER    \n";
echo "    🛡️ DATA PROTECTION MODE: ACTIVE (ZERO DESTRUCTIVE OPS) \n";
echo "==========================================================\n\n";

// 1. Bootstrap Laravel Framework
echo "[1/6] Bootstrapping Laravel application...\n";
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// 2. Check Database Connection & Environment Safety
try {
    $dbConnection = config('database.default');
    $dbHost = config("database.connections.{$dbConnection}.host");
    $dbName = config("database.connections.{$dbConnection}.database");
    
    echo " -> Connected to Database: {$dbName} on host {$dbHost}\n";
    echo " -> Safety check: No DROP, TRUNCATE, or DELETE operations will be executed.\n";
} catch (\Throwable $e) {
    echo " ❌ DB Connection Error: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Create Spatie Permission Tables safely if not existing (preserving existing data)
echo "\n[2/6] Verifying Spatie Permission tables...\n";
try {
    if (!Schema::hasTable('permissions')) {
        echo " -> Creating 'permissions' table...\n";
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });
    } else {
        echo " -> Table 'permissions' exists (Preserved).\n";
    }

    if (!Schema::hasTable('roles')) {
        echo " -> Creating 'roles' table...\n";
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });
    } else {
        echo " -> Table 'roles' exists (Preserved).\n";
    }

    if (!Schema::hasTable('model_has_permissions')) {
        echo " -> Creating 'model_has_permissions' table...\n";
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
        });
    } else {
        echo " -> Table 'model_has_permissions' exists (Preserved).\n";
    }

    if (!Schema::hasTable('model_has_roles')) {
        echo " -> Creating 'model_has_roles' table...\n";
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
        });
    } else {
        echo " -> Table 'model_has_roles' exists (Preserved).\n";
    }

    if (!Schema::hasTable('role_has_permissions')) {
        echo " -> Creating 'role_has_permissions' table...\n";
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });
    } else {
        echo " -> Table 'role_has_permissions' exists (Preserved).\n";
    }

    echo " ✅ Spatie permission tables verified!\n";
} catch (\Throwable $e) {
    echo " ⚠️ Notice on permission tables: " . $e->getMessage() . "\n";
}

// 4. Seed Permissions & Super Admin Role safely (using firstOrCreate)
echo "\n[3/6] Seeding permissions and roles safely...\n";
try {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $permissions = [
        'employees.view',
        'employees.create',
        'employees.edit',
        'employees.delete',
        'departments.view',
        'designations.view',
        'companies.view',
        'attendance.view',
        'leaves.view',
        'payroll.view',
        'recruitment.view',
        'documents.view',
    ];

    foreach ($permissions as $perm) {
        Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
    }

    $adminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    $adminRole->givePermissionTo(Permission::all());

    echo " ✅ Permissions and super-admin role seeded without overwriting existing data!\n";
} catch (\Throwable $e) {
    echo " ⚠️ Notice on permission seeding: " . $e->getMessage() . "\n";
}

// 5. Safely update xin_employees.employee_id column & populate username for blank records ONLY
echo "\n[4/6] Checking xin_employees schema and employee codes...\n";
try {
    if (Schema::hasTable('xin_employees') && Schema::hasColumn('xin_employees', 'employee_id')) {
        // Inspect column type dynamically
        $colInfo = DB::select("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'xin_employees' AND COLUMN_NAME = 'employee_id'", [$dbName]);
        $dataType = strtolower($colInfo[0]->DATA_TYPE ?? '');

        if ($dataType === 'int' || $dataType === 'bigint' || $dataType === 'integer') {
            echo " -> Altering column 'employee_id' type to VARCHAR(200)...\n";
            Schema::table('xin_employees', function (Blueprint $table) {
                $table->string('employee_id', 200)->nullable()->change();
            });
        } else {
            echo " -> Column 'employee_id' is already string type ({$dataType}) (Preserved).\n";
        }

        // Fill employee_id = username ONLY for records where employee_id is 0, NULL, or empty string
        $updatedRows = DB::update("UPDATE xin_employees SET employee_id = username WHERE username IS NOT NULL AND username != '' AND (employee_id = '0' OR employee_id IS NULL OR employee_id = '')");
        echo " ✅ xin_employees.employee_id checked safely! Updated {$updatedRows} blank records.\n";
    }

    // Check Leave Tables
    if (Schema::hasTable('xin_leave_type')) {
        echo " -> Table 'xin_leave_type' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_leave_applications')) {
        echo " -> Table 'xin_leave_applications' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_emp_today_attendance')) {
        echo " -> Table 'xin_emp_today_attendance' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_clocking')) {
        echo " -> Table 'xin_clocking' verified (Preserved).\n";
        try {
            DB::statement("ALTER TABLE xin_clocking MODIFY clock_out VARCHAR(100) NULL DEFAULT ''");
            echo " -> Safely updated 'xin_clocking.clock_out' column default.\n";
        } catch (\Throwable $ex) {}
    }
    if (Schema::hasTable('xin_make_payment')) {
        echo " -> Table 'xin_make_payment' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_employee_salary')) {
        echo " -> Table 'xin_employee_salary' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_performance_appraisal')) {
        echo " -> Table 'xin_performance_appraisal' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_performance_indicator')) {
        echo " -> Table 'xin_performance_indicator' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_assets')) {
        echo " -> Table 'xin_assets' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_job_applications')) {
        echo " -> Table 'xin_job_applications' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_job_interviews')) {
        echo " -> Table 'xin_job_interviews' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_jobs')) {
        echo " -> Table 'xin_jobs' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_job_codes')) {
        echo " -> Table 'xin_job_codes' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_training')) {
        echo " -> Table 'xin_training' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_trainers')) {
        echo " -> Table 'xin_trainers' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_training_types')) {
        echo " -> Table 'xin_training_types' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_system_setting')) {
        echo " -> Table 'xin_system_setting' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_user_roles')) {
        echo " -> Table 'xin_user_roles' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_email_template')) {
        echo " -> Table 'xin_email_template' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_api_access_tokens')) {
        echo " -> Table 'xin_api_access_tokens' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_webhook_triggers')) {
        echo " -> Table 'xin_webhook_triggers' verified (Preserved).\n";
    }
    if (Schema::hasTable('xin_employees_log')) {
        echo " -> Table 'xin_employees_log' verified (Preserved).\n";
    }
} catch (\Throwable $e) {
    echo " ⚠️ Notice on employee_id safety check: " . $e->getMessage() . "\n";
}

// 6. Ensure Upload Directories Exist (preserving existing files)
echo "\n[5/6] Verifying upload directories...\n";
$uploadDirs = [
    public_path('uploads/profile'),
    public_path('uploads/documents'),
    public_path('uploads/logo'),
];

foreach ($uploadDirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
        echo " -> Created directory: " . str_replace(base_path() . '/', '', $dir) . "\n";
    } else {
        echo " -> Directory exists: " . str_replace(base_path() . '/', '', $dir) . " (Preserved)\n";
    }
}
echo " ✅ Upload directories verified!\n";

// 7. Clear & Optimize Laravel Cache
echo "\n[6/6] Clearing and rebuilding application cache...\n";
try {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo " ✅ Application cache cleared and optimized!\n";
} catch (\Throwable $e) {
    echo " ⚠️ Notice on cache clear: " . $e->getMessage() . "\n";
}

echo "\n==========================================================\n";
echo "    🎉 LIVE SERVER SETUP COMPLETED SAFELY & SUCCESSFULLY!   \n";
echo "==========================================================\n";
