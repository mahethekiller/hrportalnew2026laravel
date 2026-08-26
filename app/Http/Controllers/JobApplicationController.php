<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\Department;
use App\Models\JobApplication;
use App\Services\RecruitmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function __construct(
        protected RecruitmentService $recruitmentService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $applications = $this->recruitmentService->getApplicationsPaginated($filters);
        $summary = $this->recruitmentService->getSummaryStats();
        $departments = Department::with('company')->orderBy('department_name')->get();
        $jobs = \App\Models\JobPost::orderBy('job_title')->get();

        return view('recruitment.index', compact('applications', 'summary', 'departments', 'jobs', 'filters'));
    }

    public function store(StoreJobApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['added_by'] = Auth::id() ?? 1;

        if ($request->hasFile('job_resume')) {
            $path = $request->file('job_resume')->store('resumes', 'public');
            $data['job_resume'] = $path;
        }

        $application = $this->recruitmentService->createApplication($data);

        return redirect()->route('recruitment-applications.index')
            ->with('success', 'Candidate application for "' . $application->candidate_name . '" submitted successfully.');
    }

    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $this->recruitmentService->updateApplicationStatus($application, $request->input('status'), $request->input('remarks'));

        return redirect()->route('recruitment-applications.index')
            ->with('success', 'Candidate application stage updated to "' . $request->input('status') . '".');
    }
}
