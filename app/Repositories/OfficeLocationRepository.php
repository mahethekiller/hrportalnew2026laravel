<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\OfficeLocation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OfficeLocationRepository
{
    public function getAll(): Collection
    {
        return OfficeLocation::with('company')->orderBy('location_id', 'desc')->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = OfficeLocation::with('company');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('location_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        }

        return $query->orderBy('location_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?OfficeLocation
    {
        return OfficeLocation::with('company')->find($id);
    }

    public function create(array $data): OfficeLocation
    {
        return OfficeLocation::create($data);
    }

    public function update(OfficeLocation $location, array $data): bool
    {
        return $location->update($data);
    }

    public function delete(OfficeLocation $location): bool
    {
        return (bool) $location->delete();
    }
}
