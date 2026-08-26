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
        $departments = \App\Models\Department::with('company')->orderBy('department_name')->get();
        $designations = \App\Models\Designation::with('company')->orderBy('designation_name')->get();
        
        $roles = collect();
        try {
            $spatieRoles = \Spatie\Permission\Models\Role::orderBy('name')->get();
            foreach ($spatieRoles as $r) {
                $roles->push((object)[
                    'id' => $r->id,
                    'name' => $r->name,
                ]);
            }
        } catch (\Throwable $e) {}

        try {
            $portalRoles = \App\Models\UserRole::orderBy('role_name')->get();
            foreach ($portalRoles as $pr) {
                $roleName = $pr->role_name ?? $pr->name ?? '';
                if (!empty($roleName) && !$roles->contains('name', $roleName)) {
                    $roles->push((object)[
                        'id' => $pr->id,
                        'name' => $roleName,
                    ]);
                }
            }
        } catch (\Throwable $e) {}

        try {
            $defaultTemplate = \App\Models\EmailTemplate::where('template_code', 'candidate_interview_scheduled')->first();
        } catch (\Throwable $e) {
            $defaultTemplate = null;
        }

        return view('recruitment.interviews', compact('interviews', 'applications', 'interviewers', 'defaultTemplate', 'filters', 'departments', 'designations', 'roles'));
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
            ->with('success', 'Candidate interview successfully scheduled and notification sent.');
    }

    public function update(Request $request, JobInterview $interview): RedirectResponse
    {
        $data = $request->all();
        $this->recruitmentService->updateInterview($interview, $data);

        return redirect()->route('recruitment-interviews.index')
            ->with('success', 'Interview details updated successfully.');
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

    public function convert(Request $request, JobInterview $interview): RedirectResponse
    {
        $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'employee_id' => ['nullable', 'string', 'max:100'],
            'contact_no' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'max:20'],
            'joining_date' => ['nullable', 'date'],
            'department_id' => ['nullable', 'integer'],
            'designation_id' => ['nullable', 'integer'],
            'role_id' => ['nullable'],
            'basic_salary' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $employee = $this->recruitmentService->convertToEmployee($interview, $request->all());
        if (!$employee) {
            return redirect()->route('recruitment-interviews.index')
                ->with('error', 'Unable to convert candidate to employee.');
        }

        return redirect()->route('employees.show', $employee->user_id)
            ->with('success', 'Candidate successfully converted to Active Employee (' . $employee->first_name . ' ' . $employee->last_name . ')!');
    }
}
