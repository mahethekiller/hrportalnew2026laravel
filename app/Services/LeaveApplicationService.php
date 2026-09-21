<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LeaveApplication;
use App\Repositories\LeaveApplicationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveApplicationService
{
    public function __construct(
        protected LeaveApplicationRepository $repository,
        protected MailService $mailService
    ) {}

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?LeaveApplication
    {
        return $this->repository->findById($id);
    }

    public function getCounts(): array
    {
        return $this->repository->getCounts();
    }

    public function applyForLeave(array $data): LeaveApplication
    {
        $data['employee_id'] = $data['employee_id'] ?? (\Auth::user()->employee->user_id ?? \Auth::id());
        $employee = \App\Models\Employee::find($data['employee_id']);
        if ($employee) {
            $data['company_id'] = $data['company_id'] ?? ($employee->company_id ?: 1);
            if (empty($data['manager_id']) && !empty($employee->manager_id)) {
                $data['manager_id'] = $employee->manager_id;
            }
        } else {
            $data['company_id'] = $data['company_id'] ?? 1;
        }

        $data['status'] = $data['status'] ?? LeaveApplication::STATUS_PENDING;
        $data['applied_on'] = $data['applied_on'] ?? date('Y-m-d H:i:s');
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $data['start_duration'] = $data['start_duration'] ?? 'Full';
        $data['end_duration'] = $data['end_duration'] ?? 'Full';
        $data['casual_deducted'] = $data['casual_deducted'] ?? '0.00';
        $data['earned_deducted'] = $data['earned_deducted'] ?? '0.00';
        $data['remarks'] = $data['remarks'] ?? '';

        $leave = $this->repository->create($data);

        // Send Email Notification (code3 = Leave Request)
        try {
            $leave->load(['employee', 'leaveType', 'company', 'manager']);
            $employeeName = $leave->employee ? ($leave->employee->first_name . ' ' . $leave->employee->last_name) : 'Employee';
            
            $toEmails = [];
            if ($leave->manager && filter_var($leave->manager->email, FILTER_VALIDATE_EMAIL)) {
                $toEmails[] = trim($leave->manager->email);
            }
            if ($leave->employee && filter_var($leave->employee->email, FILTER_VALIDATE_EMAIL)) {
                $toEmails[] = trim($leave->employee->email);
            }
            $toEmails = array_unique(array_filter($toEmails));

            if (!empty($toEmails)) {
                $this->mailService->sendTemplateEmail(
                    templateCode: 'code3',
                    toEmails: $toEmails,
                    replacements: [
                        '{employee_name}' => $employeeName,
                        '{leave_type}' => $leave->leave_type_name,
                        '{from}' => $leave->from_date,
                        '{to}' => $leave->to_date,
                        '{start_date}' => $leave->from_date,
                        '{end_date}' => $leave->to_date,
                        '{reason}' => $leave->reason ?? '',
                        '{company_name}' => $leave->company->name ?? config('app.name'),
                    ],
                    moduleKey: 'leave',
                    companyId: (int) $leave->company_id,
                    actionUrl: route('leaves.index'),
                    actionText: 'Review Leave Application',
                    userId: (int) $leave->employee_id
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Leave application email dispatch error: ' . $e->getMessage());
        }

        return $leave;
    }

    public function updateStatus(LeaveApplication $leaveApplication, int $status, ?string $remarks = null): bool
    {
        $updateData = [
            'status' => $status,
        ];

        if ($remarks !== null) {
            $updateData['remarks'] = $remarks;
        }

        $result = $this->repository->update($leaveApplication, $updateData);

        if ($result) {
            try {
                $leaveApplication->load(['employee', 'leaveType', 'company']);
                $employeeEmail = $leaveApplication->employee->email ?? null;

                if ($employeeEmail) {
                    $templateCode = ($status === LeaveApplication::STATUS_APPROVED) ? 'code4' : 'code5';
                    $statusLabel = ($status === LeaveApplication::STATUS_APPROVED) ? 'Approved' : 'Rejected';

                    $this->mailService->sendTemplateEmail(
                        templateCode: $templateCode,
                        toEmails: $employeeEmail,
                        replacements: [
                            '{employee_name}' => $leaveApplication->employee->first_name . ' ' . $leaveApplication->employee->last_name,
                            '{leave_type}' => $leaveApplication->leave_type_name,
                            '{from}' => $leaveApplication->from_date,
                            '{to}' => $leaveApplication->to_date,
                            '{start_date}' => $leaveApplication->from_date,
                            '{end_date}' => $leaveApplication->to_date,
                            '{status}' => $statusLabel,
                            '{remarks}' => $remarks ?? '',
                            '{company_name}' => $leaveApplication->company->name ?? config('app.name'),
                        ],
                        moduleKey: 'leave',
                        companyId: (int) $leaveApplication->company_id,
                        actionUrl: route('leaves.index'),
                        actionText: 'View Leaves',
                        userId: (int) $leaveApplication->employee_id
                    );
                }
            } catch (\Throwable $e) {
                \Log::error('Leave status change email dispatch error: ' . $e->getMessage());
            }
        }

        return $result;
    }

    public function deleteLeave(LeaveApplication $leaveApplication): bool
    {
        return $this->repository->delete($leaveApplication);
    }
}
