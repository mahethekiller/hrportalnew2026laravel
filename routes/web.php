<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\EmployeeContactController;
use App\Http\Controllers\EmployeeBankaccountController;
use App\Http\Controllers\EmployeeQualificationController;
use App\Http\Controllers\EmployeeWorkExperienceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/onboarding/{token}', [\App\Http\Controllers\EmployeePortalController::class, 'onboardingForm'])->name('onboarding');
Route::post('/onboarding/{token}', [\App\Http\Controllers\EmployeePortalController::class, 'storeOnboarding'])->name('onboarding.store');

// TEKKEN 7: TEEJ SPECIAL SHOWDOWN Public Standalone Routes (No Login Required)
use App\Http\Controllers\TekkenShowdownController;

Route::get('/tekken-showdown', [TekkenShowdownController::class, 'index'])->name('tekken.index');
Route::get('/tekken-showdown/admin', [TekkenShowdownController::class, 'admin'])->name('tekken.admin');
Route::post('/tekken-showdown/admin/login', [TekkenShowdownController::class, 'verifyAdminPin'])->name('tekken.admin.login');
Route::post('/tekken-showdown/admin/logout', [TekkenShowdownController::class, 'logoutAdmin'])->name('tekken.admin.logout');
Route::post('/tekken-showdown/register', [TekkenShowdownController::class, 'store'])->name('tekken.store');
Route::patch('/tekken-showdown/status/{id}', [TekkenShowdownController::class, 'updateStatus'])->name('tekken.status');
Route::delete('/tekken-showdown/{id}', [TekkenShowdownController::class, 'destroy'])->name('tekken.destroy');
Route::get('/tekken-showdown/export', [TekkenShowdownController::class, 'export'])->name('tekken.export');

