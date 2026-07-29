<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LeaveType;
use App\Repositories\LeaveTypeRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class LeaveTypeService
{
    public function __construct(
        protected LeaveTypeRepository $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?LeaveType
    {
        return $this->repository->findById($id);
    }

    public function createType(array $data): LeaveType
    {
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['status'] = $data['status'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->repository->create($data);
    }

    public function updateType(LeaveType $leaveType, array $data): bool
    {
        return $this->repository->update($leaveType, $data);
    }

    public function deleteType(LeaveType $leaveType): bool
    {
        return $this->repository->delete($leaveType);
    }
}
