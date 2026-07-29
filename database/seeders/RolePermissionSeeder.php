<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset Cached Roles and Permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define Business Resource Modules
        $modules = [
            'employees',        // Staff profiles and demographics
            'organization',     // Companies, departments, designations, offices
            'leave',            // Leave requests, balances, and types
            'attendance',       // Shift planners, punch logs, and WFH clocking
            'payroll',          // Monthly compensation, payslips, and histories
            'performance',      // Appraisal cards and KPIs indicators
            'assets',           // Hardware/license inventory allocations
            'recruitment',      // Job postings, codes, applications, and F2F interviews
            'training',         // Course catalogs, schedules, and instructor panels
            'settings',         // Global system parameters, mail configurations
            'api_control',      // API access tokens, webhooks subscriptions
            'reports'           // Custom audit trails and executive analytics dashboards
        ];

        // 3. Create Granular CRUD Permissions for Each Module
        foreach ($modules as $module) {
            Permission::findOrCreate("view.{$module}", 'web');
            Permission::findOrCreate("create.{$module}", 'web');
            Permission::findOrCreate("edit.{$module}", 'web');
            Permission::findOrCreate("delete.{$module}", 'web');
        }

        // 4. Seed Standard Roles and Assign Permissions
        
        // Super Admin (All Access)
        $superAdmin = Role::findOrCreate('Super Admin', 'web');
        $superAdmin->givePermissionTo(Permission::all());

        // HR Manager (Limited settings, full staff operations)
        $hrManager = Role::findOrCreate('HR Manager', 'web');
        $hrManager->givePermissionTo([
            'view.employees', 'create.employees', 'edit.employees',
            'view.leave', 'create.leave', 'edit.leave',
            'view.attendance', 'edit.attendance',
            'view.payroll', 'create.payroll',
            'view.recruitment', 'create.recruitment', 'edit.recruitment',
            'view.training', 'create.training'
        ]);

        // General Employee (View own logs/files, request leave)
        $employee = Role::findOrCreate('Employee', 'web');
        $employee->givePermissionTo([
            'view.employees',
            'view.leave', 'create.leave',
            'view.attendance',
            'view.payroll'
        ]);
    }
}
