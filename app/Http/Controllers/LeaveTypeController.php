<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveTypeRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use App\Services\LeaveTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveTypeController extends Controller
{
    public function __construct(
        protected LeaveTypeService $leaveTypeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'company_id']);
        $leaveTypes = $this->leaveTypeService->getPaginated($filters);

        return view('leaves.types', compact('leaveTypes', 'filters'));
    }

    public function store(StoreLeaveTypeRequest $request): RedirectResponse
    {
        $leaveType = $this->leaveTypeService->createType($request->validated());

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type "' . $leaveType->type_name . '" created successfully.');
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType): RedirectResponse
    {
        $this->leaveTypeService->updateType($leaveType, $request->validated());

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type updated successfully.');
    }

    public function destroy(LeaveType $leaveType): RedirectResponse
    {
        $this->leaveTypeService->deleteType($leaveType);

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type removed successfully.');
    }
}
