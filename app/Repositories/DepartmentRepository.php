<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository
{
    public function getAll(): Collection
    {
        return Department::with(['company', 'employee'])
            ->orderBy('department_id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Department::with(['company', 'employee']);

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('department_name', 'like', "%{$search}%");
        }

        return $query->orderBy('department_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?Department
    {
        return Department::with(['company', 'employee', 'designations', 'employees'])->find($id);
    }

    public function create(array $data): Department
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data): bool
    {
        return $department->update($data);
    }

    public function delete(Department $department): bool
    {
        return (bool) $department->delete();
    }
}
