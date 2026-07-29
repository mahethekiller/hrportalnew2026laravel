<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\WfhClocking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WfhClockingRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = WfhClocking::with('employee')
            ->where(function ($q) {
                $q->where('show_status', '!=', 0)
                  ->where('show_status', '!=', '0')
                  ->orWhereNull('show_status');
            });

        if (!empty($filters['userid'])) {
            $query->where('userid', $filters['userid']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($eq) use ($search) {
                      $eq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?WfhClocking
    {
        return WfhClocking::find($id);
    }

    public function findActiveByUserId(int $userId): ?WfhClocking
    {
        return WfhClocking::where('userid', $userId)
            ->where(function ($q) {
                $q->where('show_status', '!=', 0)
                  ->where('show_status', '!=', '0')
                  ->orWhereNull('show_status');
            })
            ->where(function ($q) {
                $q->whereNull('clock_out')
                  ->orWhere('clock_out', '')
                  ->orWhere('clock_out', '0000-00-00 00:00:00');
            })
            ->orderBy('id', 'desc')
            ->first();
    }

    public function findTodayActiveByUserId(int $userId): ?WfhClocking
    {
        return $this->findActiveByUserId($userId);
    }

    public function clockIn(array $data): WfhClocking
    {
        $data['clock_in'] = $data['clock_in'] ?? date('Y-m-d H:i:s');
        $data['clock_out'] = $data['clock_out'] ?? '';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['show_status'] = 1;

        return WfhClocking::create($data);
    }

    public function clockOut(WfhClocking $clocking, ?string $outTime = null): bool
    {
        return $clocking->update([
            'clock_out' => $outTime ?? date('Y-m-d H:i:s'),
            'show_status' => 1,
        ]);
    }
}
