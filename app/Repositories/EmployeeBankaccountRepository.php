<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeeBankaccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeBankaccountRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeBankaccount::where('employee_id', $employeeId)
            ->orderBy('is_primary', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeBankaccount::with('employee');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->orderBy('bankaccount_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeBankaccount
    {
        return EmployeeBankaccount::with('employee')->find($id);
    }

    public function create(array $data): EmployeeBankaccount
    {
        return EmployeeBankaccount::create($data);
    }

    public function update(EmployeeBankaccount $bankAccount, array $data): bool
    {
        return $bankAccount->update($data);
    }

    public function delete(EmployeeBankaccount $bankAccount): bool
    {
        return (bool) $bankAccount->delete();
    }
}
