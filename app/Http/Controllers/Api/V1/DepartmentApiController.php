<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentApiController extends Controller
{
    public function __construct(
        protected DepartmentService $departmentService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $departments = $this->departmentService->getPaginated($request->all());

        return DepartmentResource::collection($departments);
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->departmentService->createDepartment($request->validated());

        return (new DepartmentResource($department))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $department = $this->departmentService->getById($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        return response()->json(['data' => new DepartmentResource($department)]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): DepartmentResource
    {
        $this->departmentService->updateDepartment($department, $request->validated());

        return new DepartmentResource($department->fresh(['company', 'employee']));
    }

    public function destroy(Department $department): JsonResponse
    {
        $this->departmentService->deleteDepartment($department);

        return response()->json(['message' => 'Department record deleted successfully.']);
    }
}
