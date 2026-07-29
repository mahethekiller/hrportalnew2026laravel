<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeeContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeContractRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeContract::with(['contractType', 'designation'])
            ->where('employee_id', $employeeId)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeContract::with(['employee', 'contractType', 'designation']);

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeContract
    {
        return EmployeeContract::with(['employee', 'contractType', 'designation'])->find($id);
    }

    public function create(array $data): EmployeeContract
    {
        return EmployeeContract::create($data);
    }

    public function update(EmployeeContract $contract, array $data): bool
    {
        return $contract->update($data);
    }

    public function delete(EmployeeContract $contract): bool
    {
        return (bool) $contract->delete();
    }
}
