<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PayrollPayment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PayrollRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = PayrollPayment::with(['employee', 'department', 'designation', 'company']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('payment_method', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($eq) use ($search) {
                      $eq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('employee_id', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['date'])) {
            $query->where('payment_date', 'like', "{$filters['date']}%");
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('make_payment_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?PayrollPayment
    {
        return PayrollPayment::with(['employee', 'department', 'designation', 'company'])->find($id);
    }

    public function create(array $data): PayrollPayment
    {
        return PayrollPayment::create($data);
    }

    public function getMonthlyTotals(): array
    {
        $totalPaid = PayrollPayment::where('status', 1)->sum('net_salary');
        $totalAllowances = PayrollPayment::where('status', 1)->sum('total_allowances');
        $totalDeductions = PayrollPayment::where('status', 1)->sum('total_deductions');
        $paidCount = PayrollPayment::where('status', 1)->count();
        $unpaidCount = PayrollPayment::where('status', 0)->count();

        return [
            'total_paid' => (float) $totalPaid,
            'total_allowances' => (float) $totalAllowances,
            'total_deductions' => (float) $totalDeductions,
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
        ];
    }
}
