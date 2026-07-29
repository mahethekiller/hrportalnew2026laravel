<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Services\CompanyService;
use App\Services\DepartmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function __construct(
        protected DepartmentService $departmentService,
        protected CompanyService $companyService
    ) {}

    public function index(Request $request): View
    {
        $departments = $this->departmentService->getPaginated($request->all());
        $companies = $this->companyService->getAllCompanies();

        return view('organization.departments.index', compact('departments', 'companies'));
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = $this->departmentService->createDepartment($request->validated());

        return redirect()->route('departments.index')
            ->with('success', 'Department "' . $department->department_name . '" created successfully.');
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->departmentService->updateDepartment($department, $request->validated());

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->departmentService->deleteDepartment($department);

        return redirect()->route('departments.index')
            ->with('success', 'Department removed successfully.');
    }
}
