<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PerformanceAppraisal;
use App\Models\PerformanceIndicator;
use App\Repositories\PerformanceAppraisalRepository;
use App\Repositories\PerformanceIndicatorRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PerformanceService
{
    public function __construct(
        protected PerformanceAppraisalRepository $appraisalRepository,
        protected PerformanceIndicatorRepository $indicatorRepository
    ) {}

    public function getAppraisalsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->appraisalRepository->getPaginated($filters, $perPage);
    }

    public function getIndicatorsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->indicatorRepository->getPaginated($filters, $perPage);
    }

    public function getAppraisalById(int $id): ?PerformanceAppraisal
    {
        return $this->appraisalRepository->findById($id);
    }

    public function getSummaryStats(): array
    {
        return $this->appraisalRepository->getSummaryStats();
    }

    public function createAppraisal(array $data): PerformanceAppraisal
    {
        if (isset($data['teamwork']) && !isset($data['team_work'])) {
            $data['team_work'] = $data['teamwork'];
            unset($data['teamwork']);
        }

        return $this->appraisalRepository->create($data);
    }

    public function createIndicator(array $data): PerformanceIndicator
    {
        return $this->indicatorRepository->create($data);
    }
}
