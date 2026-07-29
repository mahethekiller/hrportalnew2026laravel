<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePerformanceAppraisalRequest;
use App\Models\PerformanceAppraisal;
use App\Services\EmployeeService;
use App\Services\PerformanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PerformanceAppraisalController extends Controller
{
    public function __construct(
        protected PerformanceService $performanceService,
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'period']);
        $appraisals = $this->performanceService->getAppraisalsPaginated($filters);
        $summary = $this->performanceService->getSummaryStats();
        $employees = $this->employeeService->getEmployees([], 200);

        return view('performance.index', compact('appraisals', 'summary', 'employees', 'filters'));
    }

    public function store(StorePerformanceAppraisalRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['added_by'] = Auth::user()->name ?? 'System Admin';

        $this->performanceService->createAppraisal($data);

        return redirect()->route('performance-appraisals.index')
            ->with('success', 'Performance Appraisal recorded successfully.');
    }

    public function show(PerformanceAppraisal $performance_appraisal): View
    {
        $performance_appraisal->load(['employee', 'manager', 'company']);

        return view('performance.show', [
            'appraisal' => $performance_appraisal,
        ]);
    }
}
