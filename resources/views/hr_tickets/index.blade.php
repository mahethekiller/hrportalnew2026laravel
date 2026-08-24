@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-ticket-simple me-2 text-primary"></i> HR Support Tickets</h4>
        <p class="text-muted fs-8 mb-0">File internal requests with human resources and track resolution.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <a href="{{ route('hr-tickets.create') }}" class="btn btn-primary btn-sm fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Open HR Ticket
        </a>
    </div>
</div>


<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 py-3">
        <form method="GET" action="{{ route('hr-tickets.index') }}" class="row g-2">
            <div class="col-md-3 col-6">
                <select name="status" class="form-select form-select-sm fs-8" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Open</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Closed</option>
                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>On Hold</option>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <select name="priority" class="form-select form-select-sm fs-8" onchange="this.form.submit()">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="table-light text-uppercase fs-9 fw-bold">
                    <tr>
                        <th class="ps-4">Ticket Code</th>
                        <th>Subject</th>
                        <th>Opened By</th>
                        <th>Company</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $tk)
                        <tr>
                            <td class="ps-4 font-monospace fw-bold text-primary">#{{ $tk->ticket_code }}</td>
                            <td>
                                <div class="fw-bold text-gray-900">{{ $tk->clean_subject }}</div>
                                <div class="text-muted fs-9 text-truncate" style="max-width: 250px;">{{ Str::limit($tk->plain_description, 50) }}</div>
                            </td>
                            <td>{{ $tk->employee ? $tk->employee->first_name . ' ' . $tk->employee->last_name : 'System' }}</td>
                            <td>{{ $tk->company ? $tk->company->name : '--' }}</td>
                            <td>{!! $tk->priority_badge !!}</td>
                            <td>
                                @php
                                    $sBadge = match(strval($tk->ticket_status)) {
                                        '2' => 'bg-success text-white',
                                        '3' => 'bg-warning text-dark',
                                        default => 'bg-danger text-white'
                                    };
                                    $statusName = match(strval($tk->ticket_status)) {
                                        '2' => 'Closed',
                                        '3' => 'On Hold',
                                        default => 'Open'
                                    };
                                @endphp
                                <span class="badge {{ $sBadge }} text-capitalize px-2 py-1 fs-9">{{ $statusName }}</span>
                            </td>
                            <td>{{ $tk->created_at }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('hr-tickets.show', $tk->ticket_id) }}" class="btn btn-sm btn-light-primary py-1 px-2 fs-9">
                                    <i class="fa-solid fa-eye me-1"></i> View Ticket
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-ticket-simple fs-2 mb-2 d-block text-muted"></i>
                                No HR support tickets logged.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
