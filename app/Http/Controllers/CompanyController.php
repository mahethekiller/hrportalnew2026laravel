<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    public function index(Request $request): View
    {
        $companies = $this->companyService->getPaginated($request->all());

        return view('organization.companies.index', compact('companies'));
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $company = $this->companyService->createCompany($request->validated());

        return redirect()->route('companies.index')
            ->with('success', 'Company entity "' . $company->name . '" registered successfully.');
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->companyService->updateCompany($company, $request->validated());

        return redirect()->route('companies.index')
            ->with('success', 'Company details updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->companyService->deleteCompany($company);

        return redirect()->route('companies.index')
            ->with('success', 'Company entity removed successfully.');
    }
}
