<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobInterviewRequest;
use App\Services\EmployeeService;
use App\Services\RecruitmentService;
use App\Models\JobInterview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobInterviewController extends Controller
{
    public function __construct(
        protected RecruitmentService $recruitmentService,
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);
        $interviews = $this->recruitmentService->getInterviewsPaginated($filters);
        $applications = $this->recruitmentService->getApplicationsPaginated([], 200);
        $interviewers = $this->employeeService->getActiveEmployees();
        try {
            $defaultTemplate = \App\Models\EmailTemplate::where('template_code', 'candidate_interview_scheduled')->first();
        } catch (\Throwable $e) {
            $defaultTemplate = null;
        }

        return view('recruitment.interviews', compact('interviews', 'applications', 'interviewers', 'defaultTemplate', 'filters'));
    }

    public function store(StoreJobInterviewRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['added_by'] = Auth::user()->name ?? 'HR Recruiter';

        try {
            $this->recruitmentService->scheduleInterview($data);
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('recruitment-interviews.index')
            ->with('success', 'Candidate interview scheduled successfully.');
    }

    public function update(Request $request, JobInterview $interview): RedirectResponse
    {
        $request->validate([
            'interview_mode' => ['required', 'string', 'max:100'],
            'interview_date' => ['required', 'date'],
            'interview_time' => ['required', 'string', 'max:50'],
            'interview_place' => ['nullable', 'string', 'max:255'],
            'next_round_date' => ['nullable', 'date'],
            'offered_ctc' => ['nullable', 'numeric', 'min:0'],
            'interviewers_id' => ['nullable'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'application_remarks' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $this->recruitmentService->updateInterview($interview, $request->all());

        return redirect()->route('recruitment-interviews.index')
            ->with('success', 'Candidate interview details updated successfully.');
    }

    public function updateStatus(Request $request, \App\Models\JobInterview $interview): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'next_round_date' => ['nullable', 'date'],
            'interview_time' => ['nullable', 'string', 'max:50'],
            'interviewers_id' => ['nullable'],
            'cc_employees_id' => ['nullable'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'send_email_notification' => ['nullable'],
            'notify_candidate' => ['nullable'],
            'notify_interviewers' => ['nullable'],
            'custom_email_subject' => ['nullable', 'string', 'max:255'],
            'custom_email_body' => ['nullable', 'string'],
        ]);

        $this->recruitmentService->updateInterviewStatus($interview, $request->input('status'), $request->all());

        return redirect()->route('recruitment-interviews.index')
            ->with('success', 'Interview status updated to "' . ucfirst($request->input('status')) . '".');
    }

    public function convert(\App\Models\JobInterview $interview): RedirectResponse
    {
        $employee = $this->recruitmentService->convertToEmployee($interview);
        if (!$employee) {
            return redirect()->route('recruitment-interviews.index')
                ->with('error', 'Unable to convert candidate to employee.');
        }

        return redirect()->route('employees.show', $employee->user_id)
            ->with('success', 'Candidate successfully converted to employee (' . $employee->first_name . ' ' . $employee->last_name . ')!');
    }
}
