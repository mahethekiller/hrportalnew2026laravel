@extends('layouts.app')

@section('title', 'HR Ticket #' . $hrTicket->ticket_code . ' - ' . $hrTicket->clean_subject)

@section('content')
@php
    $statusStr = strval($hrTicket->ticket_status);
    $statusBadgeClass = match($statusStr) {
        '2' => 'bg-success-subtle text-success border border-success-subtle',
        '3' => 'bg-warning-subtle text-warning border border-warning-subtle',
        default => 'bg-danger-subtle text-danger border border-danger-subtle'
    };
    $statusLabel = match($statusStr) {
        '2' => 'Closed',
        '3' => 'On Hold',
        default => 'Open'
    };

    $priorityStr = strtolower((string)$hrTicket->ticket_priority);
    $priorityBadgeClass = match($priorityStr) {
        '4', 'critical' => 'bg-danger text-white',
        '3', 'high' => 'bg-danger-subtle text-danger border border-danger-subtle',
        '2', 'medium' => 'bg-warning-subtle text-warning border border-warning-subtle',
        default => 'bg-info-subtle text-info border border-info-subtle'
    };
    $priorityLabel = match($priorityStr) {
        '4', 'critical' => 'Critical',
        '3', 'high' => 'High',
        '2', 'medium' => 'Medium',
        default => 'Low'
    };

    $requesterName = $hrTicket->employee ? ($hrTicket->employee->first_name . ' ' . $hrTicket->employee->last_name) : 'System User';
    $initials = strtoupper(substr($hrTicket->employee?->first_name ?? 'U', 0, 1) . substr($hrTicket->employee?->last_name ?? 'S', 0, 1));
@endphp

