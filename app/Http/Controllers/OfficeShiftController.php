<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfficeShiftRequest;
use App\Models\OfficeShift;
use App\Services\OfficeShiftService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficeShiftController extends Controller
{
    public function __construct(
        protected OfficeShiftService $shiftService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);
        $shifts = $this->shiftService->getPaginated($filters);

        return view('attendance.shifts', compact('shifts', 'filters'));
    }

    public function store(StoreOfficeShiftRequest $request): RedirectResponse
    {
        $shift = $this->shiftService->createShift($request->validated());

        return redirect()->route('office-shifts.index')
            ->with('success', 'Office shift "' . $shift->shift_name . '" created successfully.');
    }

    public function update(StoreOfficeShiftRequest $request, OfficeShift $shift): RedirectResponse
    {
        $this->shiftService->updateShift($shift, $request->validated());

        return redirect()->route('office-shifts.index')
            ->with('success', 'Office shift schedule updated successfully.');
    }

    public function destroy(OfficeShift $shift): RedirectResponse
    {
        $this->shiftService->deleteShift($shift);

        return redirect()->route('office-shifts.index')
            ->with('success', 'Office shift record deleted successfully.');
    }
}
