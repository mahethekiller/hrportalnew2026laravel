<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\WfhClockInRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\WfhClockingResource;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AttendanceApiController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'date', 'status']);
        $perPage = (int) $request->get('per_page', 15);
        $attendances = $this->attendanceService->getOfficeAttendancePaginated($filters, $perPage);

        return AttendanceResource::collection($attendances);
    }

    public function wfhIndex(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['userid', 'search']);
        $perPage = (int) $request->get('per_page', 15);
        $clockings = $this->attendanceService->getWfhClockingsPaginated($filters, $perPage);

        return WfhClockingResource::collection($clockings);
    }

    public function wfhClockIn(WfhClockInRequest $request): JsonResponse
    {
        $userId = $request->input('userid') ?? (Auth::user()->employee->user_id ?? Auth::id());
        $clocking = $this->attendanceService->wfhClockIn((int) $userId, $request->input('description'));

        return response()->json([
            'message' => 'WFH Clock-In registered successfully.',
            'data' => new WfhClockingResource($clocking),
        ], 201);
    }

    public function wfhClockOut(Request $request): JsonResponse
    {
        $userId = $request->input('userid') ?? (Auth::user()->employee->user_id ?? Auth::id());
        $success = $this->attendanceService->wfhClockOut((int) $userId);

        if (!$success) {
            return response()->json(['message' => 'No active WFH Clock-In session found.'], 404);
        }

        return response()->json(['message' => 'WFH Clock-Out registered successfully.']);
    }

    public function storeOfficePunch(StoreAttendanceRequest $request): JsonResponse
    {
        $attendance = $this->attendanceService->recordOfficePunch($request->validated());

        return response()->json([
            'message' => 'Office attendance punch record added successfully.',
            'data' => new AttendanceResource($attendance),
        ], 201);
    }
}
