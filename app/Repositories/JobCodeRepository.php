<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\JobCode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobCodeRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobCode::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('job_code', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('job_code_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?JobCode
    {
        return JobCode::find($id);
    }

    public function create(array $data): JobCode
    {
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['status'] = $data['status'] ?? 'active';
        $data['added_date'] = date('Y-m-d H:i:s');

        return JobCode::create($data);
    }

    public function update(JobCode $jobCode, array $data): bool
    {
        $data['updated_date'] = date('Y-m-d H:i:s');
        return $jobCode->update($data);
    }
}
