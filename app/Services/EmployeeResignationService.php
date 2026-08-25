<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\UploadHelper;
use App\Models\Employee;
use App\Models\EmployeeResignation;
use App\Models\EmployeeResignationLog;
use App\Repositories\EmployeeResignationRepository;
use App\Traits\HasCleanContent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class EmployeeResignationService
{
    use HasCleanContent;

    public function __construct(
        protected EmployeeResignationRepository $repository,
        protected MailService $mailService
    ) {}

    public function getEmployeeResignations(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeeResignation
    {
        return $this->repository->findById($id);
    }

    /**
     * Submit new resignation request.
     */
    public function submitResignation(array $data, Employee $employee): EmployeeResignation
    {
        $noticeDate = $data['notice_date'] ?? date('Y-m-d');
        $calculatedLwd = $employee->calculateLwd($noticeDate)->format('Y-m-d');
        $requestedLwd = !empty($data['resignation_date']) ? $data['resignation_date'] : $calculatedLwd;

        $sanitizedReason = self::sanitizeContent($data['reason'] ?? '', false);

        $resignationData = [
            'company_id' => $employee->company_id ?? 1,
            'employee_id' => $employee->user_id,
            'manager_id' => $employee->manager_id ?? 0,
            'notice_date' => $noticeDate,
            'resignation_date' => $requestedLwd,
            'requested_notice' => $employee->notice_period_months . ' Month(s)',
            'reason' => $sanitizedReason,
            'manager_comment' => '',
            'it_comment' => '',
            'account_comment' => '',
            'hr_comment' => '',
            'coo_comment' => '',
            'sage_comment' => '',
            'login_comment' => '',
            'it_person' => 0,
            'account_per' => 0,
            'hr_person' => 0,
            'manager_person' => $employee->manager_id ?? 0,
            'sage_person' => 0,
            'login_person' => 0,
            'employee_accept' => 'Pending',
            'comments' => '',
            'exit_form' => '',
            'status' => 'Pending',
            'added_by' => $employee->user_id,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $resignation = $this->repository->create($resignationData);

        // Audit Log Entry
        EmployeeResignationLog::create([
            'resignation_id' => $resignation->resignation_id,
            'company_id' => $resignation->company_id,
            'employee_id' => $resignation->employee_id,
            'notice_date' => $resignation->notice_date,
            'resignation_date' => $resignation->resignation_date,
            'reason' => $resignation->reason,
            'added_by' => $employee->user_id,
            'updated_by' => $employee->user_id,
            'updated_date' => date('Y-m-d H:i:s'),
        ]);

        // Single-Threaded Email Dispatch to Employee, Manager, Kamal Sir & Priyanka
        $actionUrl = url('/my-portal/team-resignations?resignation_id=' . $resignation->resignation_id);
        $bodyHtml = "<p>A new resignation request has been initiated by <strong>{$employee->first_name} {$employee->last_name}</strong> (Employee ID: {$employee->employee_id}).</p>"
            . "<p><strong>Notice Date:</strong> {$resignation->notice_date}<br>"
            . "<strong>Notice Period:</strong> {$employee->notice_period_months} Month(s)<br>"
            . "<strong>Requested Last Working Day (LWD):</strong> {$resignation->resignation_date}</p>"
            . "<p><strong>Resignation Reason / Remarks:</strong><br>{$resignation->clean_reason}</p>";

        $this->mailService->sendResignationNotification('submitted', $resignation, '', $bodyHtml, $actionUrl);

        return $resignation;
    }

    /**
     * Reporting Manager Response & LWD Confirmation.
     */
    public function respondByManager(EmployeeResignation $resignation, array $data, ?object $actor = null): bool
    {
        $statusVal = (int) ($data['status'] ?? 1); // 1 = Approved, 2 = Rejected
        $confirmedLwd = !empty($data['resignation_date']) ? $data['resignation_date'] : $resignation->resignation_date;
        $sanitizedComment = self::sanitizeContent($data['manager_comment'] ?? '', false);
        $actorId = $actor ? ($actor->user_id ?? $actor->id ?? $resignation->manager_id) : $resignation->manager_id;

        $updateData = [
            'manager_status' => $statusVal,
            'manager_comment' => $sanitizedComment,
            'manager_person' => $actorId,
            'resignation_date' => $confirmedLwd,
            'status' => $statusVal === 1 ? 'Approved' : 'Rejected',
        ];

        $updated = $this->repository->update($resignation, $updateData);

        if ($updated) {
            // Audit Log Entry
            EmployeeResignationLog::create([
                'resignation_id' => $resignation->resignation_id,
                'company_id' => $resignation->company_id,
                'employee_id' => $resignation->employee_id,
                'notice_date' => $resignation->notice_date,
                'resignation_date' => $confirmedLwd,
                'reason' => $resignation->reason,
                'added_by' => $resignation->added_by,
                'updated_by' => $actorId,
                'updated_date' => date('Y-m-d H:i:s'),
            ]);

            // Threaded Email Dispatch
            $actionUrl = url('/my-portal/resignation');
            $statusLabel = $statusVal === 1 ? 'Approved / Accepted' : 'Rejected / Retained';
            $bodyHtml = "<p>Reporting Manager has responded to the resignation request for <strong>{$resignation->employee->first_name} {$resignation->employee->last_name}</strong>.</p>"
                . "<p><strong>Manager Decision:</strong> <span style='color: " . ($statusVal === 1 ? 'green' : 'red') . "; font-weight: bold;'>{$statusLabel}</span><br>"
                . "<strong>Confirmed Last Working Day (LWD):</strong> {$confirmedLwd}</p>"
                . "<p><strong>Manager Remarks:</strong><br>{$resignation->clean_manager_comment}</p>";

            $this->mailService->sendResignationNotification('manager_responded', $resignation, '', $bodyHtml, $actionUrl);
        }

        return $updated;
    }

    /**
     * Assign Department Clearance Officers (IT, Accounts, HR).
     */
    public function assignClearanceOfficers(EmployeeResignation $resignation, array $data): bool
    {
        $updateData = [];
        if (isset($data['it_person'])) {
            $updateData['it_person'] = (int) $data['it_person'];
        }
        if (isset($data['account_per'])) {
            $updateData['account_per'] = (int) $data['account_per'];
        }
        if (isset($data['hr_person'])) {
            $updateData['hr_person'] = (int) $data['hr_person'];
        }

        return $this->repository->update($resignation, $updateData);
    }

    /**
     * Send / Resend Clearance Notification Email to Assigned Officer.
     */
    public function sendClearanceNotificationEmail(EmployeeResignation $resignation, string $stage): bool
    {
        $actionUrl = url("/settings/clearance?resignation_id={$resignation->resignation_id}&stage={$stage}");
        $stageTitle = strtoupper($stage);

        $bodyHtml = "<p>You have been assigned to perform <strong>{$stageTitle} Department No-Dues Clearance</strong> for employee <strong>{$resignation->employee->first_name} {$resignation->employee->last_name}</strong> (Employee ID: {$resignation->employee->employee_id}).</p>"
            . "<p><strong>Confirmed Last Working Day (LWD):</strong> {$resignation->resignation_date}</p>"
            . "<p>Please click the button below to log in and update the No-Dues status with your remarks.</p>";

        return $this->mailService->sendResignationNotification('clearance_notify', $resignation, '', $bodyHtml, $actionUrl);
    }

    /**
     * Submit Exit Questionnaire & No-Dues Form Attachments.
     */
    public function submitExitForm(EmployeeResignation $resignation, array $data, ?UploadedFile $file = null): bool
    {
        $fileName = $resignation->exit_form;

        if ($file) {
            $uploaded = UploadHelper::upload('resignations', $file);
            if ($uploaded) {
                $fileName = $uploaded;
            }
        }

        $exitSummary = json_encode([
            'reason_details' => self::sanitizeContent($data['reason_details'] ?? '', false),
            'handover_summary' => self::sanitizeContent($data['handover_summary'] ?? '', false),
            'feedback_rating' => $data['feedback_rating'] ?? 5,
            'asset_laptop' => !empty($data['asset_laptop']),
            'asset_idcard' => !empty($data['asset_idcard']),
            'asset_sim' => !empty($data['asset_sim']),
            'asset_keys' => !empty($data['asset_keys']),
            'asset_files' => !empty($data['asset_files']),
        ]);

        return $this->repository->update($resignation, [
            'exit_form' => $fileName,
            'comments' => $exitSummary,
        ]);
    }

    /**
     * Update Department Stage Clearance Status & Comments.
     */
    public function updateDepartmentClearance(EmployeeResignation $resignation, string $stage, array $data, ?object $actor = null): bool
    {
        $statusVal = (int) ($data['status'] ?? 1);
        $comment = self::sanitizeContent($data['comment'] ?? '', false);
        $actorId = $actor ? ($actor->user_id ?? $actor->id ?? 1) : 1;

        $updateData = [];
        if ($stage === 'manager') {
            $updateData['manager_status'] = $statusVal;
            $updateData['manager_comment'] = $comment;
            $updateData['manager_person'] = $actorId;
        } elseif ($stage === 'it') {
            $updateData['it_status'] = $statusVal;
            $updateData['it_comment'] = $comment;
            $updateData['it_person'] = $actorId;
        } elseif ($stage === 'accounts') {
            $updateData['account_status'] = $statusVal;
            $updateData['account_comment'] = $comment;
            $updateData['account_per'] = $actorId;
        } elseif ($stage === 'hr') {
            $updateData['hr_status'] = $statusVal;
            $updateData['hr_comment'] = $comment;
            $updateData['hr_person'] = $actorId;

            if ($statusVal === 1) {
                $updateData['status'] = 'Completed';
            }
        }

        $updated = $this->repository->update($resignation, $updateData);

        if ($updated) {
            // Audit Log Entry
            EmployeeResignationLog::create([
                'resignation_id' => $resignation->resignation_id,
                'company_id' => $resignation->company_id,
                'employee_id' => $resignation->employee_id,
                'notice_date' => $resignation->notice_date,
                'resignation_date' => $resignation->resignation_date,
                'reason' => $resignation->reason,
                'added_by' => $resignation->added_by,
                'updated_by' => $actorId,
                'updated_date' => date('Y-m-d H:i:s'),
            ]);

            // Threaded Email Dispatch
            $actionUrl = url('/settings/clearance');
            $stageName = strtoupper($stage);
            $statusText = $statusVal === 1 ? 'Cleared / No Dues' : 'Pending Dues / Action Required';
            $bodyHtml = "<p>Departmental No-Dues Clearance update for employee <strong>{$resignation->employee->first_name} {$resignation->employee->last_name}</strong>.</p>"
                . "<p><strong>Department Stage:</strong> {$stageName}<br>"
                . "<strong>Status:</strong> <span style='font-weight: bold; color: " . ($statusVal === 1 ? 'green' : 'red') . ";'>{$statusText}</span></p>"
                . "<p><strong>Remarks / Comments:</strong><br>{$comment}</p>";

            $this->mailService->sendResignationNotification('clearance_updated', $resignation, '', $bodyHtml, $actionUrl);
        }

        return $updated;
    }

    /**
     * Generate Relieving / Experience Letter PDF.
     */
    public function generateRelievingPdf(EmployeeResignation $resignation, string $docType = 'relieving'): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('pdf.relieving_letter_pdf', [
            'resignation' => $resignation,
            'docType' => $docType,
        ]);

        $fileName = ($docType === 'experience' ? 'Experience_Certificate_' : 'Relieving_Letter_') 
            . ($resignation->employee->employee_id ?? $resignation->resignation_id) . '.pdf';

        return $pdf->download($fileName);
    }
}
