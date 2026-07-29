<?php

use App\Http\Controllers\Api\V1\EmployeeApiController;
use App\Http\Controllers\Api\V1\DepartmentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->as('api.v1.')->group(function () {
    Route::apiResource('employees', EmployeeApiController::class);
    Route::apiResource('departments', DepartmentApiController::class);
    Route::apiResource('leaves', \App\Http\Controllers\Api\V1\LeaveApplicationApiController::class);
    Route::put('leaves/{leave}/status', [\App\Http\Controllers\Api\V1\LeaveApplicationApiController::class, 'updateStatus'])->name('leaves.update-status');

    // Attendance & WFH API Endpoints
    Route::get('attendance', [\App\Http\Controllers\Api\V1\AttendanceApiController::class, 'index'])->name('attendance.index');
    Route::post('attendance/manual', [\App\Http\Controllers\Api\V1\AttendanceApiController::class, 'storeOfficePunch'])->name('attendance.manual');
    Route::get('wfh-clocking', [\App\Http\Controllers\Api\V1\AttendanceApiController::class, 'wfhIndex'])->name('wfh.index');
    Route::post('wfh-clocking/clock-in', [\App\Http\Controllers\Api\V1\AttendanceApiController::class, 'wfhClockIn'])->name('wfh.clock-in');
    Route::post('wfh-clocking/clock-out', [\App\Http\Controllers\Api\V1\AttendanceApiController::class, 'wfhClockOut'])->name('wfh.clock-out');

    // Payroll API Endpoints
    Route::apiResource('payroll', \App\Http\Controllers\Api\V1\PayrollApiController::class)->only(['index', 'show', 'store']);

    // Performance API Endpoints
    Route::apiResource('performance-appraisals', \App\Http\Controllers\Api\V1\PerformanceApiController::class)->only(['index', 'show', 'store']);

    // Assets API Endpoints
    Route::apiResource('assets', \App\Http\Controllers\Api\V1\AssetApiController::class)->only(['index', 'show', 'store']);

    // Recruitment API Endpoints
    Route::apiResource('job-posts', \App\Http\Controllers\Api\V1\JobPostApiController::class)->only(['index', 'show', 'store']);
    Route::apiResource('job-applications', \App\Http\Controllers\Api\V1\RecruitmentApiController::class)->only(['index', 'show', 'store']);

    // Training API Endpoints
    Route::apiResource('training-sessions', \App\Http\Controllers\Api\V1\TrainingApiController::class)->only(['index', 'show', 'store']);

    // Settings API Endpoints
    Route::get('/system-settings', [\App\Http\Controllers\Api\V1\SettingApiController::class, 'index']);
    Route::put('/system-settings', [\App\Http\Controllers\Api\V1\SettingApiController::class, 'update']);

    // Reporting API Endpoints
    Route::get('/reports/summary', [\App\Http\Controllers\Api\V1\ReportApiController::class, 'summary']);
});
