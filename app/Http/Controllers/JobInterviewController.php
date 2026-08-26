<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobInterviewRequest;
use App\Services\EmployeeService;
use App\Services\RecruitmentService;
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

        return view('recruitment.interviews', compact('interviews', 'applications', 'interviewers', 'filters'));
    }

    public function store(StoreJobInterviewRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['added_by'] = Auth::user()->name ?? 'HR Recruiter';

        $this->recruitmentService->scheduleInterview($data);

        return redirect()->route('recruitment-interviews.index')
            ->with('success', 'Candidate interview scheduled successfully.');
    }

    public function updateStatus(Request $request, \App\Models\JobInterview $interview): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $this->recruitmentService->updateInterviewStatus($interview, $request->input('status'));

        return redirect()->route('recruitment-interviews.index')
            ->with('success', 'Interview status updated to "' . $request->input('status') . '".');
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
