<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\JobPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobPostRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobPost::with(['company', 'designation', 'applications'])
            ->where(function ($q) {
                $q->where('show_status', '!=', 0)
                  ->where('show_status', '!=', '0')
                  ->orWhereNull('show_status');
            });

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                  ->orWhere('job_code', 'like', "%{$search}%")
                  ->orWhere('job_location', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('job_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?JobPost
    {
        return JobPost::with(['company', 'designation', 'applications'])->find($id);
    }

    public function create(array $data): JobPost
    {
        $data['status'] = $data['status'] ?? 1;
        $data['show_status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');

        return JobPost::create($data);
    }

    public function update(JobPost $jobPost, array $data): bool
    {
        $data['updated_date'] = date('Y-m-d H:i:s');
        return $jobPost->update($data);
    }

    public function getSummaryStats(): array
    {
        $all = JobPost::where(function ($q) {
            $q->where('show_status', '!=', 0)->orWhereNull('show_status');
        })->get();

        $total = $all->count();
        $active = $all->where('status', 1)->count();
        $closed = $all->where('status', 0)->count();
        $totalVacancies = (int) $all->sum('job_vacancy');

        return [
            'total_posts' => $total,
            'active_posts' => $active,
            'closed_posts' => $closed,
            'total_vacancies' => $totalVacancies,
        ];
    }
}
