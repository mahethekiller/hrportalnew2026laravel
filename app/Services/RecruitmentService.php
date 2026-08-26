<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobInterview;
use App\Repositories\JobApplicationRepository;
use App\Repositories\JobInterviewRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RecruitmentService
{
    public function __construct(
        protected JobApplicationRepository $applicationRepository,
        protected JobInterviewRepository $interviewRepository,
        protected \App\Services\EmployeeService $employeeService
    ) {}

    public function getApplicationsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applicationRepository->getPaginated($filters, $perPage);
    }

    public function getInterviewsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->interviewRepository->getPaginated($filters, $perPage);
    }

    public function getApplicationById(int $id): ?JobApplication
    {
        return $this->applicationRepository->findById($id);
    }

    public function getSummaryStats(): array
    {
        return $this->applicationRepository->getSummaryStats();
    }

    public function createApplication(array $data): JobApplication
    {
        return $this->applicationRepository->create($data);
    }

    public function updateApplication(JobApplication $application, array $data): bool
    {
        return $this->applicationRepository->update($application, $data);
    }

    public function updateApplicationStatus(JobApplication $application, string $status, ?string $remarks = null): bool
    {
        return $this->applicationRepository->updateStatus($application, $status, $remarks);
    }

    public function scheduleInterview(array $data): JobInterview
    {
        $interview = $this->interviewRepository->create($data);

        if (!empty($data['application_id'])) {
            $application = $this->applicationRepository->findById((int) $data['application_id']);
            if ($application) {
                $this->applicationRepository->updateStatus($application, 'Interview Scheduled');
            }
        }

        return $interview;
    }

    public function updateInterviewStatus(JobInterview $interview, string $status): bool
    {
        return $this->interviewRepository->updateStatus($interview, $status);
    }

    public function convertToEmployee(JobInterview $interview): ?\App\Models\Employee
    {
        $application = $interview->jobApplication;
        if (!$application) {
            return null;
        }

        $names = explode(' ', trim($application->candidate_name), 2);
        $firstName = $names[0] ?? 'Candidate';
        $lastName = $names[1] ?? 'Employee';

        $employee = $this->employeeService->createEmployee([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $application->email ?? ('emp_' . uniqid() . '@example.com'),
            'contact_no' => $application->contact_no ?? '0000000000',
            'gender' => $application->gender ?? 'Male',
            'department_id' => $application->department_id ?? 1,
            'designation_id' => 1,
            'company_id' => 1,
            'password' => '12345678',
        ]);

        $interview->update([
            'convert_to_employee' => 1,
            'employee_id' => $employee->user_id,
            'status' => 'confirmed',
        ]);

        $this->applicationRepository->updateStatus($application, 'Hired', 'Converted to Employee ID: ' . $employee->user_id);
        $application->update(['user_id' => $employee->user_id]);

        return $employee;
    }
}
