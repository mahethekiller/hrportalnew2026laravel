<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository
{
    /**
     * Get paginated employees with eager loaded relationships.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Employee::with(['user', 'department', 'designation', 'company']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $status = $filters['status'];
            if (is_numeric($status)) {
                $query->where('is_active', (int) $status);
            } elseif ($status === 'active') {
                $query->where('is_active', 1);
            } elseif ($status === 'inactive') {
                $query->where('is_active', '!=', 1);
            }
        }

        return $query->orderBy('user_id', 'desc')->paginate($perPage);
    }

    /**
     * Find employee by ID.
     */
    public function findById(int $id): ?Employee
    {
        return Employee::with(['user', 'department', 'designation', 'company', 'officeShift'])->find($id);
    }

    /**
     * Create a new employee record.
     */
    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    /**
     * Update an employee record.
     */
    public function update(Employee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    /**
     * Delete an employee record.
     */
    public function delete(Employee $employee): bool
    {
        return (bool) $employee->delete();
    }
}
