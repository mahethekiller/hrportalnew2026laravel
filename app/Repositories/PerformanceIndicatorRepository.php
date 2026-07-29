<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PerformanceIndicator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PerformanceIndicatorRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = PerformanceIndicator::with(['designation', 'company']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('designation', function ($dq) use ($search) {
                $dq->where('designation_name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('performance_indicator_id', 'desc')->paginate($perPage);
    }

    public function create(array $data): PerformanceIndicator
    {
        $data['created_at'] = date('Y-m-d H:i:s');

        return PerformanceIndicator::create($data);
    }
}
