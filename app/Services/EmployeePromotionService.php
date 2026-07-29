<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeePromotion;
use App\Repositories\EmployeePromotionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeePromotionService
{
    public function __construct(
        protected EmployeePromotionRepository $repository
    ) {}

    public function getEmployeePromotions(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeePromotion
    {
        return $this->repository->findById($id);
    }

    public function createPromotion(array $data): EmployeePromotion
    {
        return $this->repository->create($data);
    }

    public function updatePromotion(EmployeePromotion $promotion, array $data): bool
    {
        return $this->repository->update($promotion, $data);
    }

    public function deletePromotion(EmployeePromotion $promotion): bool
    {
        return $this->repository->delete($promotion);
    }
}
