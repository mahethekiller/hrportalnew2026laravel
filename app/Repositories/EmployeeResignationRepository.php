<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeeResignation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeResignationRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeResignation::with(['employee', 'manager', 'itPerson', 'accountPerson', 'hrPerson'])
            ->where('employee_id', $employeeId)
            ->orderBy('resignation_id', 'desc')
            ->get();
    }

    public function getByManagerId(int $managerId): Collection
    {
        return EmployeeResignation::with(['employee', 'manager', 'itPerson', 'accountPerson', 'hrPerson'])
            ->where('manager_id', $managerId)
            ->orderBy('resignation_id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeResignation::with(['employee', 'manager', 'itPerson', 'accountPerson', 'hrPerson']);

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        return $query->orderBy('resignation_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeResignation
    {
        return EmployeeResignation::with(['employee', 'manager', 'itPerson', 'accountPerson', 'hrPerson'])->find($id);
    }

    public function create(array $data): EmployeeResignation
    {
        return EmployeeResignation::create($data);
    }

    public function update(EmployeeResignation $resignation, array $data): bool
    {
        return $resignation->update($data);
    }

    public function delete(EmployeeResignation $resignation): bool
    {
        return (bool) $resignation->delete();
    }
}
