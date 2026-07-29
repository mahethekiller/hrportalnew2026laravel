<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SalaryHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SalaryHistoryRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = SalaryHistory::with('employee')
            ->where(function ($q) {
                $q->where('show_status', '!=', 0)
                  ->where('show_status', '!=', '0')
                  ->orWhereNull('show_status');
            });

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('employee', function ($eq) use ($search) {
                $eq->where('first_name', 'like', "%{$search}%")
                   ->orWhere('last_name', 'like', "%{$search}%")
                   ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function create(array $data): SalaryHistory
    {
        $data['show_status'] = $data['show_status'] ?? 1;
        $data['added_date'] = $data['added_date'] ?? date('Y-m-d H:i:s');

        return SalaryHistory::create($data);
    }
}
