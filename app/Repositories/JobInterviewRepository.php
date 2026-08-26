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
        $data['added_by'] = !empty($data['added_by']) && is_numeric($data['added_by']) ? (int) $data['added_by'] : (auth()->id() ?? 1);

        if (isset($data['interviewers_id'])) {
            if (is_array($data['interviewers_id'])) {
                $data['interviewers_id'] = implode(',', array_filter($data['interviewers_id']));
            }
        } else {
            $data['interviewers_id'] = '';
        }

        $data['interview_place'] = $data['interview_place'] ?? '';
        $data['interview_date2'] = $data['interview_date2'] ?? '';
        $data['new_date'] = $data['new_date'] ?? '';
        $data['next_round_date'] = $data['next_round_date'] ?? '';
        $data['interviewees_id'] = !empty($data['interviewees_id']) ? (int) $data['interviewees_id'] : 0;
        $data['expected_doj'] = $data['expected_doj'] ?? '';
        $data['offered_ctc'] = $data['offered_ctc'] ?? '';
        $data['description'] = $data['description'] ?? '';
        $data['remarks'] = $data['remarks'] ?? '';
        $data['offer_status'] = $data['offer_status'] ?? '';
        $data['salary_template_id'] = !empty($data['salary_template_id']) ? (int) $data['salary_template_id'] : 0;
        $data['convert_to_employee'] = !empty($data['convert_to_employee']) ? (int) $data['convert_to_employee'] : 0;
        $data['employee_id'] = !empty($data['employee_id']) ? (int) $data['employee_id'] : 0;
        $data['updated_by'] = !empty($data['updated_by']) ? (int) $data['updated_by'] : 0;
        $data['updated_date'] = $data['updated_date'] ?? '';

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
