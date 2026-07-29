<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeeContract;
use App\Repositories\EmployeeContractRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeContractService
{
    public function __construct(
        protected EmployeeContractRepository $repository
    ) {}

    public function getEmployeeContracts(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeeContract
    {
        return $this->repository->findById($id);
    }

    public function createContract(array $data): EmployeeContract
    {
        $data['contract_type_id'] = $data['contract_type_id'] ?? 1;
        $data['designation_id'] = $data['designation_id'] ?? 1;

        return $this->repository->create($data);
    }

    public function updateContract(EmployeeContract $contract, array $data): bool
    {
        return $this->repository->update($contract, $data);
    }

    public function deleteContract(EmployeeContract $contract): bool
    {
        return $this->repository->delete($contract);
    }
}
