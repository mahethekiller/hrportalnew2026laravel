<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Asset;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeLog;
use App\Models\LeaveApplication;
use App\Models\PayrollPayment;
use App\Models\TrainingSession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    public function getExecutiveOverviewStats(): array
    {
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalPayrollDisbursed = (float) PayrollPayment::sum('net_salary');
        $pendingLeaves = LeaveApplication::where('status', 1)->count();
        $approvedLeaves = LeaveApplication::where('status', 2)->count();
        $totalAssets = Asset::count();
        $totalTrainings = TrainingSession::count();

        // Department breakdown
        $departmentBreakdown = Department::withCount('employees')->get();

        return [
            'total_employees' => $totalEmployees,
            'total_departments' => $totalDepartments,
            'total_payroll' => '₹' . number_format($totalPayrollDisbursed, 2),
            'pending_leaves' => $pendingLeaves,
            'approved_leaves' => $approvedLeaves,
            'total_assets' => $totalAssets,
            'total_trainings' => $totalTrainings,
            'department_breakdown' => $departmentBreakdown,
        ];
    }

    public function getEmployeeReports(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Employee::with(['department', 'designation']);

        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status']);
        }

        return $query->orderBy('user_id', 'desc')->paginate($perPage);
    }

    public function getPayrollReports(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = PayrollPayment::with(['employee', 'department']);

        if (!empty($filters['month_year'])) {
            $query->where('payment_date', 'like', $filters['month_year'] . '%');
        }

        return $query->orderBy('make_payment_id', 'desc')->paginate($perPage);
    }

    public function getAuditLogs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeLog::with(['department', 'designation']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                   ->orWhere('last_name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%")
                   ->orWhere('updates', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }
}
