<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobPostRequest;
use App\Models\Department;
use App\Services\JobPostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobPostController extends Controller
{
    public function __construct(
        protected JobPostService $jobPostService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $jobs = $this->jobPostService->getJobPostsPaginated($filters);
        $summary = $this->jobPostService->getSummaryStats();
        $departments = Department::orderBy('department_name')->get();
        $jobCodes = \App\Models\JobCode::orderBy('job_code')->get();

        return view('recruitment.jobs', compact('jobs', 'summary', 'departments', 'jobCodes', 'filters'));
    }

    public function store(StoreJobPostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['added_by'] = Auth::user()->name ?? 'HR Manager';

        $job = $this->jobPostService->createJobPost($data);

        return redirect()->route('recruitment-job-posts.index')
            ->with('success', 'Job Opening Requisition "' . $job->job_title . '" (' . $job->job_code . ') published successfully.');
    }

    public function update(StoreJobPostRequest $request, \App\Models\JobPost $jobPost): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::user()->name ?? 'HR Manager';

        $this->jobPostService->updateJobPost($jobPost, $data);

        return redirect()->route('recruitment-job-posts.index')
            ->with('success', 'Job Opening Requisition "' . $jobPost->job_title . '" updated successfully.');
    }
}
