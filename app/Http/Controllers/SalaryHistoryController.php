<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalaryIncrementRequest;
use App\Services\EmployeeService;
use App\Services\PayrollService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalaryHistoryController extends Controller
{
    public function __construct(
        protected PayrollService $payrollService,
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);
        $salaryHistories = $this->payrollService->getSalaryHistoryPaginated($filters);
        $employees = $this->employeeService->getEmployees([], 200);

        return view('payroll.salary-history', compact('salaryHistories', 'employees', 'filters'));
    }

    public function store(StoreSalaryIncrementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['added_by'] = Auth::user()->name ?? 'System Admin';

        $this->payrollService->recordSalaryIncrement($data);

        return redirect()->route('salary-history.index')
            ->with('success', 'Salary increment history recorded successfully.');
    }
}
