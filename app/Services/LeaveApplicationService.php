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
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['employee_id'] = $data['employee_id'] ?? (\Auth::user()->employee->user_id ?? \Auth::id());
        $data['status'] = $data['status'] ?? LeaveApplication::STATUS_PENDING;
        $data['applied_on'] = $data['applied_on'] ?? date('Y-m-d H:i:s');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['start_duration'] = $data['start_duration'] ?? 'full_day';
        $data['end_duration'] = $data['end_duration'] ?? 'full_day';
        $data['casual_deducted'] = $data['casual_deducted'] ?? 0;
        $data['earned_deducted'] = $data['earned_deducted'] ?? 0;

        $leave = $this->repository->create($data);

        // Send Email Notification (code3 = Leave Request)
        try {
            $leave->load(['employee', 'leaveType', 'company', 'manager']);
            $employeeName = $leave->employee ? ($leave->employee->first_name . ' ' . $leave->employee->last_name) : 'Employee';
            $recipientEmail = $leave->manager && $leave->manager->email ? $leave->manager->email : ($leave->employee->email ?? null);

            if ($recipientEmail) {
                $this->mailService->sendTemplateEmail(
                    templateCode: 'code3',
                    toEmails: $recipientEmail,
                    replacements: [
                        '{employee_name}' => $employeeName,
                        '{leave_type}' => $leave->leaveType->type_name ?? 'Leave',
                        '{start_date}' => $leave->from_date,
                        '{end_date}' => $leave->to_date,
                        '{reason}' => $leave->reason ?? '',
                        '{company_name}' => $leave->company->name ?? config('app.name'),
                    ],
                    moduleKey: 'leave',
                    companyId: (int) $leave->company_id,
                    actionUrl: route('leave-applications.index'),
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
                            '{leave_type}' => $leaveApplication->leaveType->type_name ?? 'Leave',
                            '{start_date}' => $leaveApplication->from_date,
                            '{end_date}' => $leaveApplication->to_date,
                            '{status}' => $statusLabel,
                            '{remarks}' => $remarks ?? '',
                            '{company_name}' => $leaveApplication->company->name ?? config('app.name'),
                        ],
                        moduleKey: 'leave',
                        companyId: (int) $leaveApplication->company_id,
                        actionUrl: route('my-leaves.index'),
                        actionText: 'View My Leaves',
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
