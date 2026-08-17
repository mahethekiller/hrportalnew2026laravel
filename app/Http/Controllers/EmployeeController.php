<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
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
        $filters = $request->only(['search', 'department_id', 'status']);
        $employees = $this->employeeService->getEmployees($filters, 12);
        $departments = Department::all();

        $totalActive = Employee::where('is_active', 1)->count();
        $totalDepartments = Department::count();
        $totalInactive = Employee::where('is_active', '!=', 1)->count();

        return view('employees.index', compact(
            'employees',
            'departments',
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

        return view('employees.create', compact('departments', 'designations', 'companies'));
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
            'employeeContacts',
            'employeeBankaccounts',
            'employeeQualifications',
            'employeeWorkExperiences',
            'employeeContracts.contractType',
            'employeeContracts.designation',
            'manager',
            'subManager',
        ]);

        return view('employees.show', compact('employee'));
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

        return view('employees.edit', compact('employee', 'departments', 'designations', 'companies'));
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

        $this->employeeService->updateEmployee($employee, $request->validated());

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
}
