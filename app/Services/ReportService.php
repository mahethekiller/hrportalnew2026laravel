<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ReportRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportService
{
    public function __construct(
        protected ReportRepository $reportRepository
    ) {}

    public function getExecutiveOverviewStats(): array
    {
        return $this->reportRepository->getExecutiveOverviewStats();
    }

    public function getEmployeeReports(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->reportRepository->getEmployeeReports($filters, $perPage);
    }

    public function getPayrollReports(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->reportRepository->getPayrollReports($filters, $perPage);
    }

    public function getAuditLogs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->reportRepository->getAuditLogs($filters, $perPage);
    }
}
