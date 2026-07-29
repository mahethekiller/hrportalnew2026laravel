<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeBankaccountRequest;
use App\Models\EmployeeBankaccount;
use App\Services\EmployeeBankaccountService;
use Illuminate\Http\RedirectResponse;

class EmployeeBankaccountController extends Controller
{
    public function __construct(
        protected EmployeeBankaccountService $bankaccountService
    ) {}

    public function store(StoreEmployeeBankaccountRequest $request): RedirectResponse
    {
        $account = $this->bankaccountService->createBankaccount($request->validated());

        return redirect()->back()
            ->with('success', 'Bank account "' . $account->bank_name . '" added successfully.');
    }

    public function destroy(EmployeeBankaccount $bankaccount): RedirectResponse
    {
        $this->bankaccountService->deleteBankaccount($bankaccount);

        return redirect()->back()
            ->with('success', 'Bank account record removed successfully.');
    }
}
