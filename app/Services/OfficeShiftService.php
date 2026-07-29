<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\OfficeShift;
use App\Repositories\OfficeShiftRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OfficeShiftService
{
    public function __construct(
        protected OfficeShiftRepository $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?OfficeShift
    {
        return $this->repository->findById($id);
    }

    public function createShift(array $data): OfficeShift
    {
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->repository->create($data);
    }

    public function updateShift(OfficeShift $shift, array $data): bool
    {
        return $this->repository->update($shift, $data);
    }

    public function deleteShift(OfficeShift $shift): bool
    {
        return $this->repository->delete($shift);
    }
}
