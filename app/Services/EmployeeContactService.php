<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeeContact;
use App\Repositories\EmployeeContactRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeContactService
{
    public function __construct(
        protected EmployeeContactRepository $repository
    ) {}

    public function getEmployeeContacts(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeeContact
    {
        return $this->repository->findById($id);
    }

    public function createContact(array $data): EmployeeContact
    {
        $data['is_primary'] = $data['is_primary'] ?? 0;
        $data['is_dependent'] = $data['is_dependent'] ?? 0;
        $data['country'] = $data['country'] ?? 1;
        $data['work_phone'] = $data['work_phone'] ?? '';
        $data['work_phone_extension'] = $data['work_phone_extension'] ?? '';
        $data['home_phone'] = $data['home_phone'] ?? '';
        $data['work_email'] = $data['work_email'] ?? '';
        $data['personal_email'] = $data['personal_email'] ?? '';
        $data['address_1'] = $data['address_1'] ?? '';
        $data['address_2'] = $data['address_2'] ?? '';
        $data['city'] = $data['city'] ?? '';
        $data['state'] = $data['state'] ?? '';
        $data['zipcode'] = $data['zipcode'] ?? '';
        $data['age'] = $data['age'] ?? '';
        $data['occupation'] = $data['occupation'] ?? '';
        $data['qualification'] = $data['qualification'] ?? '';

        return $this->repository->create($data);
    }

    public function updateContact(EmployeeContact $contact, array $data): bool
    {
        return $this->repository->update($contact, $data);
    }

    public function deleteContact(EmployeeContact $contact): bool
    {
        return $this->repository->delete($contact);
    }
}
