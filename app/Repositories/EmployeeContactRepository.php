<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeeContact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeContactRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeContact::where('employee_id', $employeeId)
            ->orderBy('is_primary', 'desc')
            ->orderBy('contact_id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeContact::with('employee');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('contact_name', 'like', "%{$search}%")
                  ->orWhere('relation', 'like', "%{$search}%");
        }

        return $query->orderBy('contact_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeContact
    {
        return EmployeeContact::with('employee')->find($id);
    }

    public function create(array $data): EmployeeContact
    {
        return EmployeeContact::create($data);
    }

    public function update(EmployeeContact $contact, array $data): bool
    {
        return $contact->update($data);
    }

    public function delete(EmployeeContact $contact): bool
    {
        return (bool) $contact->delete();
    }
}
