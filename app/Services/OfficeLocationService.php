<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\OfficeLocation;
use App\Repositories\OfficeLocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OfficeLocationService
{
    public function __construct(
        protected OfficeLocationRepository $repository
    ) {}

    public function getAllLocations(): Collection
    {
        return $this->repository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?OfficeLocation
    {
        return $this->repository->findById($id);
    }

    public function createLocation(array $data): OfficeLocation
    {
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['location_head'] = $data['location_head'] ?? 0;
        $data['location_manager'] = $data['location_manager'] ?? 0;
        $data['email'] = $data['email'] ?? '';
        $data['phone'] = $data['phone'] ?? '';
        $data['fax'] = $data['fax'] ?? '';
        $data['address_1'] = $data['address_1'] ?? '';
        $data['address_2'] = $data['address_2'] ?? '';
        $data['city'] = $data['city'] ?? '';
        $data['state'] = $data['state'] ?? '';
        $data['zipcode'] = $data['zipcode'] ?? '';
        $data['country'] = $data['country'] ?? 1;
        $data['added_by'] = $data['added_by'] ?? 1;
        $data['status'] = $data['status'] ?? 1;

        return $this->repository->create($data);
    }

    public function updateLocation(OfficeLocation $location, array $data): bool
    {
        return $this->repository->update($location, $data);
    }

    public function deleteLocation(OfficeLocation $location): bool
    {
        return $this->repository->delete($location);
    }
}
