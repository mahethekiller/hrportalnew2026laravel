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
        $teamMembers = Employee::where('manager_id', $managerId)->orWhere('department_id', auth()->user()?->department_id)->get();
        $teamIds = $teamMembers->pluck('employee_id')->toArray();

        $pendingLeaves = EmployeeLeave::whereIn('employee_id', $teamIds)->where('status', 1)->get();
        $recentAppraisals = PerformanceAppraisal::whereIn('employee_id', $teamIds)->latest()->take(5)->get();

        return view('manager_portal.index', compact('teamMembers', 'pendingLeaves', 'recentAppraisals'));
    }

    /**
     * Team Attendance & Timesheet Logs
     */
    public function teamAttendance(): View
    {
        $managerId = $this->getManagerId();
        $teamMembers = Employee::where('manager_id', $managerId)->orWhere('department_id', auth()->user()?->department_id)->get();

        return view('manager_portal.team_attendance', compact('teamMembers'));
    }

    /**
     * Team Leave Approval Hub
     */
    public function teamLeaves(): View
    {
        $managerId = $this->getManagerId();
        $teamIds = Employee::where('manager_id', $managerId)->orWhere('department_id', auth()->user()?->department_id)->pluck('employee_id')->toArray();

        $keyName = (new EmployeeLeave)->getKeyName();
        $leaves = EmployeeLeave::with('employee')->whereIn('employee_id', $teamIds)->orderBy($keyName, 'desc')->paginate(15);

        return view('manager_portal.team_leaves', compact('leaves'));
    }

    /**
     * Approve or Reject Team Member Leave Application
     */
    public function updateLeaveStatus(Request $request, EmployeeLeave $leave): RedirectResponse
    {
        $request->validate([
            'status' => 'required|integer|in:2,3', // 2 = Approved, 3 = Rejected
            'remarks' => 'nullable|string',
        ]);

        $leave->update([
            'status' => $request->status,
            'remarks' => $request->remarks ?? '',
        ]);

        $statusLabel = $request->status == 2 ? 'Approved' : 'Rejected';
        return redirect()->back()->with('success', "Team leave application has been {$statusLabel}.");
    }

    /**
     * Team Performance Appraisals
     */
    public function teamPerformance(): View
    {
        $managerId = $this->getManagerId();
        $teamIds = Employee::where('manager_id', $managerId)->orWhere('department_id', auth()->user()?->department_id)->pluck('employee_id')->toArray();

        $keyName = (new PerformanceAppraisal)->getKeyName();
        $appraisals = PerformanceAppraisal::with('employee')->whereIn('employee_id', $teamIds)->orderBy($keyName, 'desc')->get();

        return view('manager_portal.team_performance', compact('appraisals'));
    }

    /**
     * List of pending profile update requests
     */
    public function pendingProfileUpdates(): View
    {
        if (!auth()->user()->can('edit.employees')) {
            abort(403, 'Unauthorized. This queue is restricted to HR Managers and Super Admins only.');
        }

        $updates = EmployeeDataUpdate::with('user')->where('acceptance', 0)->latest('id')->paginate(15);
        return view('manager_portal.profile_approvals.index', compact('updates'));
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