<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('hr-tickets.index') }}" class="text-body-secondary text-decoration-none fs-8 fw-medium hover-text-primary">
                    <i class="fa-solid fa-ticket-simple me-1"></i> HR Support
                </a>
                <span class="text-body-tertiary fs-9">/</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-9 font-monospace fw-bold">
                    #{{ $hrTicket->ticket_code }}
                </span>
                <span class="badge {{ $statusBadgeClass }} rounded-pill px-2.5 py-0.5 fs-9 fw-semibold ms-1">
                    {{ $statusLabel }}
                </span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">{{ $hrTicket->clean_subject }}</h1>
            <p class="text-body-secondary fs-7 mb-0">Conversation thread and HR resolution timeline for ticket #{{ $hrTicket->ticket_code }}.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hr-tickets.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Queue
            </a>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
            <div class="flex-grow-1 fs-8">
                <strong>Attention required:</strong> {{ $errors->first() }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Message Thread & HR Resolution Stream -->
        <div class="col-lg-8">
            <!-- Ticket Originator Message Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-8 border border-primary-subtle" style="width: 38px; height: 38px; min-width: 38px;">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-body-emphasis fs-8">{{ $requesterName }}</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 fs-9">Requester</span>
                                </div>
                                <span class="fs-9 text-body-secondary">
                                    <i class="fa-regular fa-clock me-1"></i> Logged on <x-human-date :value="$hrTicket->created_at" :time="true" />
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="badge {{ $priorityBadgeClass }} rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                <i class="fa-solid fa-flag me-1"></i> {{ $priorityLabel }} Priority
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="text-body-emphasis fs-8 line-height-base">
                        {!! $hrTicket->clean_description !!}
                    </div>
                </div>
            </div>

            <!-- Existing HR Resolution Remarks Stream Card (if present) -->
            @if(!empty($hrTicket->remarks))
                <div class="card border-0 shadow-sm rounded-3 bg-body mb-4 border-start border-primary border-3">
                    <div class="card-header bg-transparent border-bottom py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center fw-bold fs-8 border border-success-subtle" style="width: 38px; height: 38px; min-width: 38px;">
                                    <i class="fa-solid fa-user-shield"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-body-emphasis fs-8">HR Department Response</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-9">Official Resolution</span>
                                    </div>
                                    @if(!empty($hrTicket->updated_date))
                                        <span class="fs-9 text-body-secondary">
                                            <i class="fa-regular fa-calendar-check me-1"></i> Updated on {{ $hrTicket->updated_date }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-9">
                                <i class="fa-solid fa-check-double me-1"></i> Processed
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4 fs-8 text-body-emphasis line-height-base">
                        {!! $hrTicket->clean_remarks !!}
                    </div>
                </div>
            @endif

            <!-- HR Response & Status Update Form Card (Rule 6, 12) -->
            @can('edit.hr_tickets')
                <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                    <div class="card-header bg-transparent border-bottom py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-reply-all text-primary fs-6"></i>
                            <h6 class="mb-0 fw-bold text-body-emphasis">Update Status & Resolution Remarks</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('hr-tickets.status', $hrTicket->ticket_id) }}">
                            @csrf
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Ticket Status <span class="text-danger">*</span></label>
                                    <select name="ticket_status" class="form-select fs-8" required>
                                        <option value="1" {{ strval($hrTicket->ticket_status) === '1' ? 'selected' : '' }}>Open - In Progress / Under Review</option>
                                        <option value="3" {{ strval($hrTicket->ticket_status) === '3' ? 'selected' : '' }}>On Hold - Awaiting Info / External Escalation</option>
                                        <option value="2" {{ strval($hrTicket->ticket_status) === '2' ? 'selected' : '' }}>Closed - Resolution Complete</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Resolution Remarks / HR Response</label>
                                    <p class="text-body-secondary fs-9 mb-2">Provide clear guidance, action outcomes, or policies applied to address this inquiry.</p>
                                    <!-- Rule 6: Mandatory WYSIWYG Editor -->
                                    <x-wysiwyg-editor name="remarks" :value="$hrTicket->clean_remarks" height="180px" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-3">
                                <!-- Rule 12: Submit Loading Spinner State -->
                                <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                                    <i class="fa-solid fa-floppy-disk me-1.5"></i> Save & Update Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>

        <!-- Right Column: Meta Info & Attributes -->
        <div class="col-lg-4">
            <!-- Ticket Info Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders text-primary fs-6"></i>
                        <h6 class="mb-0 fw-bold text-body-emphasis">Ticket Metadata</h6>
                    </div>
                </div>
                <div class="card-body p-4 fs-8">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="text-body-secondary">Ticket Reference:</span>
                            <span class="font-monospace fw-bold text-body-emphasis">#{{ $hrTicket->ticket_code }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="text-body-secondary">Current Status:</span>
                            <span>{!! $hrTicket->status_badge !!}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="text-body-secondary">Priority Level:</span>
                            <span>{!! $hrTicket->priority_badge !!}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="text-body-secondary">Target Entity:</span>
                            <span class="fw-semibold text-body-emphasis text-end text-truncate" style="max-width: 170px;" title="{{ $hrTicket->company?->name }}">
                                {{ $hrTicket->company?->name ?? 'Organization' }}
                            </span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="text-body-secondary">Submitted By:</span>
                            <span class="fw-semibold text-body-emphasis text-end">{{ $requesterName }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="text-body-secondary">Created Date:</span>
                            <span class="text-body-secondary fs-9"><x-human-date :value="$hrTicket->created_at" :time="true" /></span>
                        </li>
                        @if(!empty($hrTicket->updated_date))
                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-body-secondary">Last Modified:</span>
                                <span class="text-body-secondary fs-9">{{ $hrTicket->updated_date }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Guidelines & Policy Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-info text-info fs-6"></i>
                        <h6 class="mb-0 fw-bold text-body-emphasis">HR Service Level Info</h6>
                    </div>
                </div>
                <div class="card-body p-4 fs-9 text-body-secondary line-height-base">
                    <div class="d-flex align-items-start gap-2 mb-2.5">
                        <i class="fa-solid fa-clock text-primary mt-1"></i>
                        <div>
                            <span class="fw-bold text-body-emphasis d-block">Response Timelines</span>
                            Standard inquiries are addressed within 24 to 48 business hours. Critical issues take immediate priority.
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="fa-solid fa-lock text-success mt-1"></i>
                        <div>
                            <span class="fw-bold text-body-emphasis d-block">Confidential Handling</span>
                            All communications and attachments within HR tickets are held in strict organizational confidentiality.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
