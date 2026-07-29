<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\JobApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobApplicationRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobApplication::with(['department', 'job', 'interviews'])
            ->where(function ($q) {
                $q->where('show_status', '!=', 0)
                  ->where('show_status', '!=', '0')
                  ->orWhereNull('show_status');
            });

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('candidate_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_no', 'like', "%{$search}%")
                  ->orWhere('current_company', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('application_status', $filters['status']);
        }

        return $query->orderBy('application_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?JobApplication
    {
        return JobApplication::with(['department', 'interviews'])->find($id);
    }

    public function create(array $data): JobApplication
    {
        $data['application_status'] = $data['application_status'] ?? 'Applied';
        $data['job_id'] = !empty($data['job_id']) ? (int) $data['job_id'] : 1;
        $data['show_status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');

        return JobApplication::create($data);
    }

    public function updateStatus(JobApplication $application, string $status, ?string $remarks = null): bool
    {
        return $application->update([
            'application_status' => $status,
            'application_remarks' => $remarks ?? $application->application_remarks,
            'updated_date' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getSummaryStats(): array
    {
        $all = JobApplication::where(function ($q) {
            $q->where('show_status', '!=', 0)->orWhereNull('show_status');
        })->get();

        $total = $all->count();
        $shortlisted = $all->filter(fn($item) => strtolower($item->application_status ?? '') === 'shortlisted')->count();
        $interviews = $all->filter(fn($item) => str_contains(strtolower($item->application_status ?? ''), 'interview'))->count();
        $hired = $all->filter(fn($item) => in_array(strtolower($item->application_status ?? ''), ['hired', 'offered']))->count();

        return [
            'total_applicants' => $total,
            'shortlisted_count' => $shortlisted,
            'interview_count' => $interviews,
            'hired_count' => $hired,
        ];
    }
}
