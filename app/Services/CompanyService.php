<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class CompanyService
{
    public function __construct(
        protected CompanyRepository $repository
    ) {}

    public function getAllCompanies(): Collection
    {
        return $this->repository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?Company
    {
        return $this->repository->findById($id);
    }

    public function createCompany(array $data): Company
    {
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $file = $data['logo'];
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logo'), $filename);
            $data['logo'] = $filename;
        }

        $data['type_id'] = $data['type_id'] ?? 1;
        $data['trading_name'] = $data['trading_name'] ?? $data['name'];
        $data['username'] = $data['username'] ?? 'company_' . time();
        $data['password'] = $data['password'] ?? 'secret';
        $data['registration_no'] = $data['registration_no'] ?? 'REG-' . rand(1000, 9999);
        $data['government_tax'] = $data['government_tax'] ?? 'TAX-001';
        $data['logo'] = $data['logo'] ?? 'logo.png';
        $data['contact_number'] = $data['contact_number'] ?? '0000000000';
        $data['website_url'] = $data['website_url'] ?? 'https://antigravity.io';
        $data['address_1'] = $data['address_1'] ?? 'Headquarters';
        $data['address_2'] = $data['address_2'] ?? '';
        $data['city'] = $data['city'] ?? 'City';
        $data['state'] = $data['state'] ?? 'State';
        $data['zipcode'] = $data['zipcode'] ?? '00000';
        $data['country'] = $data['country'] ?? 1;
        $data['is_active'] = $data['is_active'] ?? 1;
        $data['added_by'] = $data['added_by'] ?? 1;

        return $this->repository->create($data);
    }

    public function updateCompany(Company $company, array $data): bool
    {
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $file = $data['logo'];
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logo'), $filename);
            $data['logo'] = $filename;
        }

        return $this->repository->update($company, $data);
    }

    public function deleteCompany(Company $company): bool
    {
        return $this->repository->delete($company);
    }
}
