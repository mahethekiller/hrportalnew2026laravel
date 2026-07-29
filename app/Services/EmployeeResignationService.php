<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeeResignation;
use App\Repositories\EmployeeResignationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeResignationService
{
    public function __construct(
        protected EmployeeResignationRepository $repository
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

    public function createResignation(array $data): EmployeeResignation
    {
        return $this->repository->create($data);
    }

    public function updateResignation(EmployeeResignation $resignation, array $data): bool
    {
        return $this->repository->update($resignation, $data);
    }

    public function deleteResignation(EmployeeResignation $resignation): bool
    {
        return $this->repository->delete($resignation);
    }
}
