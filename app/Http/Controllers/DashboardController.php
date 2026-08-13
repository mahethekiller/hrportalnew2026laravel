<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDataUpdate;
use App\Models\LeaveApplication;
use App\Models\Holiday;
use App\Models\WfhClocking;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OfficeLocation;
use App\Models\SystemSetting;
use App\Models\EmployeeLeave;
use App\Models\Announcement;
use App\Models\Meeting;
use App\Models\PayrollPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        // Common queries: Holidays, Birthdays, Anniversaries
        $upcomingHolidays = Holiday::where('start_date', '>=', today()->format('Y-m-d'))
            ->orderBy('start_date')
            ->take(5)
            ->get();

        $allEmployees = Employee::where('is_active', 1)->get();

        $upcomingBirthdays = $allEmployees->filter(function ($emp) {
            if (empty($emp->date_of_birth)) return false;
            $dob = Carbon::parse($emp->date_of_birth);
            $birthdayThisYear = $dob->copy()->year((int) date('Y'));
            $birthdayNextYear = $dob->copy()->year((int) date('Y') + 1);

            $diff1 = today()->diffInDays($birthdayThisYear, false);
            $diff2 = today()->diffInDays($birthdayNextYear, false);

            return ($diff1 >= 0 && $diff1 <= 30) || ($diff2 >= 0 && $diff2 <= 30);
        })->sortBy(function ($emp) {
            $dob = Carbon::parse($emp->date_of_birth);
            $birthdayThisYear = $dob->copy()->year((int) date('Y'));
            if ($birthdayThisYear->isPast() && !$birthdayThisYear->isToday()) {
                $birthdayThisYear->addYear();
            }
            return $birthdayThisYear;
        })->take(5);

        $upcomingAnniversaries = $allEmployees->filter(function ($emp) {
            if (empty($emp->date_of_joining)) return false;
            $doj = Carbon::parse($emp->date_of_joining);
            $anniversaryThisYear = $doj->copy()->year((int) date('Y'));
            $anniversaryNextYear = $doj->copy()->year((int) date('Y') + 1);

            $diff1 = today()->diffInDays($anniversaryThisYear, false);
            $diff2 = today()->diffInDays($anniversaryNextYear, false);

            return ($diff1 >= 0 && $diff1 <= 30) || ($diff2 >= 0 && $diff2 <= 30);
        })->sortBy(function ($emp) {
            $doj = Carbon::parse($emp->date_of_joining);
            $anniversaryThisYear = $doj->copy()->year((int) date('Y'));
            if ($anniversaryThisYear->isPast() && !$anniversaryThisYear->isToday()) {
                $anniversaryThisYear->addYear();
            }
            return $anniversaryThisYear;
        })->take(5);

        // Interactive clock-in stats
        $activeWfh = null;
        $todayOfficePunch = null;
        if ($employee) {
            $activeWfh = WfhClocking::where('userid', $employee->user_id)
                ->whereNull('clock_out')
                ->first();
            $todayOfficePunch = Attendance::where('card_no', $employee->card_no)
                ->whereDate('punch_date', today()->format('Y-m-d'))
                ->first();
        }

        // Determine role and dispatch
        if ($user->can('edit.employees') || $user->hasAnyRole(['HR Manager', 'Super Admin', 'super-admin']) || $user->user_role_id == 1) {
            // Check if Super Admin or legacy admin
            $isSuperAdmin = ($user->user_role_id == 1 || $user->hasRole('Super Admin') || $user->hasRole('super-admin'));
            
            if ($isSuperAdmin) {
                // Super Admin Stats
                $totalCompanies = Company::count();
                $totalDepartments = Department::count();
                $totalDesignations = Designation::count();
                $totalLocations = OfficeLocation::count();
                $systemSetting = SystemSetting::first();
                
                return view('dashboard.super_admin', compact(
                    'upcomingHolidays',
                    'upcomingBirthdays',
                    'upcomingAnniversaries',
                    'activeWfh',
                    'todayOfficePunch',
                    'totalCompanies',
                    'totalDepartments',
                    'totalDesignations',
                    'totalLocations',
                    'systemSetting'
                ));
            } else {
                // HR Stats
                $pendingProfileUpdates = EmployeeDataUpdate::where('acceptance', 0)->count();
                $pendingLeaves = LeaveApplication::where('status', 1)->count();
                $activeEmployeesCount = Employee::where('is_active', 1)->count();
                
                // Attendance overview
                $todayOfficePunchCount = Attendance::whereDate('punch_date', today()->format('Y-m-d'))->count();
                $todayActiveWfhCount = WfhClocking::whereNull('clock_out')
                    ->whereDate('created_at', today()->format('Y-m-d'))
                    ->count();

                return view('dashboard.hr', compact(
                    'upcomingHolidays',
                    'upcomingBirthdays',
                    'upcomingAnniversaries',
                    'activeWfh',
                    'todayOfficePunch',
                    'pendingProfileUpdates',
                    'pendingLeaves',
                    'activeEmployeesCount',
                    'todayOfficePunchCount',
                    'todayActiveWfhCount'
                ));
            }
        }

        // Default: Employee Dashboard
        $employeeId = $employee ? $employee->user_id : $user->id;
        $leaves = EmployeeLeave::where('employee_id', $employeeId)->latest()->take(5)->get();
        $payslips = PayrollPayment::where('employee_id', $employeeId)->latest()->take(5)->get();
        $meetings = Meeting::take(5)->get();
        $announcements = Announcement::latest()->take(5)->get();

        return view('dashboard.employee', compact(
            'upcomingHolidays',
            'upcomingBirthdays',
            'upcomingAnniversaries',
            'activeWfh',
            'todayOfficePunch',
            'leaves',
            'payslips',
            'meetings',
            'announcements'
        ));
    }
}
