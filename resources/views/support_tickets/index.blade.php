@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-headset me-2 text-primary"></i> Support Tickets Helpdesk</h4>
        <p class="text-muted fs-8 mb-0">Manage customer/employee support requests and track resolution threads.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <a href="{{ route('support-tickets.create') }}" class="btn btn-primary btn-sm fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Open New Ticket
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show fs-8" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 py-3">
        <form method="GET" action="{{ route('support-tickets.index') }}" class="row g-2">
            <div class="col-md-3 col-6">
                <select name="status" class="form-select form-select-sm fs-8" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
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
                        <th>Department</th>
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
                                <div class="fw-bold text-gray-900">{{ $tk->subject }}</div>
                                <div class="text-muted fs-9 text-truncate" style="max-width: 250px;">{{ Str::limit($tk->description, 50) }}</div>
                            </td>
                            <td>{{ $tk->employee ? $tk->employee->first_name . ' ' . $tk->employee->last_name : 'System' }}</td>
                            <td>{{ $tk->department ? $tk->department->department_name : '--' }}</td>
                            <td>
                                @php
                                    $pBadge = match(strtolower($tk->ticket_priority)) {
                                        'critical' => 'bg-danger text-white',
                                        'high' => 'bg-warning text-dark',
                                        'medium' => 'bg-info text-dark',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $pBadge }} text-capitalize px-2 py-1 fs-9">{{ $tk->ticket_priority }}</span>
                            </td>
                            <td>
                                @php
                                    $sBadge = match(strtolower($tk->ticket_status)) {
                                        'resolved' => 'bg-success text-white',
                                        'closed' => 'bg-light text-dark border',
                                        default => 'bg-primary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $sBadge }} text-capitalize px-2 py-1 fs-9">{{ $tk->ticket_status }}</span>
                            </td>
                            <td>{{ $tk->created_at }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('support-tickets.show', $tk->ticket_id) }}" class="btn btn-sm btn-light-primary py-1 px-2 fs-9">
                                    <i class="fa-solid fa-eye me-1"></i> View Thread
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-headset fs-2 mb-2 d-block text-muted"></i>
                                No support tickets logged under this search.
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
