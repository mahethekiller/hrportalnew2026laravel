<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LeaveApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class LeaveApplicationRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = LeaveApplication::with(['employee', 'leaveType', 'company', 'manager']);

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (int) $filters['status']);
        }

        if (!empty($filters['leave_type_id'])) {
            $query->where('leave_type_id', $filters['leave_type_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($eq) use ($search) {
                    $eq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('employee_id', 'like', "%{$search}%");
                })->orWhere('reason', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('leave_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?LeaveApplication
    {
        return LeaveApplication::with(['employee', 'leaveType', 'company', 'manager'])->find($id);
    }

    public function create(array $data): LeaveApplication
    {
        return LeaveApplication::create($data);
    }

    public function update(LeaveApplication $leaveApplication, array $data): bool
    {
        return $leaveApplication->update($data);
    }

    public function delete(LeaveApplication $leaveApplication): bool
    {
        return (bool) $leaveApplication->delete();
    }

    public function getCounts(): array
    {
        return [
            'total' => LeaveApplication::count(),
            'pending' => LeaveApplication::where('status', 1)->count(),
            'approved' => LeaveApplication::where('status', 2)->count(),
            'rejected' => LeaveApplication::where('status', 3)->count(),
        ];
    }
}
