<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeApiController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    /**
     * Display a listing of employees.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'department_id', 'status']);
        $perPage = (int) $request->get('per_page', 15);
        $employees = $this->employeeService->getEmployees($filters, $perPage);

        return EmployeeResource::collection($employees);
    }

    /**
     * Store a newly created employee.
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());

        return (new EmployeeResource($employee))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified employee.
     */
    public function show(int $id): JsonResponse|EmployeeResource
    {
        $employee = $this->employeeService->getEmployeeById($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee record not found.'], 404);
        }

        return new EmployeeResource($employee);
    }

    /**
     * Update the specified employee.
     */
    public function update(UpdateEmployeeRequest $request, int $id): JsonResponse|EmployeeResource
    {
        $employee = $this->employeeService->getEmployeeById($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee record not found.'], 404);
        }

        $this->employeeService->updateEmployee($employee, $request->validated());

        return new EmployeeResource($employee->fresh(['user', 'department', 'designation', 'company']));
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(int $id): JsonResponse
    {
        $employee = $this->employeeService->getEmployeeById($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee record not found.'], 404);
        }

        $this->employeeService->deleteEmployee($employee);

        return response()->json(['message' => 'Employee record deleted successfully.'], 200);
    }
}
