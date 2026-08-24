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

        // 1. My Day Category (Employee Base Layer)
        $myDayRoot = NavigationMenu::create([
            'title' => 'My Day',
            'icon' => 'fa-solid fa-sun',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Home',
            'icon' => 'fa-solid fa-house',
            'route_name' => 'dashboard',
            'resource_key' => null,
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Leave',
            'icon' => 'fa-solid fa-calendar-check',
            'route_name' => 'my-portal.leaves',
            'resource_key' => null,
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Attendance',
            'icon' => 'fa-solid fa-clock',
            'route_name' => 'my-portal.attendance',
            'resource_key' => null,
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Pay',
            'icon' => 'fa-solid fa-wallet',
            'route_name' => 'my-portal.payslips',
            'resource_key' => null,
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Performance',
            'icon' => 'fa-solid fa-chart-line',
            'route_name' => 'my-portal.performance_feedback',
            'resource_key' => null,
            'sort_order' => 5,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Profile',
            'icon' => 'fa-solid fa-user-gear',
            'route_name' => 'my-portal.profile-update',
            'resource_key' => null,
            'sort_order' => 6,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Requests & Claims',
            'icon' => 'fa-solid fa-paper-plane',
            'route_name' => 'my-portal.referrals',
            'resource_key' => null,
            'sort_order' => 7,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'Support Tickets',
            'icon' => 'fa-solid fa-headset',
            'route_name' => 'support-tickets.index',
            'resource_key' => 'support_tickets',
            'sort_order' => 8,
        ]);

        NavigationMenu::create([
            'parent_id' => $myDayRoot->menu_id,
            'title' => 'HR Tickets',
            'icon' => 'fa-solid fa-file-circle-question',
            'route_name' => 'hr-tickets.index',
            'resource_key' => 'hr_tickets',
            'sort_order' => 9,
        ]);

        // 2. My Team Category (Manager Persona)
        $myTeamRoot = NavigationMenu::create([
            'title' => 'My Team',
            'icon' => 'fa-solid fa-users-gear',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $myTeamRoot->menu_id,
            'title' => 'Team Roster',
            'icon' => 'fa-solid fa-user-group',
            'route_name' => 'manager-portal.index',
            'resource_key' => 'organization',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $myTeamRoot->menu_id,
            'title' => 'Leave Approvals',
            'icon' => 'fa-solid fa-clipboard-check',
            'route_name' => 'manager-portal.team_leaves',
            'resource_key' => 'leave',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $myTeamRoot->menu_id,
            'title' => 'Profile Approvals',
            'icon' => 'fa-solid fa-user-check',
            'route_name' => 'manager-portal.profile_approvals.index',
            'resource_key' => 'organization',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $myTeamRoot->menu_id,
            'title' => 'Team Attendance',
            'icon' => 'fa-solid fa-user-clock',
            'route_name' => 'manager-portal.team_attendance',
            'resource_key' => 'attendance',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $myTeamRoot->menu_id,
            'title' => 'Team Performance',
            'icon' => 'fa-solid fa-chart-column',
            'route_name' => 'manager-portal.team_performance',
            'resource_key' => 'performance',
            'sort_order' => 5,
        ]);

        // 3. Hiring Category (Recruiter Persona)
        $hiringRoot = NavigationMenu::create([
            'title' => 'Hiring',
            'icon' => 'fa-solid fa-briefcase',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $hiringRoot->menu_id,
            'title' => 'Candidate Pipeline',
            'icon' => 'fa-solid fa-network-wired',
            'route_name' => 'recruitment-applications.index',
            'resource_key' => 'recruitment',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $hiringRoot->menu_id,
            'title' => 'Job Openings',
            'icon' => 'fa-solid fa-bullhorn',
            'route_name' => 'recruitment-job-posts.index',
            'resource_key' => 'recruitment',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $hiringRoot->menu_id,
            'title' => 'Job Codes',
            'icon' => 'fa-solid fa-tags',
            'route_name' => 'recruitment-job-codes.index',
            'resource_key' => 'recruitment',
            'sort_order' => 3,
        ]);

        // 4. People Ops Category (HR Admin Persona)
        $peopleOpsRoot = NavigationMenu::create([
            'title' => 'People Ops',
            'icon' => 'fa-solid fa-sitemap',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Employees Directory',
            'icon' => 'fa-solid fa-id-card',
            'route_name' => 'employees.index',
            'resource_key' => 'employees',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Organization Structure',
            'icon' => 'fa-solid fa-building',
            'route_name' => 'departments.index',
            'resource_key' => 'organization',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Attendance Management',
            'icon' => 'fa-solid fa-business-time',
            'route_name' => 'attendance.index',
            'resource_key' => 'attendance',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Leave Management',
            'icon' => 'fa-solid fa-calendar-days',
            'route_name' => 'leaves.index',
            'resource_key' => 'leave',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Payroll & Salary',
            'icon' => 'fa-solid fa-money-bill-wave',
            'route_name' => 'payroll.index',
            'resource_key' => 'payroll',
            'sort_order' => 5,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Performance Appraisals',
            'icon' => 'fa-solid fa-award',
            'route_name' => 'performance-appraisals.index',
            'resource_key' => 'performance',
            'sort_order' => 6,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Training & Development',
            'icon' => 'fa-solid fa-graduation-cap',
            'route_name' => 'training-sessions.index',
            'resource_key' => 'training',
            'sort_order' => 7,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Company Assets',
            'icon' => 'fa-solid fa-boxes-stacked',
            'route_name' => 'assets.index',
            'resource_key' => 'assets',
            'sort_order' => 8,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Announcements',
            'icon' => 'fa-solid fa-bullhorn',
            'route_name' => 'announcements.index',
            'resource_key' => 'announcements',
            'sort_order' => 9,
        ]);

        NavigationMenu::create([
            'parent_id' => $peopleOpsRoot->menu_id,
            'title' => 'Executive Reports',
            'icon' => 'fa-solid fa-chart-pie',
            'route_name' => 'reports.index',
            'resource_key' => 'reports',
            'sort_order' => 10,
        ]);

        // 5. System Category (Super Admin Persona)
        $systemRoot = NavigationMenu::create([
            'title' => 'System',
            'icon' => 'fa-solid fa-sliders',
            'sort_order' => 5,
        ]);

        NavigationMenu::create([
            'parent_id' => $systemRoot->menu_id,
            'title' => 'System Settings',
            'icon' => 'fa-solid fa-gear',
            'route_name' => 'system-settings.index',
            'resource_key' => 'settings',
            'sort_order' => 1,
        ]);

        NavigationMenu::create([
            'parent_id' => $systemRoot->menu_id,
            'title' => 'Roles & Permissions',
            'icon' => 'fa-solid fa-user-shield',
            'route_name' => 'user-roles.index',
            'resource_key' => 'settings',
            'sort_order' => 2,
        ]);

        NavigationMenu::create([
            'parent_id' => $systemRoot->menu_id,
            'title' => 'Navigation Manager',
            'icon' => 'fa-solid fa-bars-staggered',
            'route_name' => 'settings.navigation.index',
            'resource_key' => 'settings',
            'sort_order' => 3,
        ]);

        NavigationMenu::create([
            'parent_id' => $systemRoot->menu_id,
            'title' => 'Email Templates',
            'icon' => 'fa-solid fa-envelope-open-text',
            'route_name' => 'email-templates.index',
            'resource_key' => 'settings',
            'sort_order' => 4,
        ]);

        NavigationMenu::create([
            'parent_id' => $systemRoot->menu_id,
            'title' => 'API Access Keys',
            'icon' => 'fa-solid fa-key',
            'route_name' => 'api-tokens.index',
            'resource_key' => 'api_control',
            'sort_order' => 5,
        ]);

        NavigationMenu::create([
            'parent_id' => $systemRoot->menu_id,
            'title' => 'Webhook Subscriptions',
            'icon' => 'fa-solid fa-diagram-project',
            'route_name' => 'webhooks.index',
            'resource_key' => 'api_control',
            'sort_order' => 6,
        ]);

        NavigationMenu::create([
            'parent_id' => $systemRoot->menu_id,
            'title' => 'Admin Tickets',
            'icon' => 'fa-solid fa-shield-halved',
            'route_name' => 'admin-tickets.index',
            'resource_key' => 'admin_tickets',
            'sort_order' => 7,
        ]);

        // Grant Role ID 1 (All Access)
        $adminRole = UserRole::find(1);
        if ($adminRole) {
            $adminRole->update([
                'role_access' => 'all',
                'role_resources' => implode(',', ['employees', 'organization', 'leave', 'attendance', 'payroll', 'performance', 'assets', 'recruitment', 'training', 'support_tickets', 'hr_tickets', 'admin_tickets', 'announcements', 'settings', 'api_control', 'reports'])
            ]);
        }
    }
}

