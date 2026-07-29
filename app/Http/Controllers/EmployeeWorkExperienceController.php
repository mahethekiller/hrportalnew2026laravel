<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeWorkExperienceRequest;
use App\Models\EmployeeWorkExperience;
use App\Services\EmployeeWorkExperienceService;
use Illuminate\Http\RedirectResponse;

class EmployeeWorkExperienceController extends Controller
{
    public function __construct(
        protected EmployeeWorkExperienceService $experienceService
    ) {}

    public function store(StoreEmployeeWorkExperienceRequest $request): RedirectResponse
    {
        $experience = $this->experienceService->createExperience($request->validated());

        return redirect()->back()
            ->with('success', 'Work experience at "' . $experience->company_name . '" recorded successfully.');
    }

    public function destroy(EmployeeWorkExperience $experience): RedirectResponse
    {
        $this->experienceService->deleteExperience($experience);

        return redirect()->back()
            ->with('success', 'Work experience record removed successfully.');
    }
}
