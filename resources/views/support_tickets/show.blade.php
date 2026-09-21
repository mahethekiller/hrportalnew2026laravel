@extends('layouts.app')

@section('title', 'Ticket #' . $supportTicket->ticket_code . ' - ' . $supportTicket->clean_subject)

@section('content')
@php
    $statusStr = strval($supportTicket->ticket_status);
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

    $priorityStr = strtolower((string)$supportTicket->ticket_priority);
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

    $requesterName = $supportTicket->employee ? ($supportTicket->employee->first_name . ' ' . $supportTicket->employee->last_name) : 'System User';
    $assignedEmp = \App\Models\Employee::where('user_id', $supportTicket->assigned_to)->first();
    $assignedName = $assignedEmp ? ($assignedEmp->first_name . ' ' . $assignedEmp->last_name) : 'Unassigned';
@endphp

<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('support-tickets.index') }}" class="text-body-secondary text-decoration-none fs-8 fw-medium hover-text-primary">
                    <i class="fa-solid fa-headset me-1"></i> Helpdesk
                </a>
                <span class="text-body-tertiary fs-9">/</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-9 font-monospace fw-bold">
                    #{{ $supportTicket->ticket_code }}
                </span>
                <span class="badge {{ $statusBadgeClass }} rounded-pill px-2.5 py-0.5 fs-9 fw-semibold ms-1">
                    {{ $statusLabel }}
                </span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">{{ $supportTicket->clean_subject }}</h1>
            <p class="text-body-secondary fs-7 mb-0">Conversation thread and resolution timeline for ticket #{{ $supportTicket->ticket_code }}.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('support-tickets.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
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
        <!-- Left Column: Message Thread & Replies -->
        <div class="col-lg-8">
            <!-- Ticket Originator Message Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 38px; height: 38px; min-width: 38px;">
                                {{ strtoupper(substr($requesterName, 0, 2)) }}
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-body-emphasis fs-8">{{ $requesterName }}</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 fs-9">Requester</span>
                                </div>
                                <span class="fs-9 text-body-secondary">
                                    <i class="fa-regular fa-clock me-1"></i> Logged on <x-human-date :value="$supportTicket->created_at" :time="true" />
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
                    <div class="fs-8 text-body-emphasis leading-relaxed mb-0">
                        {!! $supportTicket->clean_description !!}
                    </div>
                </div>
            </div>

            <!-- Replies Timeline Header -->
            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-comments text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis fs-6">Discussion Thread</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2 fs-9">
                        {{ $supportTicket->comments->count() }} {{ Str::plural('Reply', $supportTicket->comments->count()) }}
                    </span>
                </div>
            </div>

            <!-- Replies Stream -->
            <div class="timeline-stream mb-4">
                @forelse($supportTicket->comments as $comment)
                    @php
                        $commentAuthor = $comment->user ? ($comment->user->first_name . ' ' . $comment->user->last_name) : 'Support Agent';
                        $isTicketAuthor = $comment->user_id === $supportTicket->employee_id;
                    @endphp
                    <div class="card border-0 shadow-sm rounded-3 bg-body mb-3 transition-all hover-lift">
                        <div class="card-body p-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle {{ $isTicketAuthor ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }} d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 28px; height: 28px;">
                                        {{ strtoupper(substr($commentAuthor, 0, 2)) }}
                                    </div>
                                    <span class="fw-bold text-body-emphasis fs-8">{{ $commentAuthor }}</span>
                                    @if($isTicketAuthor)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.2 fs-9">Requester</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.2 fs-9">Agent / HR</span>
                                    @endif
                                </div>
                                <span class="fs-9 text-body-secondary">
                                    <x-human-date :value="$comment->created_at" :time="true" />
                                </span>
                            </div>
                            <div class="fs-8 text-body-emphasis leading-relaxed ps-4 ms-1">
                                {!! $comment->clean_comment !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm rounded-3 bg-body mb-4 py-4 text-center">
                        <i class="fa-solid fa-comments fs-3 text-body-tertiary mb-2 d-block"></i>
                        <h6 class="fw-bold text-body-emphasis mb-1">No responses posted yet</h6>
                        <p class="fs-9 text-body-secondary mb-0">Use the reply composer below to post an update or follow up on this inquiry.</p>
                    </div>
                @endforelse
            </div>

            <!-- Post a Reply Form -->
            @if(strval($supportTicket->ticket_status) !== '2')
                <div class="card border-0 shadow-sm rounded-3 bg-body">
                    <div class="card-header bg-transparent border-bottom py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-reply text-primary fs-7"></i>
                            <h6 class="mb-0 fw-bold text-body-emphasis fs-7">Post a Response</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('support-tickets.comments', $supportTicket->ticket_id) }}">
                            @csrf
                            <!-- Rule 6 WYSIWYG Editor for Rich Replies -->
                            <div class="mb-3">
                                <x-wysiwyg-editor name="reply_content" :value="old('reply_content')" height="180px" />
                                @error('reply_content') <div class="text-danger fs-9 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-9 text-body-secondary">All parties subscribed will receive notification updates.</span>
                                <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                                    <i class="fa-solid fa-paper-plane me-1.5"></i> Submit Response
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-2.5 p-3 rounded-3" role="alert">
                    <i class="fa-solid fa-lock fs-5 text-warning"></i>
                    <div class="fs-8">
                        <strong>Ticket Resolved & Closed:</strong> This ticket thread has been finalized. If you require further assistance on this issue, please open a new support ticket.
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Settings, Metadata & Attachments -->
        <div class="col-lg-4">
            <!-- Ticket Information Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders text-primary fs-7"></i>
                        <h6 class="mb-0 fw-bold text-body-emphasis fs-7">Ticket Dossier</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush fs-8 mb-3">
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-body-secondary">Status:</span>
                            <span class="badge {{ $statusBadgeClass }} rounded-pill px-2.5 py-0.5 fs-9 fw-semibold">{{ $statusLabel }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-body-secondary">Priority:</span>
                            <span class="badge {{ $priorityBadgeClass }} rounded-pill px-2.5 py-0.5 fs-9 fw-semibold">{{ $priorityLabel }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-body-secondary">Department:</span>
                            <span class="fw-bold text-body-emphasis">{{ $supportTicket->department ? $supportTicket->department->department_name : 'General' }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-body-secondary">Assigned Agent:</span>
                            <span class="fw-bold text-body-emphasis">{{ $assignedName }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-body-secondary">Created:</span>
                            <span class="text-body-emphasis"><x-human-date :value="$supportTicket->created_at" :time="true" /></span>
                        </li>
                    </ul>

                    @can('edit.support_tickets')
                        <div class="pt-3 border-top">
                            <span class="fs-9 fw-bold text-uppercase text-body-secondary d-block mb-2">Agent Controls</span>
                            <form method="POST" action="{{ route('support-tickets.status', $supportTicket->ticket_id) }}">
                                @csrf
                                <div class="mb-2.5">
                                    <label class="form-label fs-9 fw-semibold text-body-secondary mb-1">Update Status</label>
                                    <select name="ticket_status" class="form-select form-select-sm bg-body fs-8" required>
                                        <option value="1" {{ strval($supportTicket->ticket_status) === '1' ? 'selected' : '' }}>Open</option>
                                        <option value="3" {{ strval($supportTicket->ticket_status) === '3' ? 'selected' : '' }}>On Hold</option>
                                        <option value="2" {{ strval($supportTicket->ticket_status) === '2' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                                <div class="mb-2.5">
                                    <label class="form-label fs-9 fw-semibold text-body-secondary mb-1">Reassign Agent</label>
                                    <select name="assigned_to" class="form-select form-select-sm select-search bg-body fs-8" data-control="select2">
                                        <option value="0">Unassigned</option>
                                        @foreach($employees ?? [] as $emp)
                                            <option value="{{ $emp->user_id }}" {{ $supportTicket->assigned_to == $emp->user_id ? 'selected' : '' }}>
                                                {{ $emp->first_name }} {{ $emp->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-9 fw-semibold text-body-secondary mb-1">Internal Resolution Remarks</label>
                                    <textarea name="ticket_remarks" rows="2" class="form-control form-control-sm bg-body fs-8" placeholder="Agent notes...">{{ $supportTicket->plain_remarks }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold submit-loader" onclick="submitWithLoader(this)">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i> Update Ticket State
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>

            <!-- Attachments Manager Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-paperclip text-primary fs-7"></i>
                            <h6 class="mb-0 fw-bold text-body-emphasis fs-7">Attachments</h6>
                        </div>
                        <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2 fs-9">
                            {{ $supportTicket->attachments->count() }} Files
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush fs-8 mb-3">
                        @forelse($supportTicket->attachments as $attach)
                            @php
                                $ext = strtolower(pathinfo($attach->attachment_file, PATHINFO_EXTENSION) ?: 'file');
                            @endphp
                            <li class="list-group-item bg-transparent d-flex align-items-center justify-content-between px-0 py-2">
                                <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 220px;">
                                    <i class="fa-solid {{ in_array($ext, ['pdf']) ? 'fa-file-pdf text-danger' : (in_array($ext, ['doc', 'docx']) ? 'fa-file-word text-primary' : 'fa-file text-secondary') }} fs-7"></i>
                                    <span class="fw-semibold text-body-emphasis text-truncate" title="{{ $attach->file_title }}">{{ $attach->file_title }}</span>
                                </div>
                                <a href="{{ asset($attach->attachment_file) }}" target="_blank" class="btn btn-sm btn-outline-success px-2 py-0.5 rounded-2" title="Download File">
                                    <i class="fa-solid fa-download fs-9"></i>
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item bg-transparent px-0 text-center text-muted fs-9 py-3">
                                No attachments uploaded to this ticket.
                            </li>
                        @endforelse
                    </ul>

                    @if(strval($supportTicket->ticket_status) !== '2')
                        <div class="pt-3 border-top">
                            <span class="fs-9 fw-bold text-uppercase text-body-secondary d-block mb-2">Add Attachment</span>
                            <form method="POST" action="{{ route('support-tickets.attachments', $supportTicket->ticket_id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <input type="text" name="file_title" class="form-control form-control-sm bg-body fs-8" required placeholder="Attachment Title e.g. Log file">
                                </div>
                                <div class="mb-2.5">
                                    <input type="file" name="attachment" class="form-control form-control-sm bg-body" required>
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100 fw-bold submit-loader" onclick="submitWithLoader(this)">
                                    <i class="fa-solid fa-upload me-1.5"></i> Upload Attachment
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
