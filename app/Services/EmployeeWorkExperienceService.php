<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeeWorkExperience;
use App\Repositories\EmployeeWorkExperienceRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeWorkExperienceService
{
    public function __construct(
        protected EmployeeWorkExperienceRepository $repository
    ) {}

    public function getEmployeeExperiences(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeeWorkExperience
    {
        return $this->repository->findById($id);
    }

    public function createExperience(array $data): EmployeeWorkExperience
    {
        $data['interview_id'] = $data['interview_id'] ?? 0;
        $data['to_date'] = $data['to_date'] ?? date('Y-m-d');
        $data['description'] = $data['description'] ?? '';

        return $this->repository->create($data);
    }

    public function updateExperience(EmployeeWorkExperience $experience, array $data): bool
    {
        return $this->repository->update($experience, $data);
    }

    public function deleteExperience(EmployeeWorkExperience $experience): bool
    {
        return $this->repository->delete($experience);
    }
}
