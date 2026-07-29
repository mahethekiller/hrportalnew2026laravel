<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeeWorkExperience;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeWorkExperienceRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeWorkExperience::where('employee_id', $employeeId)
            ->orderBy('work_experience_id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeWorkExperience::with('employee');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->orderBy('work_experience_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeWorkExperience
    {
        return EmployeeWorkExperience::with('employee')->find($id);
    }

    public function create(array $data): EmployeeWorkExperience
    {
        return EmployeeWorkExperience::create($data);
    }

    public function update(EmployeeWorkExperience $experience, array $data): bool
    {
        return $experience->update($data);
    }

    public function delete(EmployeeWorkExperience $experience): bool
    {
        return (bool) $experience->delete();
    }
}
