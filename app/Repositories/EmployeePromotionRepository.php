<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeePromotion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeePromotionRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeePromotion::where('employee_id', $employeeId)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeePromotion::with('employee');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeePromotion
    {
        return EmployeePromotion::with('employee')->find($id);
    }

    public function create(array $data): EmployeePromotion
    {
        return EmployeePromotion::create($data);
    }

    public function update(EmployeePromotion $promotion, array $data): bool
    {
        return $promotion->update($data);
    }

    public function delete(EmployeePromotion $promotion): bool
    {
        return (bool) $promotion->delete();
    }
}
