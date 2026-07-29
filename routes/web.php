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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::resource('recruitment-applications', \App\Http\Controllers\JobApplicationController::class)->except(['create', 'edit', 'update', 'destroy']);
    Route::post('/recruitment-applications/{application}/status', [\App\Http\Controllers\JobApplicationController::class, 'updateStatus'])->name('recruitment-applications.status');
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

    // Announcement Routes
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
