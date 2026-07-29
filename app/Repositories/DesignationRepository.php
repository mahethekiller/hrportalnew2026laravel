<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Designation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DesignationRepository
{
    public function getAll(): Collection
    {
        return Designation::with(['department', 'company'])
            ->orderBy('designation_id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Designation::with(['department', 'company']);

        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('designation_name', 'like', "%{$search}%");
        }

        return $query->orderBy('designation_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?Designation
    {
        return Designation::with(['department', 'company', 'employees'])->find($id);
    }

    public function create(array $data): Designation
    {
        return Designation::create($data);
    }

    public function update(Designation $designation, array $data): bool
    {
        return $designation->update($data);
    }

    public function delete(Designation $designation): bool
    {
        return (bool) $designation->delete();
    }
}
