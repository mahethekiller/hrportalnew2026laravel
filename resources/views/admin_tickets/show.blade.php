@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-ticket-simple me-2 text-primary"></i> Admin Ticket #{{ $adminTicket->ticket_code }}</h4>
        <p class="text-muted fs-8 mb-0">Conversation & Processing Thread.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <a href="{{ route('admin-tickets.index') }}" class="btn btn-light btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show fs-8" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light p-2 rounded-circle">
                            <i class="fa-solid fa-user fs-5 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-gray-900">{{ $adminTicket->employee ? $adminTicket->employee->first_name . ' ' . $adminTicket->employee->last_name : 'System User' }}</h6>
                            <span class="text-muted fs-9">Logged on {{ $adminTicket->created_at }}</span>
                        </div>
                    </div>
                    <div>
                        @php
                            $pBadge = match(strtolower($adminTicket->ticket_priority)) {
                                'critical' => 'bg-danger text-white',
                                'high' => 'bg-warning text-dark',
                                'medium' => 'bg-info text-dark',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $pBadge }} text-capitalize px-2 py-1 fs-9">{{ $adminTicket->ticket_priority }} Priority</span>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <h5 class="fw-bold text-gray-900 mb-3">{{ $adminTicket->subject }}</h5>
                <p class="text-gray-800 fs-8 leading-relaxed whitespace-pre-line">{{ $adminTicket->description }}</p>
            </div>
        </div>

        @if(!empty($adminTicket->remarks))
            <div class="card border-0 shadow-sm rounded-3 bg-light-primary border-start border-primary border-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-comment-dots me-1"></i> Admin Resolution Remarks</h6>
                    <p class="text-gray-800 fs-8 mb-0 whitespace-pre-line">{{ $adminTicket->remarks }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Info & Action -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4">
                <h6 class="mb-0 fw-bold text-gray-900"><i class="fa-solid fa-sliders me-1 text-primary"></i> Ticket Information</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush fs-8 mb-3">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Target Company:</span>
                        <span class="fw-bold text-end">{{ $adminTicket->company ? $adminTicket->company->name : '--' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Current Status:</span>
                        @php
                            $sBadge = match(strval($adminTicket->ticket_status)) {
                                '2' => 'bg-success text-white',
                                '3' => 'bg-warning text-dark',
                                default => 'bg-danger text-white'
                            };
                            $statusName = match(strval($adminTicket->ticket_status)) {
                                '2' => 'Closed',
                                '3' => 'On Hold',
                                default => 'Open'
                            };
                        @endphp
                        <span class="badge {{ $sBadge }} text-capitalize fs-9">{{ $statusName }}</span>
                    </li>
                </ul>

                @can('edit.admin_tickets')
                    <hr>
                    <form method="POST" action="{{ route('admin-tickets.status', $adminTicket->ticket_id) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label fs-9 fw-semibold text-muted">Update Status</label>
                            <select name="ticket_status" class="form-select form-select-sm fs-8" required>
                                <option value="1" {{ strval($adminTicket->ticket_status) === '1' ? 'selected' : '' }}>Open</option>
                                <option value="2" {{ strval($adminTicket->ticket_status) === '2' ? 'selected' : '' }}>Closed</option>
                                <option value="3" {{ strval($adminTicket->ticket_status) === '3' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-9 fw-semibold text-muted">Resolution / Processing Remarks</label>
                            <textarea name="remarks" rows="4" class="form-control form-control-sm fs-8" placeholder="Enter remarks here...">{{ $adminTicket->remarks }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Update Configuration</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
