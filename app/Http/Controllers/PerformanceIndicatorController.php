<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePerformanceIndicatorRequest;
use App\Models\Designation;
use App\Services\PerformanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PerformanceIndicatorController extends Controller
{
    public function __construct(
        protected PerformanceService $performanceService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);
        $indicators = $this->performanceService->getIndicatorsPaginated($filters);
        $designations = Designation::orderBy('designation_name')->get();

        return view('performance.indicators', compact('indicators', 'designations', 'filters'));
    }

    public function store(StorePerformanceIndicatorRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['added_by'] = Auth::user()->name ?? 'System Admin';

        $this->performanceService->createIndicator($data);

        return redirect()->route('performance-indicators.index')
            ->with('success', 'Designation performance benchmark indicators created successfully.');
    }
}
