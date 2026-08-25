<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeResignation;
use App\Services\EmployeeResignationService;
use App\Services\MailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SeparationClearanceController extends Controller
{
    public function __construct(
        protected EmployeeResignationService $resignationService,
        protected MailService $mailService
    ) {}

    /**
     * Departmental Clearance Hub.
     */
    public function index(Request $request): View
    {
        $stageFilter = $request->input('stage', 'all');
        $statusFilter = $request->input('status', 'all');
        $search = $request->input('search', '');

        $query = EmployeeResignation::with(['employee', 'manager', 'itPerson', 'accountPerson', 'hrPerson']);

        if ($search !== '') {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($stageFilter === 'manager') {
            $query->where(function ($q) {
                $q->whereNull('manager_status')->orWhere('manager_status', 0)->orWhere('manager_status', 2);
            });
        } elseif ($stageFilter === 'it') {
            $query->where('manager_status', 1)->where(function ($q) {
                $q->whereNull('it_status')->orWhere('it_status', 0)->orWhere('it_status', 2);
            });
        } elseif ($stageFilter === 'accounts') {
            $query->where('it_status', 1)->where(function ($q) {
                $q->whereNull('account_status')->orWhere('account_status', 0)->orWhere('account_status', 2);
            });
        } elseif ($stageFilter === 'hr') {
            $query->where('account_status', 1)->where(function ($q) {
                $q->whereNull('hr_status')->orWhere('hr_status', 0)->orWhere('hr_status', 2);
            });
        }

        $resignations = $query->orderBy('resignation_id', 'desc')->paginate(15);

        // Fetch Employees for Clearance Officer Selection
        $officers = Employee::select('user_id', 'first_name', 'last_name', 'employee_id', 'department_id')
            ->where('is_active', 1)
            ->orderBy('first_name', 'asc')
            ->get();

        // Exit Analytics Metrics
        $totalResignations = EmployeeResignation::count();
        $completedClearances = EmployeeResignation::where('hr_status', 1)->count();
        $pendingClearances = EmployeeResignation::where(function ($q) {
            $q->whereNull('hr_status')->orWhere('hr_status', '!=', 1);
        })->count();

        return view('settings.clearance', compact(
            'resignations',
            'officers',
            'stageFilter',
            'statusFilter',
            'search',
            'totalResignations',
            'completedClearances',
            'pendingClearances'
        ));
    }

    /**
     * Assign Clearance Officers (IT, Accounts, HR).
     */
    public function assignOfficers(Request $request, int $id): RedirectResponse
    {
        $resignation = $this->resignationService->getById($id);
        if (!$resignation) {
            return redirect()->back()->with('error', 'Resignation record not found.');
        }

        $request->validate([
            'it_person' => ['nullable', 'integer'],
            'account_per' => ['nullable', 'integer'],
            'hr_person' => ['nullable', 'integer'],
        ]);

        $this->resignationService->assignClearanceOfficers($resignation, [
            'it_person' => $request->input('it_person'),
            'account_per' => $request->input('account_per'),
            'hr_person' => $request->input('hr_person'),
        ]);

        return redirect()->back()->with('success', 'Clearance officers assigned successfully.');
    }

    /**
     * Trigger / Resend Clearance Notification Email to Assigned Officer.
     */
    public function notifyOfficer(Request $request, int $id): RedirectResponse
    {
        $resignation = $this->resignationService->getById($id);
        if (!$resignation) {
            return redirect()->back()->with('error', 'Resignation record not found.');
        }

        $stage = $request->input('stage', 'it');
        $sent = $this->resignationService->sendClearanceNotificationEmail($resignation, $stage);

        if ($sent) {
            return redirect()->back()->with('success', "No-Dues clearance notification email sent to assigned {$stage} officer.");
        }

        return redirect()->back()->with('error', 'Failed to send notification email. Please check SMTP profile and officer email.');
    }

    /**
     * Update Department Stage Clearance Status & Comments.
     */
    public function updateClearance(Request $request, int $id): RedirectResponse
    {
        $resignation = $this->resignationService->getById($id);
        if (!$resignation) {
            return redirect()->back()->with('error', 'Resignation record not found.');
        }

        $request->validate([
            'stage' => ['required', 'string', 'in:manager,it,accounts,hr'],
            'status' => ['required', 'integer', 'in:1,2'], // 1 = Cleared, 2 = Pending Dues
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $stage = $request->input('stage');
        $status = (int) $request->input('status');
        $comment = $request->input('comment');

        // Sequential Enforcement Check
        if ($stage === 'it' && (int) $resignation->manager_status !== 1) {
            return redirect()->back()->with('error', 'Cannot clear IT stage until Reporting Manager stage is Cleared.');
        }
        if ($stage === 'accounts' && (int) $resignation->it_status !== 1) {
            return redirect()->back()->with('error', 'Cannot clear Accounts stage until IT stage is Cleared.');
        }
        if ($stage === 'hr' && (int) $resignation->account_status !== 1) {
            return redirect()->back()->with('error', 'Cannot clear HR stage until Accounts stage is Cleared.');
        }

        $this->resignationService->updateDepartmentClearance($resignation, $stage, [
            'status' => $status,
            'comment' => $comment,
        ], Auth::user());

        $statusText = $status === 1 ? 'Cleared' : 'Marked as Pending Dues';
        return redirect()->back()->with('success', "Department clearance status updated to {$statusText}.");
    }
}
