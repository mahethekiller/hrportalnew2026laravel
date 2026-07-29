<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePayrollPaymentRequest;
use App\Models\PayrollPayment;
use App\Services\EmployeeService;
use App\Services\PayrollService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function __construct(
        protected PayrollService $payrollService,
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'date', 'status']);
        $payments = $this->payrollService->getPaymentsPaginated($filters);
        $summary = $this->payrollService->getMonthlySummary();
        $employees = $this->employeeService->getEmployees([], 200);

        return view('payroll.index', compact('payments', 'summary', 'employees', 'filters'));
    }

    public function store(StorePayrollPaymentRequest $request): RedirectResponse
    {
        $payment = $this->payrollService->processPayment($request->validated());

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll payment recorded successfully for Employee ID #' . $payment->employee_id);
    }

    public function payslip(PayrollPayment $payment): View
    {
        $payment->load(['employee', 'department', 'designation', 'company']);

        return view('payroll.payslip', compact('payment'));
    }
}
