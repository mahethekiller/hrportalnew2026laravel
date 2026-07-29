<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeeBankaccount;
use App\Repositories\EmployeeBankaccountRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeBankaccountService
{
    public function __construct(
        protected EmployeeBankaccountRepository $repository
    ) {}

    public function getEmployeeBankaccounts(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeeBankaccount
    {
        return $this->repository->findById($id);
    }

    public function createBankaccount(array $data): EmployeeBankaccount
    {
        $data['is_primary'] = $data['is_primary'] ?? 1;
        $data['bank_code'] = $data['bank_code'] ?? '';
        $data['bank_branch'] = $data['bank_branch'] ?? '';

        return $this->repository->create($data);
    }

    public function updateBankaccount(EmployeeBankaccount $bankAccount, array $data): bool
    {
        return $this->repository->update($bankAccount, $data);
    }

    public function deleteBankaccount(EmployeeBankaccount $bankAccount): bool
    {
        return $this->repository->delete($bankAccount);
    }
}
