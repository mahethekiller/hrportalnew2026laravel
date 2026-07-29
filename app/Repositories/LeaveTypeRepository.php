<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LeaveType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class LeaveTypeRepository
{
    public function getAll(): Collection
    {
        return LeaveType::orderBy('leave_type_id', 'desc')->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = LeaveType::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('type_name', 'like', "%{$search}%");
        }

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        return $query->orderBy('leave_type_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?LeaveType
    {
        return LeaveType::find($id);
    }

    public function create(array $data): LeaveType
    {
        return LeaveType::create($data);
    }

    public function update(LeaveType $leaveType, array $data): bool
    {
        return $leaveType->update($data);
    }

    public function delete(LeaveType $leaveType): bool
    {
        return (bool) $leaveType->delete();
    }
}
