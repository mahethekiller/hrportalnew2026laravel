<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService
{
    public function __construct(
        protected DepartmentRepository $repository
    ) {}

    public function getAllDepartments(): Collection
    {
        return $this->repository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?Department
    {
        return $this->repository->findById($id);
    }

    public function createDepartment(array $data): Department
    {
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['location_id'] = $data['location_id'] ?? 1;
        $data['employee_id'] = $data['employee_id'] ?? 0;
        $data['added_by'] = $data['added_by'] ?? 1;
        $data['status'] = $data['status'] ?? 1;

        return $this->repository->create($data);
    }

    public function updateDepartment(Department $department, array $data): bool
    {
        return $this->repository->update($department, $data);
    }

    public function deleteDepartment(Department $department): bool
    {
        return $this->repository->delete($department);
    }
}
