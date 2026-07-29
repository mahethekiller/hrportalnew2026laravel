<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeContactRequest;
use App\Models\EmployeeContact;
use App\Services\EmployeeContactService;
use Illuminate\Http\RedirectResponse;

class EmployeeContactController extends Controller
{
    public function __construct(
        protected EmployeeContactService $contactService
    ) {}

    public function store(StoreEmployeeContactRequest $request): RedirectResponse
    {
        $contact = $this->contactService->createContact($request->validated());

        return redirect()->back()
            ->with('success', 'Emergency contact "' . $contact->contact_name . '" added successfully.');
    }

    public function destroy(EmployeeContact $contact): RedirectResponse
    {
        $this->contactService->deleteContact($contact);

        return redirect()->back()
            ->with('success', 'Emergency contact record removed successfully.');
    }
}
