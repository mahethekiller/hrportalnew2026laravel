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
        // 1. Cache Tables
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // 2. Session & Queue Tables
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // 3. Spatie Permission & Role Tables
        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        if (!empty($tableNames)) {
            if (!Schema::hasTable($tableNames['permissions'])) {
                Schema::create($tableNames['permissions'], function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 125);
                    $table->string('guard_name', 125);
                    $table->timestamps();
                    $table->unique(['name', 'guard_name']);
                });
            }

            if (!Schema::hasTable($tableNames['roles'])) {
                Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
                    $table->bigIncrements('id');
                    if ($teams || config('permission.testing')) {
                        $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                        $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
                    }
                    $table->string('name', 125);
                    $table->string('guard_name', 125);
                    $table->timestamps();
                    if ($teams || config('permission.testing')) {
                        $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
                    } else {
                        $table->unique(['name', 'guard_name']);
                    }
                });
            }

            if (!Schema::hasTable($tableNames['model_has_permissions'])) {
                Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams) {
                    $table->unsignedBigInteger($pivotPermission);
                    $table->string('model_type', 125);
                    $table->unsignedBigInteger($columnNames['model_morph_key']);
                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

                    $table->foreign($pivotPermission)
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');

                    $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
                });
            }

            if (!Schema::hasTable($tableNames['model_has_roles'])) {
                Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams) {
                    $table->unsignedBigInteger($pivotRole);
                    $table->string('model_type', 125);
                    $table->unsignedBigInteger($columnNames['model_morph_key']);
                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

                    $table->foreign($pivotRole)
                        ->references('id')
                        ->on($tableNames['roles'])
                        ->onDelete('cascade');

                    $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_roles_role_model_type_primary');
                });
            }

            if (!Schema::hasTable($tableNames['role_has_permissions'])) {
                Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) {
                    $table->unsignedBigInteger($pivotPermission);
                    $table->unsignedBigInteger($pivotRole);

                    $table->foreign($pivotPermission)
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->onDelete('cascade');

                    $table->foreign($pivotRole)
                        ->references('id')
                        ->on($tableNames['roles'])
                        ->onDelete('cascade');

                    $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe down migration
    }
};
