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
        $data['company_name'] = $data['company_name'] ?? '';
        $data['from_date'] = $data['from_date'] ?? date('Y-m-d');
        $data['to_date'] = $data['to_date'] ?? date('Y-m-d');
        $data['post'] = $data['post'] ?? '';
        $data['description'] = $data['description'] ?? '';
        $data['created_at'] = $data['created_at'] ?? date('d-m-Y h:i:s');

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
