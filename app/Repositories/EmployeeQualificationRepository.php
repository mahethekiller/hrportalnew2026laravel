<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeeQualification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeQualificationRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeQualification::with('language')
            ->where('employee_id', $employeeId)
            ->orderBy('qualification_id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeQualification::with(['employee', 'language']);

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->orderBy('qualification_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeQualification
    {
        return EmployeeQualification::with(['employee', 'language'])->find($id);
    }

    public function create(array $data): EmployeeQualification
    {
        return EmployeeQualification::create($data);
    }

    public function update(EmployeeQualification $qualification, array $data): bool
    {
        return $qualification->update($data);
    }

    public function delete(EmployeeQualification $qualification): bool
    {
        return (bool) $qualification->delete();
    }
}
