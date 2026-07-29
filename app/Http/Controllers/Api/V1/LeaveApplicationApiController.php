<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveApplicationRequest;
use App\Http\Requests\UpdateLeaveApplicationStatusRequest;
use App\Http\Resources\LeaveApplicationResource;
use App\Models\LeaveApplication;
use App\Services\LeaveApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeaveApplicationApiController extends Controller
{
    public function __construct(
        protected LeaveApplicationService $leaveService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['employee_id', 'status', 'leave_type_id', 'search']);
        $perPage = (int) $request->get('per_page', 15);
        $leaves = $this->leaveService->getPaginated($filters, $perPage);

        return LeaveApplicationResource::collection($leaves);
    }

    public function store(StoreLeaveApplicationRequest $request): JsonResponse
    {
        $leave = $this->leaveService->applyForLeave($request->validated());

        return response()->json([
            'message' => 'Leave application submitted successfully.',
            'data' => new LeaveApplicationResource($leave),
        ], 201);
    }

    public function show(LeaveApplication $leave): JsonResponse
    {
        return response()->json([
            'data' => new LeaveApplicationResource($leave),
        ]);
    }

    public function updateStatus(UpdateLeaveApplicationStatusRequest $request, LeaveApplication $leave): JsonResponse
    {
        $this->leaveService->updateStatus($leave, (int) $request->input('status'), $request->input('remarks'));

        return response()->json([
            'message' => 'Leave application status updated successfully.',
            'data' => new LeaveApplicationResource($leave->fresh()),
        ]);
    }

    public function destroy(LeaveApplication $leave): JsonResponse
    {
        $this->leaveService->deleteLeave($leave);

        return response()->json([
            'message' => 'Leave application record deleted successfully.',
        ]);
    }
}
