<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttendanceRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Attendance::with('employee')
            ->where(function ($q) {
                $q->where('show_status', '!=', 0)
                  ->where('show_status', '!=', '0')
                  ->orWhereNull('show_status');
            });

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('card_no', 'like', "%{$search}%")
                  ->orWhere('badgenumber', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($eq) use ($search) {
                      $eq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('employee_id', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['date'])) {
            $query->where('punch_date', $filters['date']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function create(array $data): Attendance
    {
        $data['show_status'] = $data['show_status'] ?? 1;
        return Attendance::create($data);
    }

    public function findTodayByCard(string $cardNo, string $date): ?Attendance
    {
        return Attendance::where('card_no', $cardNo)
            ->where('punch_date', $date)
            ->first();
    }
}
