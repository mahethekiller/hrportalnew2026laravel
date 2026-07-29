<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function index(): View
    {
        $overview = $this->reportService->getExecutiveOverviewStats();

        return view('reports.index', compact('overview'));
    }

    public function employeeReports(Request $request): View
    {
        $filters = $request->only(['department_id', 'status']);
        $employees = $this->reportService->getEmployeeReports($filters);
        $departments = Department::orderBy('department_name')->get();

        return view('reports.employee', compact('employees', 'departments', 'filters'));
    }

    public function payrollReports(Request $request): View
    {
        $filters = $request->only(['month_year']);
        $payments = $this->reportService->getPayrollReports($filters);

        return view('reports.payroll', compact('payments', 'filters'));
    }

    public function auditLogs(Request $request): View
    {
        $filters = $request->only(['search']);
        $logs = $this->reportService->getAuditLogs($filters);

        return view('reports.audit_logs', compact('logs', 'filters'));
    }
}
