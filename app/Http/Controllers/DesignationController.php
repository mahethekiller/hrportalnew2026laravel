<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Designation;
use App\Services\CompanyService;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignationController extends Controller
{
    public function __construct(
        protected DesignationService $designationService,
        protected DepartmentService $departmentService,
        protected CompanyService $companyService
    ) {}

    public function index(Request $request): View
    {
        $designations = $this->designationService->getPaginated($request->all());
        $departments = $this->departmentService->getAllDepartments();
        $companies = $this->companyService->getAllCompanies();

        return view('organization.designations.index', compact('designations', 'departments', 'companies'));
    }

    public function store(StoreDesignationRequest $request): RedirectResponse
    {
        $designation = $this->designationService->createDesignation($request->validated());

        return redirect()->route('designations.index')
            ->with('success', 'Designation "' . $designation->designation_name . '" created successfully.');
    }

    public function update(UpdateDesignationRequest $request, Designation $designation): RedirectResponse
    {
        $this->designationService->updateDesignation($designation, $request->validated());

        return redirect()->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation): RedirectResponse
    {
        $this->designationService->deleteDesignation($designation);

        return redirect()->route('designations.index')
            ->with('success', 'Designation removed successfully.');
    }
}
