<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeeQualification;
use App\Repositories\EmployeeQualificationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeQualificationService
{
    public function __construct(
        protected EmployeeQualificationRepository $repository
    ) {}

    public function getEmployeeQualifications(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeeQualification
    {
        return $this->repository->findById($id);
    }

    public function createQualification(array $data): EmployeeQualification
    {
        $data['interview_id'] = $data['interview_id'] ?? 0;
        $data['education_level_id'] = $data['education_level_id'] ?? 1;
        $data['language_id'] = $data['language_id'] ?? 1;
        $data['skill_id'] = $data['skill_id'] ?? 1;
        $data['specialization'] = $data['specialization'] ?? '';
        $data['from_year'] = $data['from_year'] ?? '';
        $data['to_year'] = $data['to_year'] ?? '';
        $data['description'] = $data['description'] ?? '';

        return $this->repository->create($data);
    }

    public function updateQualification(EmployeeQualification $qualification, array $data): bool
    {
        return $this->repository->update($qualification, $data);
    }

    public function deleteQualification(EmployeeQualification $qualification): bool
    {
        return $this->repository->delete($qualification);
    }
}
