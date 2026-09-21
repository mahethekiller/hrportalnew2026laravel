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
        $year = (int)$request->input('year', date('Y'));
        
        $holidays = Holiday::with('company')
            ->whereYear('start_date', $year)
            ->orderBy('start_date', 'asc')
            ->get();

        $availableYears = Holiday::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(fn($y) => (int)$y)
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [(int)date('Y')];
        }

        // Stats calculation
        $today = now()->toDateString();
        $upcoming = $holidays->first(fn($h) => $h->start_date >= $today);
        if (!$upcoming && $year == (int)date('Y')) {
            $upcoming = Holiday::where('start_date', '>=', $today)->orderBy('start_date', 'asc')->first();
        }

        $stats = [
            'total' => $holidays->count(),
            'mandatory' => $holidays->filter(fn($h) => !str_contains(strtolower($h->event_name . ' ' . $h->description), 'restricted') && !str_contains(strtolower($h->event_name), '(rh)'))->count(),
            'restricted' => $holidays->filter(fn($h) => str_contains(strtolower($h->event_name . ' ' . $h->description), 'restricted') || str_contains(strtolower($h->event_name), '(rh)'))->count(),
            'upcoming' => $upcoming,
        ];

        $companies = \App\Models\Company::orderBy('name')->get();

        return view('holidays.index', compact('holidays', 'year', 'availableYears', 'stats', 'companies'));
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
            'company_id'  => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $data['event_name'] = \App\Traits\HasCleanContent::sanitizeContent($data['event_name'], false);
        $data['description'] = !empty($data['description']) ? \App\Traits\HasCleanContent::sanitizeContent($data['description'], false) : null;
        $data['company_id'] = $request->filled('company_id') ? (int)$request->company_id : (auth()->user()->company_id ?? 1);
        $data['is_publish'] = 1;

        Holiday::create($data);

        return redirect()->route('holidays.index', ['year' => date('Y', strtotime($data['start_date']))])
            ->with('success', 'Holiday created successfully.');
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
            'company_id'  => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $data['event_name'] = \App\Traits\HasCleanContent::sanitizeContent($data['event_name'], false);
        $data['description'] = !empty($data['description']) ? \App\Traits\HasCleanContent::sanitizeContent($data['description'], false) : null;
        if ($request->filled('company_id')) {
            $data['company_id'] = (int)$request->company_id;
        }

        $holiday->update($data);

        return redirect()->route('holidays.index', ['year' => date('Y', strtotime($data['start_date']))])
            ->with('success', 'Holiday updated successfully.');
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
