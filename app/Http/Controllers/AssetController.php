<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Services\AssetService;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function __construct(
        protected AssetService $assetService,
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $assets = $this->assetService->getAssetsPaginated($filters);
        $summary = $this->assetService->getSummaryStats();
        $employees = $this->employeeService->getEmployees([], 200);

        return view('assets.index', compact('assets', 'summary', 'employees', 'filters'));
    }

    public function store(StoreAssetRequest $request): RedirectResponse
    {
        $asset = $this->assetService->createAsset($request->validated());

        return redirect()->route('assets.index')
            ->with('success', 'Asset "' . $asset->name . '" (' . $asset->company_asset_code . ') registered successfully.');
    }
}
