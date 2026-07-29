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
        return EmployeeResignation::where('employee_id', $employeeId)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeResignation::with('employee');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeResignation
    {
        return EmployeeResignation::with('employee')->find($id);
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
