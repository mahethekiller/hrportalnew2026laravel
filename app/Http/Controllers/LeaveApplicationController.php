<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveApplicationRequest;
use App\Http\Requests\UpdateLeaveApplicationStatusRequest;
use App\Models\LeaveApplication;
use App\Services\EmployeeService;
use App\Services\LeaveApplicationService;
use App\Services\LeaveTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveApplicationController extends Controller
{
    public function __construct(
        protected LeaveApplicationService $leaveService,
        protected LeaveTypeService $leaveTypeService,
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'leave_type_id', 'search', 'employee_id']);
        $leaveApplications = $this->leaveService->getPaginated($filters);
        $leaveTypes = $this->leaveTypeService->getAll();
        $employees = $this->employeeService->getEmployees([], 200);
        $counts = $this->leaveService->getCounts();

        return view('leaves.index', compact('leaveApplications', 'leaveTypes', 'employees', 'counts', 'filters'));
    }

    public function store(StoreLeaveApplicationRequest $request): RedirectResponse
    {
        $leave = $this->leaveService->applyForLeave($request->validated());

        return redirect()->route('leaves.index')
            ->with('success', 'Leave application submitted successfully.');
    }

    public function updateStatus(UpdateLeaveApplicationStatusRequest $request, LeaveApplication $leave): RedirectResponse
    {
        $this->leaveService->updateStatus($leave, (int) $request->input('status'), $request->input('remarks'));

        return redirect()->back()
            ->with('success', 'Leave application status updated to "' . $leave->fresh()->status_label . '".');
    }

    public function destroy(LeaveApplication $leave): RedirectResponse
    {
        $this->leaveService->deleteLeave($leave);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave record deleted successfully.');
    }
}
