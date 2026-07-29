<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeQualificationRequest;
use App\Models\EmployeeQualification;
use App\Services\EmployeeQualificationService;
use Illuminate\Http\RedirectResponse;

class EmployeeQualificationController extends Controller
{
    public function __construct(
        protected EmployeeQualificationService $qualificationService
    ) {}

    public function store(StoreEmployeeQualificationRequest $request): RedirectResponse
    {
        $qualification = $this->qualificationService->createQualification($request->validated());

        return redirect()->back()
            ->with('success', 'Qualification "' . $qualification->name . '" added successfully.');
    }

    public function destroy(EmployeeQualification $qualification): RedirectResponse
    {
        $this->qualificationService->deleteQualification($qualification);

        return redirect()->back()
            ->with('success', 'Qualification record removed successfully.');
    }
}
