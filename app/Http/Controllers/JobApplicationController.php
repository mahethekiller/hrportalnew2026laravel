<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Requests\UpdateJobApplicationRequest;
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
            $file = $request->file('job_resume');
            $candidateSlug = \Illuminate\Support\Str::slug($data['candidate_name'] ?? 'Candidate', '_');
            $extension = $file->getClientOriginalExtension();
            $filename = 'Resume_' . $candidateSlug . '_' . date('Ymd_His') . '.' . $extension;
            $path = $file->storeAs('resumes', $filename, 'public');
            $data['job_resume'] = $path;
        }

        $application = $this->recruitmentService->createApplication($data);

        return redirect()->route('recruitment-applications.index')
            ->with('success', 'Candidate application for "' . $application->candidate_name . '" submitted successfully.');
    }

    public function update(UpdateJobApplicationRequest $request, JobApplication $application): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('job_resume')) {
            $file = $request->file('job_resume');
            $candidateSlug = \Illuminate\Support\Str::slug($data['candidate_name'] ?? $application->candidate_name, '_');
            $extension = $file->getClientOriginalExtension();
            $filename = 'Resume_' . $candidateSlug . '_' . date('Ymd_His') . '.' . $extension;
            $path = $file->storeAs('resumes', $filename, 'public');
            $data['job_resume'] = $path;
        }

        $this->recruitmentService->updateApplication($application, $data);

        return redirect()->route('recruitment-applications.index')
            ->with('success', 'Candidate profile for "' . $application->candidate_name . '" updated successfully.');
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

    public function downloadResume(JobApplication $application)
    {
        if (empty($application->job_resume)) {
            abort(404, 'Resume file not found for this candidate.');
        }

        $path = $application->job_resume;

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
        }

        if (\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('local')->response($path);
        }

        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            return response()->file($fullPath);
        }

        $localFullPath = storage_path('app/' . $path);
        if (file_exists($localFullPath)) {
            return response()->file($localFullPath);
        }

        abort(404, 'Resume file does not exist on server storage.');
    }
}
