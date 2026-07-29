<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\OfficeShift;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OfficeShiftRepository
{
    public function getAll(): Collection
    {
        return OfficeShift::orderBy('office_shift_id', 'desc')->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = OfficeShift::query();

        if (!empty($filters['search'])) {
            $query->where('shift_name', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('office_shift_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?OfficeShift
    {
        return OfficeShift::find($id);
    }

    public function create(array $data): OfficeShift
    {
        return OfficeShift::create($data);
    }

    public function update(OfficeShift $shift, array $data): bool
    {
        return $shift->update($data);
    }

    public function delete(OfficeShift $shift): bool
    {
        return (bool) $shift->delete();
    }
}
