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
        protected \App\Services\EmployeeService $employeeService,
        protected \App\Services\MailService $mailService
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
        $appId = $data['application_id'] ?? $data['job_application_id'] ?? null;
        if (!empty($appId)) {
            $existing = \App\Models\JobInterview::where('application_id', $appId)
                ->where('interview_date', $data['interview_date'] ?? date('Y-m-d'))
                ->first();
            if ($existing) {
                $formattedDate = date('F d, Y', strtotime($existing->interview_date));
                throw new \InvalidArgumentException("An interview is already scheduled for this candidate on {$formattedDate}.");
            }
        }

        $interview = $this->interviewRepository->create($data);

        if (!empty($data['application_id'])) {
            $application = $this->applicationRepository->findById((int) $data['application_id']);
            if ($application) {
                $updateData = ['application_status' => 'Interview Scheduled'];
                if (!empty($data['application_remarks'])) {
                    $updateData['application_remarks'] = $data['application_remarks'];
                }
                $this->applicationRepository->update($application, $updateData);
                $interview->setRelation('jobApplication', $application);
            }
        }

        $this->dispatchInterviewEmail($interview, $data);

        return $interview;
    }

    public function updateInterviewStatus(JobInterview $interview, string $status, array $extraData = []): bool
    {
        if (!empty($extraData['application_remarks']) && $interview->jobApplication) {
            $this->applicationRepository->update($interview->jobApplication, [
                'application_remarks' => $extraData['application_remarks']
            ]);
        }

        $updated = $this->interviewRepository->updateStatus($interview, $status, $extraData);

        if ($updated) {
            $interview->refresh();
            $interview->load('jobApplication');
            $this->dispatchInterviewEmail($interview, $extraData);
        }

        return $updated;
    }

    public function updateInterview(JobInterview $interview, array $data): bool
    {
        if (!empty($data['application_remarks']) && $interview->jobApplication) {
            $this->applicationRepository->update($interview->jobApplication, [
                'application_remarks' => $data['application_remarks']
            ]);
        }

        $updated = $this->interviewRepository->update($interview, $data);

        if ($updated) {
            $interview->refresh();
            $interview->load('jobApplication');
            $this->dispatchInterviewEmail($interview, $data);
        }

        return $updated;
    }

    protected function dispatchInterviewEmail(JobInterview $interview, array $options = []): void
    {
        $sendMail = filter_var($options['send_email_notification'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if (!$sendMail) {
            \Illuminate\Support\Facades\Log::info("Interview email skipped: send_email_notification disabled in options.", [
                'interview_id' => $interview->job_interview_id,
                'send_email_notification' => $options['send_email_notification'] ?? 'not_set'
            ]);
            return;
        }

        $notifyCandidate = isset($options['notify_candidate'])
            ? filter_var($options['notify_candidate'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true
            : true;

        $notifyInterviewers = isset($options['notify_interviewers'])
            ? filter_var($options['notify_interviewers'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true
            : true;

        $candidateEmail = trim((string) ($interview->jobApplication->email ?? ''));
        $panelists = $interview->interviewer_list;
        $ccEmails = [];

        if ($notifyInterviewers && $panelists->isNotEmpty()) {
            $ccEmails = array_filter(array_map('trim', $panelists->pluck('email')->toArray()), fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL));
        }

        if (!empty($options['cc_employees_id'])) {
            $ccIds = is_array($options['cc_employees_id']) ? $options['cc_employees_id'] : explode(',', (string) $options['cc_employees_id']);
            $extraCcEmps = \App\Models\Employee::whereIn('user_id', array_filter($ccIds))
                ->orWhereIn('employee_id', array_filter($ccIds))
                ->pluck('email')
                ->toArray();
            $extraCcEmails = array_filter(array_map('trim', $extraCcEmps), fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL));
            $ccEmails = array_unique(array_merge($ccEmails, $extraCcEmails));
        }

        $recipients = [];
        if ($notifyCandidate && !empty($candidateEmail) && filter_var($candidateEmail, FILTER_VALIDATE_EMAIL)) {
            $recipients[] = $candidateEmail;
        }

        if (empty($recipients) && empty($ccEmails)) {
            \Illuminate\Support\Facades\Log::warning("Interview email skipped: No valid candidate or interviewer email addresses found.", [
                'interview_id' => $interview->job_interview_id,
                'raw_candidate_email' => $interview->jobApplication->email ?? 'NULL'
            ]);
            return;
        }

        $customSubject = !empty($options['custom_email_subject']) ? (string) $options['custom_email_subject'] : null;
        $customBody = !empty($options['custom_email_body']) ? (string) $options['custom_email_body'] : null;

        try {
            $this->mailService->applySmtpForModule('recruitment');
            $mailable = new \App\Mail\CandidateInterviewScheduledMail($interview, $customSubject, $customBody);

            if (!empty($recipients)) {
                $pendingMail = \Illuminate\Support\Facades\Mail::to($recipients);
                if (!empty($ccEmails)) {
                    $pendingMail->cc($ccEmails);
                }
                $pendingMail->send($mailable);
            } elseif (!empty($ccEmails)) {
                \Illuminate\Support\Facades\Mail::to($ccEmails)->send($mailable);
            }

            // Log sent email to EmailHistory table
            try {
                \App\Models\EmailHistory::create([
                    'user_id' => auth()->id() ?? $interview->added_by ?? 1,
                    'email_to' => implode(', ', array_merge($recipients, $ccEmails)),
                    'subject' => $mailable->customSubject ?? 'Candidate Interview Notice',
                    'message' => $mailable->customBody ?? 'Interview invitation email sent.',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            } catch (\Throwable $eh) {
                \Illuminate\Support\Facades\Log::warning("Failed logging EmailHistory: " . $eh->getMessage());
            }

            \Illuminate\Support\Facades\Log::info("Interview notification email sent successfully to: " . implode(', ', array_merge($recipients, $ccEmails)));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending interview notification mail: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
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
