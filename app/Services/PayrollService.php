<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PayrollPayment;
use App\Models\SalaryHistory;
use App\Repositories\PayrollRepository;
use App\Repositories\SalaryHistoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayrollService
{
    public function __construct(
        protected PayrollRepository $payrollRepository,
        protected SalaryHistoryRepository $salaryHistoryRepository
    ) {}

    public function getPaymentsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->payrollRepository->getPaginated($filters, $perPage);
    }

    public function getSalaryHistoryPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->salaryHistoryRepository->getPaginated($filters, $perPage);
    }

    public function getPaymentById(int $id): ?PayrollPayment
    {
        return $this->payrollRepository->findById($id);
    }

    public function getMonthlySummary(): array
    {
        return $this->payrollRepository->getMonthlyTotals();
    }

    public function processPayment(array $data): PayrollPayment
    {
        $basic = (float) ($data['basic_salary'] ?? 0);
        $hra = (float) ($data['house_rent_allowance'] ?? 0);
        $medical = (float) ($data['medical_allowance'] ?? 0);
        $ta = (float) ($data['travelling_allowance'] ?? 0);
        $da = (float) ($data['dearness_allowance'] ?? 0);

        $pf = (float) ($data['provident_fund'] ?? 0);
        $tax = (float) ($data['tax_deduction'] ?? 0);
        $deposit = (float) ($data['security_deposit'] ?? 0);
        $advance = (float) ($data['advance_salary_amount'] ?? 0);

        $totalAllowances = $hra + $medical + $ta + $da;
        $totalDeductions = $pf + $tax + $deposit + $advance;
        $grossSalary = $basic + $totalAllowances;
        $netSalary = $grossSalary - $totalDeductions;

        $data['gross_salary'] = $grossSalary;
        $data['total_allowances'] = $totalAllowances;
        $data['total_deductions'] = $totalDeductions;
        $data['net_salary'] = $netSalary;
        $data['payment_amount'] = $netSalary;
        $data['status'] = $data['status'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->payrollRepository->create($data);
    }

    public function recordSalaryIncrement(array $data): SalaryHistory
    {
        return $this->salaryHistoryRepository->create($data);
    }
}
