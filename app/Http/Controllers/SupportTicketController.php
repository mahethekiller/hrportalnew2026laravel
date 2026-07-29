<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketComment;
use App\Models\TicketAttachment;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of support tickets.
     */
    public function index(Request $request): View
    {
        $this->authorizeAccess();

        $query = SupportTicket::with(['employee', 'department']);

        // Non-admin/HR users can only see their own tickets
        if (!Gate::allows('view.support_tickets')) {
            $query->where('employee_id', auth()->id());
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('ticket_status', $request->status);
        }
        if ($request->filled('priority')) {
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

        $keyName = (new \App\Models\SupportTicket)->getKeyName();
        $tickets = $query->orderBy($keyName, 'desc')->paginate(10);

        return view('support_tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new support ticket.
     */
    public function create(): View
    {
        $departments = Department::orderBy('id', 'asc')->get();
        return view('support_tickets.create', compact('departments'));
    }

    /**
     * Store a newly created support ticket.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'department_id' => 'required|exists:xin_departments,department_id',
            'ticket_priority' => 'required|string|in:low,medium,high,critical',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $employeeId = auth()->id();
        $employee = Employee::where('user_id', $employeeId)->first();
        $companyId = $employee ? $employee->company_id : 1;

        $ticket = SupportTicket::create([
            'company_id' => $companyId,
            'ticket_code' => 'TK-' . strtoupper(Str::random(6)),
            'subject' => $request->subject,
            'employee_id' => $employeeId,
            'ticket_priority' => $request->ticket_priority,
            'department_id' => $request->department_id,
            'assigned_to' => '0',
            'message' => $request->description,
            'description' => $request->description,
            'ticket_remarks' => '',
            'ticket_status' => '1',
            'ticket_note' => '',
            'created_at' => date('d-m-Y H:i:s'),
        ]);

        // Process Attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets'), $fileName);

            TicketAttachment::create([
                'ticket_id' => $ticket->ticket_id,
                'upload_by' => auth()->id(),
                'file_title' => 'Original Attachment',
                'file_description' => 'Attached during ticket submission',
                'attachment_file' => 'uploads/tickets/' . $fileName,
                'created_at' => date('d-m-Y H:i:s'),
            ]);
        }

        return redirect()->route('support-tickets.index')
            ->with('success', 'Support ticket opened successfully. Code: ' . $ticket->ticket_code);
    }

    /**
     * Display the specified support ticket.
     */
    public function show(SupportTicket $supportTicket): View
    {
        // Enforce owner check for non-HR/Admin
        if (!Gate::allows('view.support_tickets') && $supportTicket->employee_id !== auth()->id()) {
            abort(403, 'Unauthorized access to support ticket.');
        }

        $supportTicket->load(['employee', 'department', 'comments.user', 'attachments']);
        $employees = Employee::orderBy('first_name', 'asc')->get();

        return view('support_tickets.show', compact('supportTicket', 'employees'));
    }

    /**
     * Add a comment/reply to a support ticket.
     */
    public function addComment(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        if (!Gate::allows('view.support_tickets') && $supportTicket->employee_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'reply_content' => 'required|string',
        ]);

        TicketComment::create([
            'ticket_id' => $supportTicket->ticket_id,
            'user_id' => auth()->id(),
            'ticket_comments' => $request->reply_content,
            'created_at' => date('d-m-Y H:i:s'),
        ]);

        return redirect()->route('support-tickets.show', $supportTicket->ticket_id)
            ->with('success', 'Reply posted successfully.');
    }

    /**
     * Upload an attachment to a support ticket.
     */
    public function uploadAttachment(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        if (!Gate::allows('view.support_tickets') && $supportTicket->employee_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'file_title' => 'required|string|max:255',
            'attachment' => 'required|file|max:5120',
        ]);

        $file = $request->file('attachment');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/tickets'), $fileName);

        TicketAttachment::create([
            'ticket_id' => $supportTicket->ticket_id,
            'upload_by' => auth()->id(),
            'file_title' => $request->file_title,
            'file_description' => $request->description ?? 'Uploaded via portal',
            'attachment_file' => 'uploads/tickets/' . $fileName,
            'created_at' => date('d-m-Y H:i:s'),
        ]);

        return redirect()->route('support-tickets.show', $supportTicket->ticket_id)
            ->with('success', 'Attachment uploaded successfully.');
    }

    /**
     * Update support ticket status/assignee.
     */
    public function updateStatus(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        if (Gate::denies('edit.support_tickets')) {
            abort(403);
        }

        $request->validate([
            'ticket_status' => 'required|string|in:1,2,3',
            'assigned_to' => 'nullable|integer',
            'ticket_remarks' => 'nullable|string',
        ]);

        $supportTicket->update([
            'ticket_status' => $request->ticket_status,
            'assigned_to' => $request->assigned_to ?? $supportTicket->assigned_to,
            'ticket_remarks' => $request->ticket_remarks ?? $supportTicket->ticket_remarks,
        ]);

        return redirect()->route('support-tickets.show', $supportTicket->ticket_id)
            ->with('success', 'Ticket configuration updated successfully.');
    }

    /**
     * General access guard for index/views.
     */
    protected function authorizeAccess(): void
    {
        // Users can always view their own tickets, or if they have view.support_tickets
    }
}
