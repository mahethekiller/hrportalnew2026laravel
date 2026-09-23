<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDataUpdate;
use App\Models\EmployeeLeave;
use App\Models\PerformanceAppraisal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManagerPortalController extends Controller
{
    protected function getManagerId(): int
    {
        return (int) (auth()->user()?->employee_id ?? auth()->id() ?? 1);
    }

    /**
     * Manager Team Workstation Dashboard
     */
    public function index(): View
    {
        $managerId = $this->getManagerId();
        $teamMembers = Employee::with(['designation', 'department'])
            ->where(function($q) use ($managerId) {
                $q->where('manager_id', $managerId);
                if (auth()->user()?->department_id) {
                    $q->orWhere('department_id', auth()->user()->department_id);
                }
            })
            ->where('is_active', 1)
            ->get();

        $teamIds = array_unique(array_filter(array_merge(
            $teamMembers->pluck('user_id')->toArray(),
            $teamMembers->pluck('employee_id')->toArray()
        )));

        $pendingLeaves = EmployeeLeave::with('employee')->whereIn('employee_id', $teamIds)->where('status', 1)->latest()->take(6)->get();
        $recentAppraisals = PerformanceAppraisal::with('employee')->whereIn('employee_id', $teamIds)->latest()->take(5)->get();

        $stats = [
            'total_team' => $teamMembers->count(),
            'pending_leaves' => EmployeeLeave::whereIn('employee_id', $teamIds)->where('status', 1)->count(),
            'pending_profiles' => EmployeeDataUpdate::where('acceptance', 0)->count(),
            'pending_resignations' => \App\Models\EmployeeResignation::where(function($q) use ($managerId, $teamIds) {
                $q->where('manager_id', $managerId)->orWhereIn('employee_id', $teamIds);
            })->where('manager_status', 0)->count(),
        ];

        return view('manager_portal.index', compact('teamMembers', 'pendingLeaves', 'recentAppraisals', 'stats'));
    }

    /**
     * Team Attendance & Timesheet Logs
     */
    public function teamAttendance(Request $request): View
    {
        $managerId = $this->getManagerId();
        $teamMembers = Employee::with(['designation', 'department'])
            ->where(function($q) use ($managerId) {
                $q->where('manager_id', $managerId);
                if (auth()->user()?->department_id) {
                    $q->orWhere('department_id', auth()->user()->department_id);
                }
            })
            ->where('is_active', 1)
            ->get();

        $selectedDate = $request->input('date', date('Y-m-d'));

        // Query attendance records for selected date
        $attendances = \App\Models\EmpTodayAttendance::query()
            ->whereDate('check_in_datetime', $selectedDate)
            ->orWhereDate('punch_date', $selectedDate)
            ->get();

        $stats = [
            'total_team' => $teamMembers->count(),
            'present' => $attendances->count(),
            'absent' => max(0, $teamMembers->count() - $attendances->count()),
        ];

        return view('manager_portal.team_attendance', compact('teamMembers', 'attendances', 'selectedDate', 'stats'));
    }

    /**
     * Team Leave Approval Hub
     */
    public function teamLeaves(Request $request): View
    {
        $managerId = $this->getManagerId();
        $teamMembers = Employee::where('manager_id', $managerId);
        if (auth()->user()?->department_id) {
            $teamMembers->orWhere('department_id', auth()->user()->department_id);
        }
        $teamIds = array_unique(array_filter(array_merge(
            $teamMembers->pluck('user_id')->toArray(),
            $teamMembers->pluck('employee_id')->toArray()
        )));

        $baseQuery = EmployeeLeave::query()->whereIn('employee_id', $teamIds);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 1)->count(),
            'approved' => (clone $baseQuery)->where('status', 2)->count(),
            'rejected' => (clone $baseQuery)->where('status', 3)->count(),
        ];

        $query = (clone $baseQuery)->with(['employee.designation', 'employee.department']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function($q) use ($term) {
                $q->where('reason', 'like', "%{$term}%")
                  ->orWhereHas('employee', function($sq) use ($term) {
                      $sq->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('employee_id', 'like', "%{$term}%");
                  });
            });
        }

        $keyName = (new EmployeeLeave)->getKeyName();
        $leaves = $query->orderBy($keyName, 'desc')->paginate(15)->withQueryString();

        return view('manager_portal.team_leaves', compact('leaves', 'stats'));
    }

    /**
     * Approve or Reject Team Member Leave Application
     */
    public function updateLeaveStatus(Request $request, EmployeeLeave $leave): RedirectResponse
    {
        $request->validate([
            'status' => 'required|integer|in:2,3', // 2 = Approved, 3 = Rejected
            'remarks' => 'nullable|string|max:1000',
        ]);

        $leaveModel = \App\Models\LeaveApplication::find($leave->getKey());
        if ($leaveModel) {
            app(\App\Services\LeaveApplicationService::class)->updateStatus(
                $leaveModel,
                (int) $request->status,
                $request->remarks
            );
        } else {
            $leave->update([
                'status' => $request->status,
                'remarks' => $request->remarks ?? '',
            ]);
        }

        $statusLabel = $request->status == 2 ? 'Approved' : 'Rejected';
        return redirect()->back()->with('success', "Team leave application has been {$statusLabel}.");
    }

    /**
     * Team Performance Appraisals
     */
    public function teamPerformance(Request $request): View
    {
        $managerId = $this->getManagerId();
        $teamMembers = Employee::where('manager_id', $managerId);
        if (auth()->user()?->department_id) {
            $teamMembers->orWhere('department_id', auth()->user()->department_id);
        }
        $teamIds = array_unique(array_filter(array_merge(
            $teamMembers->pluck('user_id')->toArray(),
            $teamMembers->pluck('employee_id')->toArray()
        )));

        $keyName = (new PerformanceAppraisal)->getKeyName();
        $query = PerformanceAppraisal::with(['employee.designation', 'employee.department'])
            ->whereIn('employee_id', $teamIds);

        if ($request->filled('year')) {
            $query->where('appraisal_year', $request->year);
        }

        $appraisals = $query->orderBy($keyName, 'desc')->paginate(15)->withQueryString();

        $allTeamAppraisals = PerformanceAppraisal::whereIn('employee_id', $teamIds)->get();

        $stats = [
            'total_reviews' => $allTeamAppraisals->count(),
            'high_performers' => $allTeamAppraisals->filter(fn($appr) => (float)$appr->overall_rating >= 4.0)->count(),
            'team_size' => count($teamIds),
        ];

        return view('manager_portal.team_performance', compact('appraisals', 'stats'));
    }

    /**
     * List of pending profile update requests
     */
    public function pendingProfileUpdates(Request $request): View
    {
        if (!auth()->user()->can('edit.employees')) {
            abort(403, 'Unauthorized. This queue is restricted to HR Managers and Super Admins only.');
        }

        $baseQuery = EmployeeDataUpdate::query();

        $stats = [
            'pending' => (clone $baseQuery)->where('acceptance', 0)->count(),
            'approved' => (clone $baseQuery)->where('acceptance', 1)->count(),
            'rejected' => (clone $baseQuery)->where('acceptance', 2)->count(),
            'total' => (clone $baseQuery)->count(),
        ];

        $query = (clone $baseQuery)->with(['user.designation', 'user.department']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('acceptance', (int) $request->status);
        } else {
            // Default to pending
            $query->where('acceptance', 0);
        }

        $updates = $query->latest('id')->paginate(15)->withQueryString();
        return view('manager_portal.profile_approvals.index', compact('updates', 'stats'));
    }

    /**
     * Compare live profile vs proposed updates
     */
    public function viewProfileUpdate(EmployeeDataUpdate $update): View
    {
        if (!auth()->user()->can('edit.employees')) {
            abort(403, 'Unauthorized. This queue is restricted to HR Managers and Super Admins only.');
        }

        $employee = Employee::where('user_id', $update->user_id)->firstOrFail();
        return view('manager_portal.profile_approvals.show', compact('update', 'employee'));
    }

    /**
     * Process approvals / rejections and merge changes
     */
    public function approveProfileUpdate(Request $request, EmployeeDataUpdate $update): RedirectResponse
    {
        if (!auth()->user()->can('edit.employees')) {
            abort(403, 'Unauthorized. This queue is restricted to HR Managers and Super Admins only.');
        }

        $employee = Employee::where('user_id', $update->user_id)->firstOrFail();

        // Check if HR clicked "Reject"
        if ($request->input('action') === 'reject') {
            $update->update([
                'acceptance' => 2, // Rejected
                'acceptance_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'acceptance_date' => date('Y-m-d H:i:s'),
                'emp_updated_dets' => 1
            ]);
            return redirect()->route('manager-portal.profile_approvals.index')->with('success', 'Profile update request has been rejected.');
        }

        // Section mappings
        $sections = [
            'basic' => ['first_name', 'last_name', 'email_personal', 'contact_no', 'date_of_birth', 'gender', 'mother_tongue', 'age', 'place_of_birth', 'blood_group', 'marital_status', 'pan_number', 'aadhar_no', 'category', 'official_contact_no', 'vehicle_type', 'vehicle_no', 'paytm_no', 'skype_id'],
            'address' => ['address', 'address_com', 'city', 'state', 'pincode', 'city_temp', 'state_temp', 'pin_temp'],
            'father' => ['father_name', 'father_mobile', 'father_gender', 'father_occupation', 'father_age', 'father_qualification', 'father_address'],
            'mother' => ['mother_name', 'mother_mobile', 'mother_gender', 'mother_occupation', 'mother_age', 'mother_qualification', 'mother_address'],
            'brother' => ['brother_name', 'brother_mobile', 'brother_gender', 'brother_occupation', 'brother_age', 'brother_qualification', 'brother_address'],
            'sister' => ['sister_name', 'sister_mobile', 'sister_gender', 'sister_occupation', 'sister_age', 'sister_qualification', 'sister_address'],
            'spouse' => ['spouse_name', 'spouse_mobile', 'spouse_gender', 'spouse_occupation', 'spouse_age', 'spouse_qualification', 'spouse_address'],
            'c1' => ['child1_name', 'child1_mobile', 'child1_gender', 'child1_occupation', 'child1_age', 'child1_qualification', 'child1_address'],
            'c2' => ['child2_name', 'child2_mobile', 'child2_gender', 'child2_occupation', 'child2_age', 'child2_qualification', 'child2_address'],
            'emer' => ['emergency_contact_relation', 'emergency_contact_name', 'emergency_contact_gender', 'emergency_contact_mobile', 'emergency_contact_age', 'emergency_contact_occupation', 'emergency_contact_qualification', 'emergency_contact_address'],
            'social' => ['facebook_link', 'twitter_link', 'blogger_link', 'linkdedin_link', 'google_plus_link', 'instagram_link', 'pinterest_link', 'youtube_link'],
            'benefits' => ['health_ins_opted', 'pf_opted'],
        ];

        $approvedSections = $request->input('sections', []);

        $acceptanceFlags = [
            'basic' => 'acceptance_basic',
            'father' => 'acceptance_father',
            'mother' => 'acceptance_mother',
            'brother' => 'acceptance_bro',
            'sister' => 'acceptance_sis',
            'c1' => 'acceptance_c1',
            'c2' => 'acceptance_c2',
            'emer' => 'acceptance_emer',
            'social' => 'acceptance_social',
            'spouse' => 'acceptance_spouse',
        ];

        foreach ($sections as $secName => $fields) {
            $isApproved = in_array($secName, $approvedSections);
            
            // Set update record section flag if column exists
            if (isset($acceptanceFlags[$secName])) {
                $flagColumn = $acceptanceFlags[$secName];
                $update->{$flagColumn} = $isApproved ? 1 : -1;
            }

            if ($isApproved) {
                // Merge approved fields to primary Employee record
                foreach ($fields as $field) {
                    if (isset($update->{$field})) {
                        $employee->{$field} = $update->{$field};
                    }
                }
            }
        }

        // Save live Employee record
        $employee->save();

        // Sync core auth details to User record if linked
        if ($employee->user) {
            $userRecord = $employee->user;
            $userRecord->name = trim($employee->first_name . ' ' . $employee->last_name);
            $userRecord->email = $employee->email;
            $userRecord->save();
        }

        // Update staging record
        $update->acceptance = 1; // Approved
        $update->emp_updated_dets = 1;
        $update->acceptance_name = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $update->acceptance_date = date('Y-m-d H:i:s');
        $update->save();

        return redirect()->route('manager-portal.profile_approvals.index')->with('success', 'Selected profile sections successfully approved and merged.');
    }
}