Route::middleware('auth')->group(function () {
    Route::get('/ui-components', function () {
        return view('ui-components');
    })->name('ui.components');

    Route::get('/api/docs', [\App\Http\Controllers\SuperAdminApiController::class, 'docs'])->name('api.docs');

    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('companies', CompanyController::class);

    // Leave Management Routes
    Route::resource('leaves', \App\Http\Controllers\LeaveApplicationController::class)->except(['create', 'edit', 'update']);
    Route::post('/leaves/{leave}/status', [\App\Http\Controllers\LeaveApplicationController::class, 'updateStatus'])->name('leaves.update-status');
    Route::resource('leave-types', \App\Http\Controllers\LeaveTypeController::class)->except(['create', 'edit', 'show']);

    // Attendance & WFH Routes
    Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/wfh-clock-in', [\App\Http\Controllers\AttendanceController::class, 'wfhClockIn'])->name('attendance.wfh-clock-in');
    Route::post('/attendance/wfh-clock-out', [\App\Http\Controllers\AttendanceController::class, 'wfhClockOut'])->name('attendance.wfh-clock-out');
    Route::post('/attendance/manual', [\App\Http\Controllers\AttendanceController::class, 'storeManualPunch'])->name('attendance.manual');
    Route::resource('office-shifts', \App\Http\Controllers\OfficeShiftController::class)->except(['create', 'edit', 'show']);

    // Payroll & Compensation Routes
    Route::resource('payroll', \App\Http\Controllers\PayrollController::class)->except(['create', 'edit', 'update', 'destroy']);
    Route::get('/payroll/{payment}/payslip', [\App\Http\Controllers\PayrollController::class, 'payslip'])->name('payroll.payslip');
    Route::resource('salary-history', \App\Http\Controllers\SalaryHistoryController::class)->except(['create', 'edit', 'update', 'destroy']);

    // Performance Management Routes
    Route::resource('performance-appraisals', \App\Http\Controllers\PerformanceAppraisalController::class)->except(['create', 'edit', 'update', 'destroy']);
    Route::resource('performance-indicators', \App\Http\Controllers\PerformanceIndicatorController::class)->except(['create', 'edit', 'update', 'destroy']);

    // Assets & Inventory Routes
    Route::resource('company-assets', \App\Http\Controllers\AssetController::class)->names('assets')->except(['create', 'edit', 'update', 'destroy']);

    // Recruitment Routes
    Route::resource('recruitment-job-codes', \App\Http\Controllers\JobCodeController::class)->parameters(['recruitment-job-codes' => 'jobCode'])->except(['create', 'edit', 'destroy']);
    Route::resource('recruitment-job-posts', \App\Http\Controllers\JobPostController::class)->parameters(['recruitment-job-posts' => 'jobPost'])->except(['create', 'edit', 'destroy']);
    Route::resource('recruitment-applications', \App\Http\Controllers\JobApplicationController::class)->parameters(['recruitment-applications' => 'application'])->except(['create', 'edit', 'destroy']);
    Route::post('/recruitment-applications/{application}/status', [\App\Http\Controllers\JobApplicationController::class, 'updateStatus'])->name('recruitment-applications.status');
    Route::get('/recruitment-applications/{application}/resume', [\App\Http\Controllers\JobApplicationController::class, 'downloadResume'])->name('recruitment-applications.resume');
    Route::resource('recruitment-interviews', \App\Http\Controllers\JobInterviewController::class)->except(['create', 'edit', 'update', 'destroy']);
    Route::post('/recruitment-interviews/{interview}/status', [\App\Http\Controllers\JobInterviewController::class, 'updateStatus'])->name('recruitment-interviews.status');
    Route::post('/recruitment-interviews/{interview}/convert', [\App\Http\Controllers\JobInterviewController::class, 'convert'])->name('recruitment-interviews.convert');

    // Training Routes
    Route::get('/training-sessions', [\App\Http\Controllers\TrainingController::class, 'index'])->name('training-sessions.index');
    Route::post('/training-sessions', [\App\Http\Controllers\TrainingController::class, 'storeSession'])->name('training-sessions.store');
    Route::post('/training-sessions/{session}/status', [\App\Http\Controllers\TrainingController::class, 'updateStatus'])->name('training-sessions.status');
    Route::get('/trainers', [\App\Http\Controllers\TrainingController::class, 'trainers'])->name('trainers.index');
    Route::post('/trainers', [\App\Http\Controllers\TrainingController::class, 'storeTrainer'])->name('trainers.store');

    // Settings & Role Access Routes
    Route::get('/system-settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('system-settings.index');
    Route::put('/system-settings', [\App\Http\Controllers\SettingController::class, 'updateSystemSetting'])->name('system-settings.update');
    Route::get('/user-roles', [\App\Http\Controllers\SettingController::class, 'roles'])->name('user-roles.index');
    Route::post('/user-roles', [\App\Http\Controllers\SettingController::class, 'storeRole'])->name('user-roles.store');
    Route::put('/user-roles/{role}', [\App\Http\Controllers\SettingController::class, 'updateRole'])->name('user-roles.update');
    Route::get('/email-templates', [\App\Http\Controllers\SettingController::class, 'emailTemplates'])->name('email-templates.index');
    Route::put('/email-templates/{template}', [\App\Http\Controllers\SettingController::class, 'updateEmailTemplate'])->name('email-templates.update');

    // Multi-SMTP Sender Profiles & Mail System Routes
    Route::get('/smtp-profiles', [\App\Http\Controllers\SettingController::class, 'smtpProfiles'])->name('smtp-profiles.index');
    Route::post('/smtp-profiles', [\App\Http\Controllers\SettingController::class, 'saveSmtpProfile'])->name('smtp-profiles.store');
    Route::delete('/smtp-profiles/{id}', [\App\Http\Controllers\SettingController::class, 'deleteSmtpProfile'])->name('smtp-profiles.destroy');
    Route::post('/smtp-profiles/test', [\App\Http\Controllers\SettingController::class, 'testSmtpProfile'])->name('smtp-profiles.test');
    Route::post('/smtp-profiles/routing', [\App\Http\Controllers\SettingController::class, 'updateMailRouting'])->name('smtp-profiles.routing');
    Route::post('/smtp-profiles/company-routing', [\App\Http\Controllers\SettingController::class, 'updateCompanyEmailSettings'])->name('smtp-profiles.company-routing');
    Route::get('/email-logs', [\App\Http\Controllers\SettingController::class, 'emailLogs'])->name('email-logs.index');

    // Theme & Color Profiles Routes
    Route::get('/settings/theme', [\App\Http\Controllers\SettingController::class, 'themeSettings'])->name('settings.theme.index');
    Route::post('/settings/theme', [\App\Http\Controllers\SettingController::class, 'updateThemeSettings'])->name('settings.theme.update');
    Route::post('/settings/theme/user-preference', [\App\Http\Controllers\SettingController::class, 'updateUserThemePreference'])->name('settings.theme.preference');

    // Dynamic Navigation Manager Routes
    Route::get('/settings/navigation', [\App\Http\Controllers\NavigationMenuController::class, 'index'])->name('settings.navigation.index');
    Route::post('/settings/navigation', [\App\Http\Controllers\NavigationMenuController::class, 'store'])->name('settings.navigation.store');
    Route::put('/settings/navigation/{navigation}', [\App\Http\Controllers\NavigationMenuController::class, 'update'])->name('settings.navigation.update');
    Route::delete('/settings/navigation/{navigation}', [\App\Http\Controllers\NavigationMenuController::class, 'destroy'])->name('settings.navigation.destroy');
    Route::post('/settings/navigation/reorder', [\App\Http\Controllers\NavigationMenuController::class, 'reorder'])->name('settings.navigation.reorder');

    // Super Admin API Control Routes
    Route::get('/api/docs', [\App\Http\Controllers\SuperAdminApiController::class, 'docs'])->name('api.docs');
    Route::get('/api-tokens', [\App\Http\Controllers\SuperAdminApiController::class, 'tokens'])->name('api-tokens.index');
    Route::post('/api-tokens', [\App\Http\Controllers\SuperAdminApiController::class, 'storeToken'])->name('api-tokens.store');
    Route::post('/api-tokens/{token}/revoke', [\App\Http\Controllers\SuperAdminApiController::class, 'revokeToken'])->name('api-tokens.revoke');
    Route::get('/webhooks', [\App\Http\Controllers\SuperAdminApiController::class, 'webhooks'])->name('webhooks.index');
    Route::post('/webhooks', [\App\Http\Controllers\SuperAdminApiController::class, 'storeWebhook'])->name('webhooks.store');
    Route::post('/webhooks/{webhook}/toggle', [\App\Http\Controllers\SuperAdminApiController::class, 'toggleWebhook'])->name('webhooks.toggle');

    // Reporting & Audit Log Routes
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/employees', [\App\Http\Controllers\ReportController::class, 'employeeReports'])->name('reports.employees');
    Route::get('/reports/payroll', [\App\Http\Controllers\ReportController::class, 'payrollReports'])->name('reports.payroll');
    Route::get('/reports/audit-logs', [\App\Http\Controllers\ReportController::class, 'auditLogs'])->name('reports.audit_logs');

    // Employee Sub-Resource Routes
    Route::post('/employee-documents', [EmployeeDocumentController::class, 'store'])->name('employee-documents.store');
    Route::delete('/employee-documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('employee-documents.destroy');

    Route::post('/employee-contacts', [EmployeeContactController::class, 'store'])->name('employee-contacts.store');
    Route::delete('/employee-contacts/{contact}', [EmployeeContactController::class, 'destroy'])->name('employee-contacts.destroy');

    Route::post('/employee-bankaccounts', [EmployeeBankaccountController::class, 'store'])->name('employee-bankaccounts.store');
    Route::delete('/employee-bankaccounts/{bankaccount}', [EmployeeBankaccountController::class, 'destroy'])->name('employee-bankaccounts.destroy');

    Route::post('/employee-qualifications', [EmployeeQualificationController::class, 'store'])->name('employee-qualifications.store');
    Route::delete('/employee-qualifications/{qualification}', [EmployeeQualificationController::class, 'destroy'])->name('employee-qualifications.destroy');

    Route::post('/employee-experiences', [EmployeeWorkExperienceController::class, 'store'])->name('employee-experiences.store');
    Route::delete('/employee-experiences/{experience}', [EmployeeWorkExperienceController::class, 'destroy'])->name('employee-experiences.destroy');

    // Support Ticket Routes
    Route::resource('support-tickets', \App\Http\Controllers\SupportTicketController::class);
    Route::post('/support-tickets/{support_ticket}/comments', [\App\Http\Controllers\SupportTicketController::class, 'addComment'])->name('support-tickets.comments');
    Route::post('/support-tickets/{support_ticket}/attachments', [\App\Http\Controllers\SupportTicketController::class, 'uploadAttachment'])->name('support-tickets.attachments');
    Route::post('/support-tickets/{support_ticket}/status', [\App\Http\Controllers\SupportTicketController::class, 'updateStatus'])->name('support-tickets.status');

    // HR Ticket Routes
    Route::resource('hr-tickets', \App\Http\Controllers\HrTicketController::class);
    Route::post('/hr-tickets/{hr_ticket}/status', [\App\Http\Controllers\HrTicketController::class, 'updateStatus'])->name('hr-tickets.status');

    // Admin Ticket Routes
    Route::resource('admin-tickets', \App\Http\Controllers\AdminTicketController::class);
    Route::post('/admin-tickets/{admin_ticket}/status', [\App\Http\Controllers\AdminTicketController::class, 'updateStatus'])->name('admin-tickets.status');

    // Employee Self-Service (ESS) Portal Routes
    Route::prefix('my-portal')->name('my-portal.')->group(function () {
        Route::get('/', [\App\Http\Controllers\EmployeePortalController::class, 'index'])->name('index');
        Route::get('/leaves', [\App\Http\Controllers\EmployeePortalController::class, 'leaves'])->name('leaves');
        Route::post('/leaves', [\App\Http\Controllers\EmployeePortalController::class, 'storeLeave'])->name('leaves.store');
        Route::get('/attendance', [\App\Http\Controllers\EmployeePortalController::class, 'attendance'])->name('attendance');
        Route::get('/payslips', [\App\Http\Controllers\EmployeePortalController::class, 'payslips'])->name('payslips');
        Route::get('/performance-feedback', [\App\Http\Controllers\EmployeePortalController::class, 'performanceFeedback'])->name('performance_feedback');
        Route::post('/performance-feedback', [\App\Http\Controllers\EmployeePortalController::class, 'storePerformanceFeedback'])->name('performance_feedback.store');
        Route::get('/benefits', [\App\Http\Controllers\EmployeePortalController::class, 'benefits'])->name('benefits');
        Route::get('/referrals', [\App\Http\Controllers\EmployeePortalController::class, 'referrals'])->name('referrals');
        Route::post('/referrals', [\App\Http\Controllers\EmployeePortalController::class, 'storeReferral'])->name('referrals.store');
        Route::get('/meetings', [\App\Http\Controllers\EmployeePortalController::class, 'meetings'])->name('meetings');
        Route::post('/meetings', [\App\Http\Controllers\EmployeePortalController::class, 'storeMeeting'])->name('meetings.store');
        Route::get('/conveyance', [\App\Http\Controllers\EmployeePortalController::class, 'conveyance'])->name('conveyance');
        Route::post('/conveyance', [\App\Http\Controllers\EmployeePortalController::class, 'storeConveyance'])->name('conveyance.store');
        Route::get('/tax-documents', [\App\Http\Controllers\EmployeePortalController::class, 'taxDocuments'])->name('tax_documents');
        Route::post('/tax-documents', [\App\Http\Controllers\EmployeePortalController::class, 'storeTaxDocument'])->name('tax_documents.store');
        Route::get('/resignation', [\App\Http\Controllers\EmployeePortalController::class, 'resignation'])->name('resignation');
        Route::post('/resignation', [\App\Http\Controllers\EmployeePortalController::class, 'storeResignation'])->name('resignation.store');
        Route::post('/resignation/exit-form', [\App\Http\Controllers\EmployeePortalController::class, 'storeExitForm'])->name('resignation.exit_form');
        Route::get('/team-resignations', [\App\Http\Controllers\EmployeePortalController::class, 'teamResignations'])->name('team_resignations');
        Route::post('/team-resignations/{id}', [\App\Http\Controllers\EmployeePortalController::class, 'respondResignation'])->name('team_resignations.respond');
        Route::get('/resignation/{id}/relieving-letter', [\App\Http\Controllers\EmployeePortalController::class, 'downloadRelievingLetter'])->name('resignation.relieving_letter');
        Route::get('/resignation/{id}/experience-certificate', [\App\Http\Controllers\EmployeePortalController::class, 'downloadExperienceCertificate'])->name('resignation.experience_certificate');
        Route::get('/profile-update', [\App\Http\Controllers\EmployeePortalController::class, 'editProfile'])->name('profile-update');
        Route::post('/profile-update', [\App\Http\Controllers\EmployeePortalController::class, 'updateProfile'])->name('profile-update.store');
    });

    // Departmental No-Dues Clearance Hub Routes
    Route::get('/settings/clearance', [\App\Http\Controllers\SeparationClearanceController::class, 'index'])->name('clearance.index');
    Route::post('/settings/clearance/default-officers', [\App\Http\Controllers\SeparationClearanceController::class, 'updateDefaultOfficers'])->name('clearance.default_officers');
    Route::post('/settings/clearance/{id}/assign', [\App\Http\Controllers\SeparationClearanceController::class, 'assignOfficers'])->name('clearance.assign');
    Route::post('/settings/clearance/{id}/notify', [\App\Http\Controllers\SeparationClearanceController::class, 'notifyOfficer'])->name('clearance.notify');
    Route::post('/settings/clearance/{id}/update', [\App\Http\Controllers\SeparationClearanceController::class, 'updateClearance'])->name('clearance.update');

    // Manager Team Hub Routes
    Route::prefix('manager-portal')->name('manager-portal.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ManagerPortalController::class, 'index'])->name('index');
        Route::get('/team-attendance', [\App\Http\Controllers\ManagerPortalController::class, 'teamAttendance'])->name('team_attendance');
        Route::get('/team-leaves', [\App\Http\Controllers\ManagerPortalController::class, 'teamLeaves'])->name('team_leaves');
        Route::post('/team-leaves/{leave}/status', [\App\Http\Controllers\ManagerPortalController::class, 'updateLeaveStatus'])->name('team_leaves.status');
        Route::get('/team-performance', [\App\Http\Controllers\ManagerPortalController::class, 'teamPerformance'])->name('team_performance');
        Route::get('/profile-approvals', [\App\Http\Controllers\ManagerPortalController::class, 'pendingProfileUpdates'])->name('profile_approvals.index');
        Route::get('/profile-approvals/{update}', [\App\Http\Controllers\ManagerPortalController::class, 'viewProfileUpdate'])->name('profile_approvals.show');
        Route::post('/profile-approvals/{update}/approve', [\App\Http\Controllers\ManagerPortalController::class, 'approveProfileUpdate'])->name('profile_approvals.approve');
    });

    // Announcement Routes
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
