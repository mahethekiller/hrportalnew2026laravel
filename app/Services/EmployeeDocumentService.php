<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmployeeDocument;
use App\Repositories\EmployeeDocumentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class EmployeeDocumentService
{
    public function __construct(
        protected EmployeeDocumentRepository $repository
    ) {}

    public function getEmployeeDocuments(int $employeeId): Collection
    {
        return $this->repository->getByEmployeeId($employeeId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function getById(int $id): ?EmployeeDocument
    {
        return $this->repository->findById($id);
    }

    public function createDocument(array $data): EmployeeDocument
    {
        if (isset($data['document_file']) && $data['document_file'] instanceof UploadedFile) {
            $file = $data['document_file'];
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/documents'), $filename);
            $data['document_file'] = $filename;
        }

        $data['document_id'] = $data['document_id'] ?? 1;
        $data['document_type_id'] = $data['document_type_id'] ?? 1;
        $data['is_alert'] = $data['is_alert'] ?? 0;
        $data['description'] = $data['description'] ?? '';
        $data['notification_email'] = $data['notification_email'] ?? '';
        $data['document_file'] = $data['document_file'] ?? '';
        $data['date_of_expiry'] = $data['date_of_expiry'] ?? date('Y-m-d');

        return $this->repository->create($data);
    }

    public function updateDocument(EmployeeDocument $document, array $data): bool
    {
        if (isset($data['document_file']) && $data['document_file'] instanceof UploadedFile) {
            $file = $data['document_file'];
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/documents'), $filename);
            $data['document_file'] = $filename;
        }

        return $this->repository->update($document, $data);
    }

    public function deleteDocument(EmployeeDocument $document): bool
    {
        return $this->repository->delete($document);
    }
}
