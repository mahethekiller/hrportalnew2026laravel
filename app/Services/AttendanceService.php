<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Attendance;
use App\Models\WfhClocking;
use App\Repositories\AttendanceRepository;
use App\Repositories\WfhClockingRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttendanceService
{
    public function __construct(
        protected AttendanceRepository $attendanceRepository,
        protected WfhClockingRepository $wfhRepository
    ) {}

    public function getOfficeAttendancePaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->attendanceRepository->getPaginated($filters, $perPage);
    }

    public function getWfhClockingsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->wfhRepository->getPaginated($filters, $perPage);
    }

    public function getActiveWfhClocking(int $userId): ?WfhClocking
    {
        return $this->wfhRepository->findTodayActiveByUserId($userId);
    }

    public function wfhClockIn(int $userId, ?string $description = null): WfhClocking
    {
        return $this->wfhRepository->clockIn([
            'userid' => $userId,
            'description' => $description ?? 'Work From Home (WFH)',
        ]);
    }

    public function wfhClockOut(?int $userId = null, ?int $clockingId = null): bool
    {
        if ($clockingId) {
            $clocking = $this->wfhRepository->findById($clockingId);
            if ($clocking) {
                return $this->wfhRepository->clockOut($clocking);
            }
        }

        if ($userId) {
            $active = $this->wfhRepository->findActiveByUserId($userId);
            if ($active) {
                return $this->wfhRepository->clockOut($active);
            }
        }

        return false;
    }

    public function recordOfficePunch(array $data): Attendance
    {
        $data['punch_date'] = $data['punch_date'] ?? date('Y-m-d');
        $data['show_status'] = $data['show_status'] ?? 'Present';

        return $this->attendanceRepository->create($data);
    }
}
