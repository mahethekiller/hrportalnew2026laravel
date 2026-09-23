<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\UserRole;

use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    /**
     * Display a paginated listing of employees.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'department_id', 'company_id', 'status']);
        $employees = $this->employeeService->getEmployees($filters, 12);
        $departments = Department::all();
        $companies = \App\Models\Company::all();

        $totalActive = Employee::where('is_active', 1)->count();
        $totalDepartments = Department::count();
        $totalInactive = Employee::where('is_active', '!=', 1)->count();

        return view('employees.index', compact(
            'employees',
            'departments',
            'companies',
            'filters',
            'totalActive',
            'totalDepartments',
            'totalInactive'
        ));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create(): View
    {
        $departments = Department::all();
        $designations = Designation::all();
        $companies = Company::all();
        $managers = Employee::where('is_active', 1)->orderBy('first_name', 'asc')->get();

        return view('employees.create', compact('departments', 'designations', 'companies', 'managers'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee "' . $employee->first_name . ' ' . $employee->last_name . '" created successfully.');
    }

    /**
     * Display the specified employee profile.
     */
    public function show(Employee $employee): View
    {
        $employee->load([
            'user',
            'department',
            'designation',
            'company',
            'officeShift',
            'documents',
            'employeeDocuments.documentType.company',
            'employeeContacts',

            'employeeBankaccounts',
            'employeeQualifications',
            'employeeWorkExperiences',
            'employeeContracts.contractType',
            'employeeContracts.designation',
            'manager',
            'subManager',
        ]);

        $empCompanyId = (int) ($employee->company_id ?? 0);
        $documentTypes = DocumentType::with('company')
            ->orderByRaw("CASE WHEN company_id = {$empCompanyId} THEN 0 ELSE 1 END")
            ->orderBy('document_type', 'asc')
            ->get();

        return view('employees.show', compact('employee', 'documentTypes'));
    }



    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee): View
    {
        $isSelf = auth()->id() === (int)$employee->user_id;

        if (!$isSelf && !auth()->user()->can('edit.employees')) {
            abort(403, 'You are not authorized to edit other employee profiles.');
        }

        $departments = Department::all();
        $designations = Designation::all();
        $companies = Company::all();
        $managers = Employee::where('is_active', 1)->orderBy('first_name', 'asc')->get();
        $roles = UserRole::orderBy('id', 'asc')->get();
        $currentUser = auth()->user();
        $canChangeRole = $currentUser->can('edit.employees') && (!$isSelf || $currentUser->hasRole(['Super Admin', 'super-admin']));

        return view('employees.edit', compact('employee', 'departments', 'designations', 'companies', 'managers', 'roles', 'canChangeRole'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $isSelf = auth()->id() === (int)$employee->user_id;

        if (!$isSelf && !auth()->user()->can('edit.employees')) {
            abort(403, 'You are not authorized to update other employee profiles.');
        }

        $data = $request->validated();
        $currentUser = auth()->user();
        $canChangeRole = $currentUser->can('edit.employees') && (!$isSelf || $currentUser->hasRole(['Super Admin', 'super-admin']));

        if (!$canChangeRole) {
            unset($data['user_role_id'], $data['role_id'], $data['role']);
        }

        $this->employeeService->updateEmployee($employee, $data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $this->employeeService->deleteEmployee($employee);

        return redirect()->route('employees.index')
            ->with('success', 'Employee record deleted successfully.');
    }

    /**
     * Upload or update employee KRA document.
     */
    public function uploadKra(\Illuminate\Http\Request $request, Employee $employee): RedirectResponse
    {
        $isSelf = auth()->id() === (int)$employee->user_id;
        if (!$isSelf && !auth()->user()->can('edit.employees')) {
            abort(403, 'You are not authorized to upload KRA for other employees.');
        }

        $request->validate([
            'kra_doc' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,png,webp', 'max:10240'],
        ]);

        $this->employeeService->uploadKraDocument($employee, $request->file('kra_doc'));

        return redirect()->back()
            ->with('success', 'Key Responsibility Areas (KRA) document uploaded successfully.');
    }

    /**
     * Upload or update employee KPI document.
     */
    public function uploadKpi(\Illuminate\Http\Request $request, Employee $employee): RedirectResponse
    {
        $isSelf = auth()->id() === (int)$employee->user_id;
        if (!$isSelf && !auth()->user()->can('edit.employees')) {
            abort(403, 'You are not authorized to upload KPI for other employees.');
        }

        $request->validate([
            'kpi_doc' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,png,webp', 'max:10240'],
        ]);

        $this->employeeService->uploadKpiDocument($employee, $request->file('kpi_doc'));

        return redirect()->back()
            ->with('success', 'Performance Indicators (KPI) document uploaded successfully.');
    }
}
