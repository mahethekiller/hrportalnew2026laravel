<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Designation;
use App\Repositories\DesignationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DesignationService
{
    public function __construct(
        protected DesignationRepository $repository
    ) {}

    public function getAllDesignations(): Collection
    {
        return $this->repository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?Designation
    {
        return $this->repository->findById($id);
    }

    public function createDesignation(array $data): Designation
    {
        $data['top_designation_id'] = $data['top_designation_id'] ?? 0;
        $data['department_id'] = $data['department_id'] ?? 1;
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['added_by'] = $data['added_by'] ?? 1;
        $data['status'] = $data['status'] ?? 1;

        return $this->repository->create($data);
    }

    public function updateDesignation(Designation $designation, array $data): bool
    {
        return $this->repository->update($designation, $data);
    }

    public function deleteDesignation(Designation $designation): bool
    {
        return $this->repository->delete($designation);
    }
}
