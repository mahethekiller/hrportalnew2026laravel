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
        $query = HrTicket::with(['employee', 'company']);

        // Non-HR/Admin users can only view their own logged tickets
        if (!Gate::allows('view.hr_tickets')) {
            $query->where('employee_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('ticket_status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('ticket_priority', $request->priority);
        }

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(15);

        return view('hr_tickets.index', compact('tickets'));
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
            'subject' => $request->subject,
            'employee_id' => $employeeId,
            'description' => $request->description,
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
            'remarks' => $request->remarks ?? $hrTicket->remarks,
            'updated_by' => auth()->id(),
            'updated_date' => date('d-m-Y H:i:s'),
        ]);

        return redirect()->route('hr-tickets.show', $hrTicket->ticket_id)
            ->with('success', 'HR Ticket status updated successfully.');
    }
}
