<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HolidayController extends Controller
{
    /**
     * Display Company Holiday List & Calendar
     */
    public function index(Request $request): View
    {
        $year = $request->input('year', date('Y'));
        
        $holidays = Holiday::whereYear('start_date', $year)
            ->orderBy('start_date', 'asc')
            ->get();

        $availableYears = Holiday::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [(int)date('Y')];
        }

        return view('holidays.index', compact('holidays', 'year', 'availableYears'));
    }

    /**
     * Store a new holiday (HR / Admin)
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'event_name'  => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $data['company_id'] = auth()->user()->company_id ?? 1;
        $data['is_publish'] = 1;

        Holiday::create($data);

        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
    }

    /**
     * Update an existing holiday (HR / Admin)
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $holiday = Holiday::findOrFail($id);

        $data = $request->validate([
            'event_name'  => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $holiday->update($data);

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    /**
     * Delete a holiday (HR / Admin)
     */
    public function destroy(int $id): RedirectResponse
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
