<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\JobPost;
use App\Repositories\JobPostRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobPostService
{
    public function __construct(
        protected JobPostRepository $jobPostRepository
    ) {}

    public function getJobPostsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->jobPostRepository->getPaginated($filters, $perPage);
    }

    public function getJobPostById(int $id): ?JobPost
    {
        return $this->jobPostRepository->findById($id);
    }

    public function getSummaryStats(): array
    {
        return $this->jobPostRepository->getSummaryStats();
    }

    public function createJobPost(array $data): JobPost
    {
        if (empty($data['job_code'])) {
            $data['job_code'] = 'JOB-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4));
        }

        return $this->jobPostRepository->create($data);
    }

    public function updateJobPost(JobPost $jobPost, array $data): bool
    {
        return $this->jobPostRepository->update($jobPost, $data);
    }
}
