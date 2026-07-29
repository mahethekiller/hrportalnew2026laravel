<?php

namespace App\Http\Controllers;

use App\Models\Employee;
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
}
