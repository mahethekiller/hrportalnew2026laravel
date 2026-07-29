<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeDocumentRequest;
use App\Models\EmployeeDocument;
use App\Services\EmployeeDocumentService;
use Illuminate\Http\RedirectResponse;

class EmployeeDocumentController extends Controller
{
    public function __construct(
        protected EmployeeDocumentService $documentService
    ) {}

    public function store(StoreEmployeeDocumentRequest $request): RedirectResponse
    {
        $document = $this->documentService->createDocument($request->validated());

        return redirect()->back()
            ->with('success', 'Document "' . $document->title . '" uploaded successfully.');
    }

    public function destroy(EmployeeDocument $document): RedirectResponse
    {
        $this->documentService->deleteDocument($document);

        return redirect()->back()
            ->with('success', 'Document removed successfully.');
    }
}
