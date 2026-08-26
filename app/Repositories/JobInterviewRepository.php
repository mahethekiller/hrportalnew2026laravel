<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\JobInterview;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobInterviewRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobInterview::with(['jobApplication', 'interviewer']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('jobApplication', function ($aq) use ($search) {
                $aq->where('candidate_name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('job_interview_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?JobInterview
    {
        return JobInterview::with(['jobApplication', 'interviewer'])->find($id);
    }

    public function create(array $data): JobInterview
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['show_status'] = 1;
        $data['status'] = $data['status'] ?? 'pending';
        $data['job_id'] = !empty($data['job_id']) ? (int) $data['job_id'] : 1;

        if (isset($data['interviewers_id'])) {
            if (is_array($data['interviewers_id'])) {
                $data['interviewers_id'] = implode(',', array_filter($data['interviewers_id']));
            }
        }

        return JobInterview::create($data);
    }

    public function updateStatus(JobInterview $interview, string $status): bool
    {
        return $interview->update([
            'status' => strtolower($status),
            'updated_date' => date('Y-m-d H:i:s'),
        ]);
    }
}
