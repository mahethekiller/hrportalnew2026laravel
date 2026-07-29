<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LeaveApplication;
use App\Repositories\LeaveApplicationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveApplicationService
{
    public function __construct(
        protected LeaveApplicationRepository $repository
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

        return $this->repository->create($data);
    }

    public function updateStatus(LeaveApplication $leaveApplication, int $status, ?string $remarks = null): bool
    {
        $updateData = [
            'status' => $status,
        ];

        if ($remarks !== null) {
            $updateData['remarks'] = $remarks;
        }

        return $this->repository->update($leaveApplication, $updateData);
    }

    public function deleteLeave(LeaveApplication $leaveApplication): bool
    {
        return $this->repository->delete($leaveApplication);
    }
}
