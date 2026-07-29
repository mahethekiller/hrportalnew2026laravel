<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\WfhClockInRequest;
use App\Services\AttendanceService;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'date', 'status']);
        $officeAttendances = $this->attendanceService->getOfficeAttendancePaginated($filters);
        $wfhClockings = $this->attendanceService->getWfhClockingsPaginated($filters);
        $employees = $this->employeeService->getEmployees([], 200);

        $currentUserId = Auth::user()->employee->user_id ?? Auth::id();
        $activeWfh = $this->attendanceService->getActiveWfhClocking($currentUserId);

        return view('attendance.index', compact('officeAttendances', 'wfhClockings', 'employees', 'activeWfh', 'filters'));
    }

    public function wfhClockIn(WfhClockInRequest $request): RedirectResponse
    {
        $userId = Auth::user()->employee->user_id ?? Auth::id();
        $this->attendanceService->wfhClockIn($userId, $request->input('description'));

        return redirect()->route('attendance.index')
            ->with('success', 'You have successfully Clocked In for Work From Home (WFH).');
    }

    public function wfhClockOut(Request $request): RedirectResponse
    {
        $clockingId = $request->input('clocking_id') ? (int) $request->input('clocking_id') : null;
        $userId = Auth::user()->employee->user_id ?? Auth::id();

        $success = $this->attendanceService->wfhClockOut($userId, $clockingId);

        if ($success) {
            return redirect()->route('attendance.index', ['tab' => 'wfh'])
                ->with('success', 'WFH session clocked out successfully.');
        }

        return redirect()->route('attendance.index', ['tab' => 'wfh'])
            ->with('error', 'No active WFH Clock-In session found.');
    }

    public function storeManualPunch(StoreAttendanceRequest $request): RedirectResponse
    {
        $this->attendanceService->recordOfficePunch($request->validated());

        return redirect()->route('attendance.index')
            ->with('success', 'Manual attendance record added successfully.');
    }
}
