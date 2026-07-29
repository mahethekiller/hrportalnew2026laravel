<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PerformanceAppraisal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PerformanceAppraisalRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = PerformanceAppraisal::with(['employee', 'manager', 'company'])
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

        if (!empty($filters['period'])) {
            $query->where('appraisal_year_month', $filters['period']);
        }

        return $query->orderBy('performance_appraisal_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?PerformanceAppraisal
    {
        return PerformanceAppraisal::with(['employee', 'manager', 'company'])->find($id);
    }

    public function create(array $data): PerformanceAppraisal
    {
        $data['show_status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');

        return PerformanceAppraisal::create($data);
    }

    public function getSummaryStats(): array
    {
        $all = PerformanceAppraisal::where(function ($q) {
            $q->where('show_status', '!=', 0)->orWhereNull('show_status');
        })->get();

        $totalCount = $all->count();
        if ($totalCount === 0) {
            return [
                'total_appraisals' => 0,
                'average_score' => 0.0,
                'outstanding_count' => 0,
                'meets_count' => 0,
            ];
        }

        $scores = $all->map(fn($item) => $item->overall_rating);
        $avgScore = round($scores->average(), 1);
        $outstanding = $scores->filter(fn($score) => $score >= 4.5)->count();
        $meets = $scores->filter(fn($score) => $score >= 3.0 && $score < 4.5)->count();

        return [
            'total_appraisals' => $totalCount,
            'average_score' => $avgScore,
            'outstanding_count' => $outstanding,
            'meets_count' => $meets,
        ];
    }
}
