@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-headset me-2 text-primary"></i> Ticket Thread #{{ $supportTicket->ticket_code }}</h4>
        <p class="text-muted fs-8 mb-0">Open Conversation and Resolution Workflow.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <a href="{{ route('support-tickets.index') }}" class="btn btn-light btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Helpdesk
        </a>
    </div>
</div>


<div class="row g-4">
    <!-- Left Column: Message Thread -->
    <div class="col-lg-8">
        <!-- Ticket Originator Message -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light p-2 rounded-circle">
                            <i class="fa-solid fa-user fs-5 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-gray-900">{{ $supportTicket->employee ? $supportTicket->employee->first_name . ' ' . $supportTicket->employee->last_name : 'System User' }}</h6>
                            <span class="text-muted fs-9">Logged on {{ $supportTicket->created_at }}</span>
                        </div>
                    </div>
                    <div>
                        {!! $supportTicket->priority_badge !!}
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <h5 class="fw-bold text-gray-900 mb-3">{{ $supportTicket->clean_subject }}</h5>
                <div class="text-gray-800 fs-8 leading-relaxed">{!! $supportTicket->clean_description !!}</div>
            </div>
        </div>

        <!-- Replies Timeline -->
        <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-comments me-2 text-primary"></i> Discussion Replies ({{ $supportTicket->comments->count() }})</h6>
        
        @forelse($supportTicket->comments as $comment)
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-reply text-muted fs-9"></i>
                            <span class="fw-bold text-gray-800 fs-8">{{ $comment->user ? $comment->user->first_name . ' ' . $comment->user->last_name : 'System User' }}</span>
                            @if($comment->user_id === $supportTicket->employee_id)
                                <span class="badge bg-light-primary text-primary fs-9 px-1">Author</span>
                            @else
                                <span class="badge bg-light-info text-info fs-9 px-1">Agent</span>
                            @endif
                        </div>
                        <span class="text-muted fs-9">{{ $comment->created_at }}</span>
                    </div>
                    <div class="text-gray-800 fs-8 mb-0">{!! $comment->clean_comment !!}</div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-3 mb-4 py-4 text-center text-muted fs-8">
                No replies posted on this ticket yet.
            </div>
        @endforelse

        <!-- Post a Reply -->
        @if(strval($supportTicket->ticket_status) !== '2')
            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h6 class="mb-0 fw-bold text-gray-900"><i class="fa-solid fa-reply me-1"></i> Post a Reply</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('support-tickets.comments', $supportTicket->ticket_id) }}">
                        @csrf
                        <div class="mb-3">
                            <textarea name="reply_content" rows="4" class="form-control form-control-sm" required placeholder="Type your response here..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Post Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-warning mt-4 fs-8" role="alert">
                <i class="fa-solid fa-lock me-2"></i> This ticket is closed. You can no longer reply to this conversation.
            </div>
        @endif
    </div>

    <!-- Right Column: Settings & Attachments -->
    <div class="col-lg-4">
        <!-- Status / Manager Panel -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4">
                <h6 class="mb-0 fw-bold text-gray-900"><i class="fa-solid fa-sliders me-1 text-primary"></i> Ticket Information</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush fs-8 mb-3">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Target Department:</span>
                        <span class="fw-bold">{{ $supportTicket->department ? $supportTicket->department->department_name : '--' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Current Status:</span>
                        @php
                            $sBadge = match(strval($supportTicket->ticket_status)) {
                                '2' => 'bg-success text-white',
                                '3' => 'bg-warning text-dark',
                                default => 'bg-danger text-white'
                            };
                            $statusName = match(strval($supportTicket->ticket_status)) {
                                '2' => 'Closed',
                                '3' => 'On Hold',
                                default => 'Open'
                            };
                        @endphp
                        <span class="badge {{ $sBadge }} text-capitalize fs-9">{{ $statusName }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Assigned To:</span>
                        @php
                            $assignedEmp = \App\Models\Employee::where('user_id', $supportTicket->assigned_to)->first();
                        @endphp
                        <span class="fw-bold">{{ $assignedEmp ? $assignedEmp->first_name . ' ' . $assignedEmp->last_name : 'Unassigned' }}</span>
                    </li>
                </ul>

                @can('edit.support_tickets')
                    <hr>
                    <form method="POST" action="{{ route('support-tickets.status', $supportTicket->ticket_id) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label fs-9 fw-semibold text-muted">Update Status</label>
                            <select name="ticket_status" class="form-select form-select-sm fs-8" required>
                                <option value="1" {{ strval($supportTicket->ticket_status) === '1' ? 'selected' : '' }}>Open</option>
                                <option value="2" {{ strval($supportTicket->ticket_status) === '2' ? 'selected' : '' }}>Closed</option>
                                <option value="3" {{ strval($supportTicket->ticket_status) === '3' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fs-9 fw-semibold text-muted">Assign Agent</label>
                            <select name="assigned_to" class="form-select form-select-sm fs-8">
                                <option value="0">Unassigned</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->user_id }}" {{ $supportTicket->assigned_to == $emp->user_id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-9 fw-semibold text-muted">Internal Remarks / Note</label>
                            <textarea name="ticket_remarks" rows="3" class="form-control form-control-sm fs-8" placeholder="Internal processing remarks...">{{ $supportTicket->plain_remarks }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Update Configuration</button>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Attachments Manager -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4">
                <h6 class="mb-0 fw-bold text-gray-900"><i class="fa-solid fa-paperclip me-1 text-primary"></i> Files & Attachments</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush fs-8 mb-3">
                    @forelse($supportTicket->attachments as $attach)
                        <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                            <div class="text-truncate" style="max-width: 180px;">
                                <i class="fa-solid fa-file-pdf text-danger me-1"></i>
                                <span class="fw-bold">{{ $attach->file_title }}</span>
                            </div>
                            <a href="{{ asset($attach->attachment_file) }}" target="_blank" class="btn btn-sm btn-light py-0 px-2 fs-9">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-center text-muted fs-9 py-3">No attachments uploaded yet.</li>
                    @endforelse
                </ul>

                @if(strval($supportTicket->ticket_status) !== '2')
                    <hr>
                    <form method="POST" action="{{ route('support-tickets.attachments', $supportTicket->ticket_id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <input type="text" name="file_title" class="form-control form-control-sm fs-8" required placeholder="Attachment Title e.g. Screenshot">
                        </div>
                        <div class="mb-2">
                            <input type="file" name="attachment" class="form-control form-control-sm" required>
                        </div>
                        <button type="submit" class="btn btn-light btn-sm w-100 fw-bold text-primary"><i class="fa-solid fa-upload me-1"></i> Upload File</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
