<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmployeeDocument;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeDocumentRepository
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeDocument::with(['documentType', 'document'])
            ->where('employee_id', $employeeId)
            ->orderBy('document_id', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EmployeeDocument::with(['employee', 'documentType']);

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->orderBy('document_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?EmployeeDocument
    {
        return EmployeeDocument::with(['employee', 'documentType'])->find($id);
    }

    public function create(array $data): EmployeeDocument
    {
        return EmployeeDocument::create($data);
    }

    public function update(EmployeeDocument $document, array $data): bool
    {
        return $document->update($data);
    }

    public function delete(EmployeeDocument $document): bool
    {
        return (bool) $document->delete();
    }
}
