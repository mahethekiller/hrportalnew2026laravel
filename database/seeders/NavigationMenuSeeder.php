<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menus
        NavigationMenu::truncate();

        // 0. Standalone Top-Level Items
        NavigationMenu::create([
            'title' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'route_name' => 'dashboard',
            'resource_key' => null,
            'sort_order' => 1,
        ]);

        // 1. Core HR Directories Category
        $directoryRoot = NavigationMenu::create([
            'title' => 'Core HR Directories',
            'icon' => 'fa-solid fa-users',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $directoryRoot->menu_id,
            'title' => 'Employees Directory',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'employees.index',
            'resource_key' => 'employees',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $directoryRoot->menu_id,
            'title' => 'Departments',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'departments.index',
            'resource_key' => 'organization',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $directoryRoot->menu_id,
            'title' => 'Designations',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'designations.index',
            'resource_key' => 'organization',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $directoryRoot->menu_id,
            'title' => 'Companies',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'companies.index',
            'resource_key' => 'organization',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $directoryRoot->menu_id,
            'title' => 'HR Tickets',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'hr-tickets.index',
            'resource_key' => 'hr_tickets',
            'sort_order' => 5,
        ]);

        NavigationMenu::create([
            'parent_id' => $directoryRoot->menu_id,
            'title' => 'Announcements',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'announcements.index',
            'resource_key' => 'announcements',
            'sort_order' => 6,
        ]);

        // 2. Operations & Finance Category
        $opsRoot = NavigationMenu::create([
            'title' => 'Operations & Finance',
            'icon' => 'fa-solid fa-briefcase',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $opsRoot->menu_id,
            'title' => 'Attendance & Timesheets',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'attendance.index',
            'resource_key' => 'attendance',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $opsRoot->menu_id,
            'title' => 'Leave Management',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'leaves.index',
            'resource_key' => 'leave',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $opsRoot->menu_id,
            'title' => 'Payroll Disbursements',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'payroll.index',
            'resource_key' => 'payroll',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $opsRoot->menu_id,
            'title' => 'Assets & Inventory',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'assets.index',
            'resource_key' => 'assets',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $opsRoot->menu_id,
            'title' => 'Support Tickets',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'support-tickets.index',
            'resource_key' => 'support_tickets',
            'sort_order' => 5,
        ]);

        // 3. Talent & Development Category
        $talentRoot = NavigationMenu::create([
            'title' => 'Talent & Development',
            'icon' => 'fa-solid fa-graduation-cap',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $talentRoot->menu_id,
            'title' => 'Job Openings',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'recruitment-job-posts.index',
            'resource_key' => 'recruitment',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $talentRoot->menu_id,
            'title' => 'Job Code Tags',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'recruitment-job-codes.index',
            'resource_key' => 'recruitment',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $talentRoot->menu_id,
            'title' => 'Recruitment Pipeline',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'recruitment-applications.index',
            'resource_key' => 'recruitment',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $talentRoot->menu_id,
            'title' => 'Performance Appraisals',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'performance-appraisals.index',
            'resource_key' => 'performance',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $talentRoot->menu_id,
            'title' => 'Training Sessions',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'training-sessions.index',
            'resource_key' => 'training',
            'sort_order' => 5,
        ]);

        NavigationMenu::create([
            'parent_id' => $talentRoot->menu_id,
            'title' => 'Instructors & Trainers',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'trainers.index',
            'resource_key' => 'training',
            'sort_order' => 6,
        ]);

        // 4. Administration & Analytics Category
        $adminRoot = NavigationMenu::create([
            'title' => 'Administration & Analytics',
            'icon' => 'fa-solid fa-sliders',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'Executive Reports Hub',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'reports.index',
            'resource_key' => 'reports',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'System Settings',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'system-settings.index',
            'resource_key' => 'settings',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'User Roles & Access',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'user-roles.index',
            'resource_key' => 'settings',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'Email Templates',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'email-templates.index',
            'resource_key' => 'settings',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'Sidebar Menu Manager',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'settings.navigation.index',
            'resource_key' => 'settings',
            'sort_order' => 5,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'API Access Keys',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'api-tokens.index',
            'resource_key' => 'api_control',
            'sort_order' => 6,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'Webhook Subscriptions',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'webhooks.index',
            'resource_key' => 'api_control',
            'sort_order' => 7,
        ]);

        NavigationMenu::create([
            'parent_id' => $adminRoot->menu_id,
            'title' => 'Admin Tickets',
            'icon' => 'fa-solid fa-circle bullet-dot',
            'route_name' => 'admin-tickets.index',
            'resource_key' => 'admin_tickets',
            'sort_order' => 8,
        ]);

        // 5. My Self-Service Hub Category
        $essRoot = NavigationMenu::create([
            'title' => 'My Self-Service Hub',
            'icon' => 'fa-solid fa-user-gear',
            'sort_order' => 5,
        ]);

        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'My ESS Dashboard', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.index', 'resource_key' => null, 'sort_order' => 1]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'My Leaves', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.leaves', 'resource_key' => null, 'sort_order' => 2]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'My Attendance', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.attendance', 'resource_key' => null, 'sort_order' => 3]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'My Payslips', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.payslips', 'resource_key' => null, 'sort_order' => 4]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'Performance Self-Rating', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.performance_feedback', 'resource_key' => null, 'sort_order' => 5]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'Corporate Benefits', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.benefits', 'resource_key' => null, 'sort_order' => 6]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'Refer a Candidate', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.referrals', 'resource_key' => null, 'sort_order' => 7]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'Book Conference Room', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.meetings', 'resource_key' => null, 'sort_order' => 8]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'Conveyance Claims', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.conveyance', 'resource_key' => null, 'sort_order' => 9]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'Tax Document Uploads', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.tax_documents', 'resource_key' => null, 'sort_order' => 10]);
        NavigationMenu::create(['parent_id' => $essRoot->menu_id, 'title' => 'Resignation Notice', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'my-portal.resignation', 'resource_key' => null, 'sort_order' => 11]);

        // 6. Manager Team Hub Category
        $managerRoot = NavigationMenu::create([
            'title' => 'Manager Team Hub',
            'icon' => 'fa-solid fa-users-gear',
            'sort_order' => 6,
        ]);

        NavigationMenu::create(['parent_id' => $managerRoot->menu_id, 'title' => 'Team Workstation', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'manager-portal.index', 'resource_key' => 'organization', 'sort_order' => 1]);
        NavigationMenu::create(['parent_id' => $managerRoot->menu_id, 'title' => 'Team Attendance', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'manager-portal.team_attendance', 'resource_key' => 'attendance', 'sort_order' => 2]);
        NavigationMenu::create(['parent_id' => $managerRoot->menu_id, 'title' => 'Team Leave Approvals', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'manager-portal.team_leaves', 'resource_key' => 'leave', 'sort_order' => 3]);
        NavigationMenu::create(['parent_id' => $managerRoot->menu_id, 'title' => 'Team Performance', 'icon' => 'fa-solid fa-circle bullet-dot', 'route_name' => 'manager-portal.team_performance', 'resource_key' => 'performance', 'sort_order' => 4]);

        // 5. Grant Role ID 1 (All Access)
        $adminRole = UserRole::find(1);
        if ($adminRole) {
            $adminRole->update([
                'role_access' => 'all',
                'role_resources' => implode(',', ['employees', 'organization', 'leave', 'attendance', 'payroll', 'performance', 'assets', 'recruitment', 'training', 'support_tickets', 'hr_tickets', 'admin_tickets', 'announcements', 'settings', 'api_control', 'reports'])
            ]);
        }
    }
}
