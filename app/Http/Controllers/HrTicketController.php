<?php

namespace App\Http\Controllers;

use App\Models\HrTicket;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class HrTicketController extends Controller
{
    /**
     * Display a listing of the HR tickets.
     */
    public function index(Request $request): View
    {
        $baseQuery = HrTicket::query();

        // Non-HR/Admin users can only view their own logged tickets
        if (!Gate::allows('view.hr_tickets')) {
            $baseQuery->where('employee_id', auth()->id());
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'open' => (clone $baseQuery)->where('ticket_status', '1')->count(),
            'closed' => (clone $baseQuery)->where('ticket_status', '2')->count(),
            'on_hold' => (clone $baseQuery)->where('ticket_status', '3')->count(),
        ];

        $query = (clone $baseQuery)->with(['employee', 'company']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('ticket_status', $request->status);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $p = strtolower((string)$request->priority);
            $pMap = [
                'low' => ['1', 'low'],
                '1' => ['1', 'low'],
                'medium' => ['2', 'medium'],
                '2' => ['2', 'medium'],
                'high' => ['3', 'high'],
                '3' => ['3', 'high'],
                'critical' => ['4', 'critical'],
                '4' => ['4', 'critical'],
            ];
            $values = $pMap[$p] ?? [$request->priority];
            $query->whereIn('ticket_priority', $values);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(15)->withQueryString();
        $companies = Company::orderBy('name', 'asc')->get();

        return view('hr_tickets.index', compact('tickets', 'stats', 'companies'));
    }

    /**
     * Show the form for creating a new HR ticket.
     */
    public function create(): View
    {
        $companies = Company::orderBy('name', 'asc')->get();
        return view('hr_tickets.create', compact('companies'));
    }

    /**
     * Store a newly created HR ticket.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'company_id' => 'required|exists:xin_companies,company_id',
            'ticket_priority' => 'required|string|in:low,medium,high,critical',
            'description' => 'required|string',
        ]);

        $employeeId = auth()->id();

        $ticket = HrTicket::create([
            'ticket_code' => 'HRTK-' . strtoupper(uniqid()),
            'ticket_priority' => $request->ticket_priority,
            'company_id' => $request->company_id,
            'subject' => \App\Traits\HasCleanContent::sanitizeContent($request->subject, false),
            'employee_id' => $employeeId,
            'description' => \App\Traits\HasCleanContent::sanitizeContent($request->description, false),
            'remarks' => '',
            'ticket_status' => '1', // Open
            'created_by' => auth()->user()?->username ?? 'User',
            'created_at' => date('d-m-Y H:i:s'),
            'updated_date' => date('d-m-Y H:i:s'),
            'show_status' => 1,
            'updated_by' => $employeeId,
        ]);

        return redirect()->route('hr-tickets.index')
            ->with('success', 'HR Support Ticket opened successfully. Code: ' . $ticket->ticket_code);
    }

    /**
     * Display the specified HR ticket.
     */
    public function show(HrTicket $hrTicket): View
    {
        if (!Gate::allows('view.hr_tickets') && $hrTicket->employee_id !== auth()->id()) {
            abort(403, 'Unauthorized access to HR support ticket.');
        }

        $hrTicket->load(['employee', 'company']);
        return view('hr_tickets.show', compact('hrTicket'));
    }

    /**
     * Update HR ticket status / remarks.
     */
    public function updateStatus(Request $request, HrTicket $hrTicket): RedirectResponse
    {
        if (Gate::denies('edit.hr_tickets')) {
            abort(403);
        }

        $request->validate([
            'ticket_status' => 'required|string|in:1,2,3',
            'remarks' => 'nullable|string',
        ]);

        $hrTicket->update([
            'ticket_status' => $request->ticket_status,
            'remarks' => \App\Traits\HasCleanContent::sanitizeContent($request->remarks ?? $hrTicket->remarks, false),
            'updated_by' => auth()->id(),
            'updated_date' => date('d-m-Y H:i:s'),
        ]);

        return redirect()->route('hr-tickets.show', $hrTicket->ticket_id)
            ->with('success', 'HR Ticket status updated successfully.');
    }
}
