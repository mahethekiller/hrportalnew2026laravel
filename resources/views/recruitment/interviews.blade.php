@extends('layouts.app')

@section('title', 'Scheduled Candidate Interviews')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Scheduled Candidate Interviews</h1>
            <p class="text-muted fs-7 mb-0">Manage interview schedules, panel assignments, and candidate evaluation rounds.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('recruitment-applications.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Candidate Pipeline
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleInterviewModal">
                <i class="fa-solid fa-plus me-1"></i> Schedule New Interview
            </button>
        </div>
    </div>

    <!-- Interviews Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('recruitment-interviews.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search candidate name or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('recruitment-interviews.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4 text-nowrap" style="min-width: 195px;">Actions</th>
                            <th>Candidate</th>
                            <th>Interview Date & Time</th>
                            <th>Mode / Location</th>
                            <th>Interviewer Panel</th>
                            <th>Offered CTC</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interviews as $itv)
                            <tr>
                                <td class="ps-4 text-nowrap">
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        <!-- View Details Button -->
                                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 fs-8 rounded-2" data-bs-toggle="modal" data-bs-target="#viewInterviewModal{{ $itv->job_interview_id }}" title="View Interview Details">
                                            <i class="fa-solid fa-eye me-1"></i> View
                                        </button>

                                        <!-- Convert to Employee Action -->
                                        @if(in_array(strtolower($itv->status), ['confirmed', 'selected', 'offeraccepted']) && $itv->convert_to_employee == 0)
                                            <form method="POST" action="{{ route('recruitment-interviews.convert', $itv->job_interview_id) }}" class="d-inline" onsubmit="return confirm('Convert candidate {{ $itv->jobApplication->candidate_name ?? '' }} to active employee?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success py-1 px-2 fs-8 rounded-2" title="Convert to Active Employee">
                                                    <i class="fa-solid fa-user-plus me-1"></i> Convert
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Status Change Dropdown -->
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-sm btn-outline-secondary py-1 px-2 fs-8 dropdown-toggle rounded-2" type="button" data-bs-toggle="dropdown">
                                                Status
                                            </button>
                                            <ul class="dropdown-menu fs-8 shadow border-subtle">
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-interviews.status', $itv->job_interview_id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="dropdown-item text-success"><i class="fa-solid fa-check-circle me-2"></i> Confirmed</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-interviews.status', $itv->job_interview_id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="selected">
                                                        <button type="submit" class="dropdown-item text-primary"><i class="fa-solid fa-user-check me-2"></i> Selected</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#nextRoundModal{{ $itv->job_interview_id }}">
                                                        <i class="fa-solid fa-forward me-2"></i> Next Round Schedule...
                                                    </button>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-interviews.status', $itv->job_interview_id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="onhold">
                                                        <button type="submit" class="dropdown-item text-warning"><i class="fa-solid fa-pause-circle me-2"></i> On Hold</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-interviews.status', $itv->job_interview_id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-ban me-2"></i> Rejected</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-gray-900">{{ $itv->jobApplication->candidate_name ?? 'Candidate' }}</div>
                                    <div class="fs-9 text-muted">{{ $itv->jobApplication->email ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium text-gray-800">{{ $itv->formatted_interview_date }}</span>
                                    @if(!empty($itv->next_round_date))
                                        <div class="fs-9 text-info fw-semibold mt-1">
                                            <i class="fa-solid fa-forward me-1"></i>Next: {{ date('M d, Y', strtotime($itv->next_round_date)) }}
                                        </div>
                                    @endif
                                    <div class="fs-9 text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $itv->interview_time }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary text-uppercase fs-9">{{ $itv->interview_mode }}</span>
                                    <div class="fs-9 text-muted">{{ $itv->interview_place ?? 'Online Meeting' }}</div>
                                </td>
                                <td>
                                    @php
                                        $panelists = $itv->interviewer_list;
                                    @endphp
                                    @if($panelists->isNotEmpty())
                                        @foreach($panelists as $pan)
                                            <div class="mb-1">
                                                <span class="fw-bold text-body-emphasis fs-8">{{ $pan->first_name }} {{ $pan->last_name }}</span>
                                                @if(!empty($pan->employee_id))
                                                    <span class="badge bg-secondary-subtle text-body-secondary font-mono fs-9">ID: {{ $pan->employee_id }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="fw-bold text-body-emphasis fs-8">{{ $itv->interviewer ? ($itv->interviewer->first_name . ' ' . $itv->interviewer->last_name) : ($itv->added_by ?? 'Recruiter') }}</div>
                                        @if(!empty($itv->interviewer?->employee_id))
                                            <span class="badge bg-secondary-subtle text-body-secondary font-mono fs-9">ID: {{ $itv->interviewer->employee_id }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <span class="font-monospace text-success fw-bold">{{ $itv->offered_ctc ?? '--' }}</span>
                                </td>
                                <td class="pe-4">
                                    <span class="badge {{ $itv->status_badge_class }}">
                                        {{ $itv->status_label }}
                                    </span>
                                    @if($itv->convert_to_employee != 0)
                                        <div class="mt-1"><span class="badge badge-light-success fs-9"><i class="fa-solid fa-user-check me-1"></i>Converted</span></div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-calendar-xmark fs-2 mb-2 d-block text-muted"></i>
                                    No scheduled candidate interviews found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($interviews->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $interviews->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Dynamic Modals for Row Actions -->
@foreach($interviews as $itv)
    <!-- Modal: Schedule Next Round -->
    <div class="modal fade" id="nextRoundModal{{ $itv->job_interview_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="POST" action="{{ route('recruitment-interviews.status', $itv->job_interview_id) }}">
                @csrf
                <input type="hidden" name="status" value="nextround">
                <div class="modal-content text-start">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold text-body-emphasis">
                            <i class="fa-solid fa-forward me-2 text-info"></i> Schedule Next Round: {{ $itv->jobApplication->candidate_name ?? 'Candidate' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any() && old('next_round_interview_id') == $itv->job_interview_id)
                            <div class="alert alert-danger p-2 fs-8 mb-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="next_round_interview_id" value="{{ $itv->job_interview_id }}">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold">Next Round Date <span class="text-danger">*</span></label>
                                <input type="date" name="next_round_date" class="form-control form-control-sm" required value="{{ old('next_round_date', !empty($itv->next_round_date) ? $itv->next_round_date : date('Y-m-d', strtotime('+1 day'))) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold">Next Round Time <span class="text-danger">*</span></label>
                                <input type="time" name="interview_time" class="form-control form-control-sm" required value="{{ old('interview_time', $itv->interview_time ?? '11:00') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Next Round Interview Panelists (Multi-Select)</label>
                            <select name="interviewers_id[]" class="form-select form-select-sm select-search" data-control="select2" data-placeholder="Select Interviewers..." multiple>
                                @foreach($interviewers as $emp)
                                    <option value="{{ $emp->user_id }}" {{ in_array($emp->user_id, explode(',', (string) $itv->interviewers_id)) ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} @if(!empty($emp->employee_id)) (ID: {{ $emp->employee_id }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Next Round Evaluation Notes / Remarks</label>
                            <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="Key focus areas or evaluation criteria for the next round...">{{ old('remarks', $itv->remarks) }}</textarea>
                        </div>

                        <!-- Email Notification Settings & Recipient Selection -->
                        <div class="card border border-info-subtle bg-body-tertiary p-3 rounded-3 mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-bold fs-8 text-info">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Send Next Round Email Notification
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="send_email_notification" id="sendEmailNextSwitch{{ $itv->job_interview_id }}" value="1" checked>
                                    <label class="form-check-label fs-9 text-muted fw-semibold" for="sendEmailNextSwitch{{ $itv->job_interview_id }}">Enable Mail</label>
                                </div>
                            </div>
                            <div class="alert alert-info py-2 px-3 fs-9 mb-2">
                                <i class="fa-solid fa-circle-info me-1"></i> Mail info: Invitation notice will be sent with updated date & venue details.
                            </div>
                            <div class="row g-2 pt-1 border-top border-subtle">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notify_candidate" id="notifyCandNextCheck{{ $itv->job_interview_id }}" value="1" checked>
                                        <label class="form-check-label fs-8 text-body-emphasis" for="notifyCandNextCheck{{ $itv->job_interview_id }}">
                                            <i class="fa-solid fa-user me-1 text-success"></i> Candidate: {{ $itv->jobApplication->email ?? 'N/A' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notify_interviewers" id="notifyPanNextCheck{{ $itv->job_interview_id }}" value="1" checked>
                                        <label class="form-check-label fs-8 text-body-emphasis" for="notifyPanNextCheck{{ $itv->job_interview_id }}">
                                            <i class="fa-solid fa-users me-1 text-info"></i> Interview Panelists (CC)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- WYSIWYG Rich Text Mail Preview & Editor -->
                            <div class="mt-3 pt-2 border-top border-subtle">
                                <button type="button" class="btn btn-sm btn-outline-info py-1 px-3 fs-9 rounded-2 mb-2" data-bs-toggle="collapse" data-bs-target="#editMailContentNext{{ $itv->job_interview_id }}">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Preview & Edit Invitation Mail (WYSIWYG Editor)
                                </button>
                                <div class="collapse show" id="editMailContentNext{{ $itv->job_interview_id }}">
                                    <div class="card card-body bg-body p-3 border">
                                        <div class="mb-3">
                                            <label class="form-label fs-9 fw-semibold text-body-secondary mb-1">Custom Email Subject</label>
                                            <input type="text" name="custom_email_subject" class="form-control form-control-sm fs-8" value="{{ old('custom_email_subject') }}" placeholder="Default: [Next Round Interview] {{ $itv->jobApplication->candidate_name ?? '' }}">
                                        </div>
                                        <div class="mb-0">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <label class="form-label fs-9 fw-semibold text-body-secondary mb-0">Email Message Body (WYSIWYG Editor)</label>
                                                <span class="fs-9 text-muted"><i class="fa-solid fa-wand-magic-sparkles me-1 text-info"></i> Editable Live Preview</span>
                                            </div>

                                            <!-- WYSIWYG Toolbar -->
                                            <div class="wysiwyg-toolbar border border-bottom-0 rounded-top bg-body-tertiary p-2 d-flex flex-wrap align-items-center gap-1">
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Bold" onclick="execWysiwygCmd('bold', this)"><i class="fa-solid fa-bold"></i></button>
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Italic" onclick="execWysiwygCmd('italic', this)"><i class="fa-solid fa-italic"></i></button>
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Underline" onclick="execWysiwygCmd('underline', this)"><i class="fa-solid fa-underline"></i></button>
                                                <div class="vr mx-1"></div>
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Bullet List" onclick="execWysiwygCmd('insertUnorderedList', this)"><i class="fa-solid fa-list-ul"></i></button>
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Numbered List" onclick="execWysiwygCmd('insertOrderedList', this)"><i class="fa-solid fa-list-ol"></i></button>
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Heading" onclick="execWysiwygCmd('formatBlock', this, '<h3>')"><i class="fa-solid fa-heading"></i></button>
                                                <div class="vr mx-1"></div>
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Insert Link" onclick="insertWysiwygLink(this)"><i class="fa-solid fa-link"></i></button>
                                                <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Clear Formatting" onclick="execWysiwygCmd('removeFormat', this)"><i class="fa-solid fa-eraser"></i></button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary border py-1 px-2 ms-auto" title="Reset Default Template" onclick="resetWysiwygEditor(this)">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Template
                                                </button>
                                            </div>

                                            <!-- WYSIWYG Canvas -->
                                            @php
                                                $nextDefaultMsg = $defaultTemplate->message ?? '<p>Dear <strong>{candidate_name}</strong>,</p><p>We are pleased to invite you for the Next Round interview regarding your application for <strong>{job_title}</strong>.</p><div style="background: #f1f5f9; border-left: 4px solid #2563eb; padding: 16px; margin: 20px 0; border-radius: 4px;"><p style="margin: 0 0 8px 0;"><strong>Date:</strong> {interview_date}</p><p style="margin: 0 0 8px 0;"><strong>Time:</strong> {interview_time}</p><p style="margin: 0 0 8px 0;"><strong>Mode:</strong> {interview_mode}</p><p style="margin: 0 0 8px 0;"><strong>Venue / Link:</strong> {interview_place}</p><p style="margin: 0;"><strong>Interviewer Panel:</strong> {panelists}</p></div><p><strong>Instructions / Remarks:</strong></p><p style="background: #fafafa; border-left: 3px solid #cbd5e1; padding: 10px 15px; font-style: italic; color: #334155;">{remarks}</p><p style="margin-top: 25px;">Please confirm your availability for this interview schedule.</p><p>Best regards,<br><strong>Recruitment Team</strong></p>';
                                            @endphp

                                            <div class="wysiwyg-canvas border rounded-bottom p-3 bg-body fs-8 text-body" contenteditable="true" style="min-height: 220px; max-height: 380px; overflow-y: auto; line-height: 1.6;" data-default-template="{{ e($nextDefaultMsg) }}">
                                                {!! old('custom_email_body', $nextDefaultMsg) !!}
                                            </div>
                                            <textarea name="custom_email_body" class="wysiwyg-hidden-input d-none">{{ old('custom_email_body', $nextDefaultMsg) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info btn-sm text-white fw-bold">Update & Schedule Next Round</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: View Interview Details -->
    <div class="modal fade" id="viewInterviewModal{{ $itv->job_interview_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content text-start border-0 shadow-lg">
                <div class="modal-header bg-body-tertiary border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width:38px; height:38px;">
                            <i class="fa-solid fa-calendar-check fs-6"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis mb-0">Interview Details</h5>
                            <div class="fs-9 text-body-secondary">Candidate: {{ $itv->jobApplication->candidate_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Candidate Profile -->
                        <div class="col-md-6">
                            <div class="card border border-subtle bg-body-tertiary h-100 p-3 rounded-3">
                                <h6 class="fs-8 text-uppercase fw-bold text-primary mb-3"><i class="fa-solid fa-user me-2"></i>Candidate Profile</h6>
                                <div class="mb-2">
                                    <span class="fs-9 text-body-secondary d-block">Candidate Name</span>
                                    <span class="fw-bold text-body-emphasis fs-7">{{ $itv->jobApplication->candidate_name ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="fs-9 text-body-secondary d-block">Email Address</span>
                                    <span class="fw-medium text-body-emphasis fs-8">{{ $itv->jobApplication->email ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="fs-9 text-body-secondary d-block">Phone Number</span>
                                    <span class="fw-medium text-body-emphasis fs-8">{{ $itv->jobApplication->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-0">
                                    <span class="fs-9 text-body-secondary d-block">Applied Position</span>
                                    <span class="badge bg-primary-subtle text-primary fs-8">{{ $itv->jobApplication->job->job_title ?? 'General Position' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Venue -->
                        <div class="col-md-6">
                            <div class="card border border-subtle bg-body-tertiary h-100 p-3 rounded-3">
                                <h6 class="fs-8 text-uppercase fw-bold text-info mb-3"><i class="fa-solid fa-clock me-2"></i>Schedule & Venue</h6>
                                <div class="mb-2">
                                    <span class="fs-9 text-body-secondary d-block">Interview Date</span>
                                    <span class="fw-bold text-body-emphasis fs-7">{{ $itv->formatted_interview_date }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="fs-9 text-body-secondary d-block">Interview Time</span>
                                    <span class="fw-medium text-body-emphasis fs-8"><i class="fa-regular fa-clock me-1 text-primary"></i>{{ $itv->interview_time }}</span>
                                </div>
                                @if(!empty($itv->next_round_date))
                                    <div class="mb-2">
                                        <span class="fs-9 text-body-secondary d-block">Next Round Schedule</span>
                                        <span class="badge bg-info-subtle text-info fs-8"><i class="fa-solid fa-forward me-1"></i>{{ date('M d, Y', strtotime($itv->next_round_date)) }}</span>
                                    </div>
                                @endif
                                <div class="mb-0">
                                    <span class="fs-9 text-body-secondary d-block">Mode / Venue</span>
                                    <span class="fw-medium text-body-emphasis fs-8">{{ $itv->interview_mode }} ({{ $itv->interview_place ?? 'Online Meeting' }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Panelists & Status -->
                        <div class="col-12">
                            <div class="card border border-subtle p-3 rounded-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <span class="fs-9 text-body-secondary d-block mb-1">Assigned Interviewer Panelists</span>
                                        @php $panelists = $itv->interviewer_list; @endphp
                                        @if($panelists->isNotEmpty())
                                            @foreach($panelists as $pan)
                                                <div class="d-inline-flex align-items-center gap-1 me-2 mb-1">
                                                    <span class="badge bg-light border text-body-emphasis fs-8"><i class="fa-solid fa-user-check me-1 text-success"></i>{{ $pan->first_name }} {{ $pan->last_name }} @if(!empty($pan->employee_id))(ID: {{ $pan->employee_id }})@endif</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="fw-medium text-body-emphasis fs-8">{{ $itv->interviewer ? ($itv->interviewer->first_name . ' ' . $itv->interviewer->last_name) : ($itv->added_by ?? 'Recruiter') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-3 col-6">
                                        <span class="fs-9 text-body-secondary d-block mb-1">Offered CTC</span>
                                        <span class="font-monospace fw-bold text-success fs-7">{{ $itv->offered_ctc ?? '--' }}</span>
                                    </div>

                                    <div class="col-md-3 col-6">
                                        <span class="fs-9 text-body-secondary d-block mb-1">Current Status</span>
                                        <span class="badge {{ $itv->status_badge_class }} fs-8">{{ $itv->status_label }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Remarks -->
                        @if(!empty($itv->jobApplication->application_remarks))
                            <div class="col-12">
                                <div class="card border border-subtle bg-body-tertiary p-3 rounded-3 mb-3">
                                    <h6 class="fs-8 text-uppercase fw-bold text-primary mb-2"><i class="fa-solid fa-file-lines me-2"></i>Candidate Application Remarks</h6>
                                    <p class="mb-0 fs-8 text-body-emphasis text-break" style="white-space: pre-wrap;">{{ $itv->jobApplication->application_remarks }}</p>
                                </div>
                            </div>
                        @endif

                        @if(!empty($itv->remarks) || !empty($itv->description))
                            <div class="col-12">
                                <div class="card border border-subtle bg-body-tertiary p-3 rounded-3">
                                    <h6 class="fs-8 text-uppercase fw-bold text-body-secondary mb-2"><i class="fa-solid fa-comment-dots me-2"></i>Evaluation Notes & Remarks</h6>
                                    <p class="mb-0 fs-8 text-body-emphasis text-break" style="white-space: pre-wrap;">{{ $itv->remarks ?? $itv->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer bg-body-tertiary border-top py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

        @if($interviews->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $interviews->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Schedule New Interview -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('recruitment-interviews.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Schedule Candidate Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3 p-3 fs-8 border-danger-subtle" role="alert">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fa-solid fa-circle-exclamation me-2 fs-6 text-danger"></i>
                                <strong class="text-danger">Scheduling Error:</strong>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Select Candidate <span class="text-danger">*</span></label>
                        <select name="application_id" class="form-select form-select-sm select-search" data-control="select2" data-placeholder="Search Candidate..." required>
                            <option value=""></option>
                            @foreach($applications as $app)
                                <option value="{{ $app->application_id }}" {{ old('application_id') == $app->application_id ? 'selected' : '' }}>
                                    {{ $app->candidate_name }} ({{ $app->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interview Mode <span class="text-danger">*</span></label>
                            <select name="interview_mode" class="form-select form-select-sm" required>
                                <option value="Online Video Call" {{ old('interview_mode', 'Online Video Call') == 'Online Video Call' ? 'selected' : '' }}>Online Video Call (Google Meet/Teams)</option>
                                <option value="In-Person Office" {{ old('interview_mode') == 'In-Person Office' ? 'selected' : '' }}>In-Person Office Interview</option>
                                <option value="Telephonic" {{ old('interview_mode') == 'Telephonic' ? 'selected' : '' }}>Telephonic Screening</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interviewer Panelists (Multi-Select)</label>
                            <select name="interviewers_id[]" class="form-select form-select-sm select-search" data-control="select2" data-placeholder="Select Interviewers..." multiple>
                                @foreach($interviewers as $emp)
                                    <option value="{{ $emp->user_id }}" {{ (is_array(old('interviewers_id')) && in_array($emp->user_id, old('interviewers_id'))) ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} @if(!empty($emp->employee_id)) (ID: {{ $emp->employee_id }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interview Date <span class="text-danger">*</span></label>
                            <input type="date" name="interview_date" class="form-control form-control-sm" required value="{{ old('interview_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interview Time <span class="text-danger">*</span></label>
                            <input type="time" name="interview_time" class="form-control form-control-sm" required value="{{ old('interview_time', '11:00') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Meeting Link / Office Room</label>
                        <input type="text" name="interview_place" value="{{ old('interview_place') }}" class="form-control form-control-sm" placeholder="e.g. https://meet.google.com/abc-defg-hij or Room 302">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Candidate Application Remarks / Screening Notes</label>
                        <textarea name="application_remarks" class="form-control form-control-sm" rows="2" placeholder="Optional notes regarding candidate skills, screening summary, or initial feedback...">{{ old('application_remarks') }}</textarea>
                    </div>

                    <!-- Email Notification Settings & Recipient Selection -->
                    <div class="card border border-primary-subtle bg-body-tertiary p-3 rounded-3 mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="fw-bold fs-8 text-primary">
                                <i class="fa-solid fa-paper-plane me-1"></i> Email Notification Settings
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="send_email_notification" id="sendEmailSchedSwitch" value="1" checked>
                                <label class="form-check-label fs-9 text-muted fw-semibold" for="sendEmailSchedSwitch">Enable Mail</label>
                            </div>
                        </div>
                        <div class="row g-2 pt-1 border-top border-subtle">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="notify_candidate" id="notifyCandSchedCheck" value="1" checked>
                                    <label class="form-check-label fs-8 text-body-emphasis" for="notifyCandSchedCheck">
                                        <i class="fa-solid fa-user me-1 text-success"></i> Send to Candidate Email
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="notify_interviewers" id="notifyPanSchedCheck" value="1" checked>
                                    <label class="form-check-label fs-8 text-body-emphasis" for="notifyPanSchedCheck">
                                        <i class="fa-solid fa-users me-1 text-info"></i> Send to Interview Panelists (CC)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- WYSIWYG Rich Text Mail Preview & Editor -->
                        <div class="mt-3 pt-2 border-top border-subtle">
                            <button type="button" class="btn btn-sm btn-outline-info py-1 px-3 fs-9 rounded-2 mb-2" data-bs-toggle="collapse" data-bs-target="#editMailContentSched">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Preview & Edit Invitation Mail (WYSIWYG Editor)
                            </button>
                            <div class="collapse show" id="editMailContentSched">
                                <div class="card card-body bg-body p-3 border">
                                    <div class="mb-3">
                                        <label class="form-label fs-9 fw-semibold text-body-secondary mb-1">Custom Email Subject</label>
                                        <input type="text" name="custom_email_subject" class="form-control form-control-sm fs-8" value="{{ old('custom_email_subject') }}" placeholder="Default: [Interview Invitation] Candidate Name - Applied Position">
                                    </div>
                                    <div class="mb-0">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <label class="form-label fs-9 fw-semibold text-body-secondary mb-0">Email Message Body (WYSIWYG Editor)</label>
                                            <span class="fs-9 text-muted"><i class="fa-solid fa-wand-magic-sparkles me-1 text-primary"></i> Editable Live Preview</span>
                                        </div>

                                        <!-- WYSIWYG Toolbar -->
                                        <div class="wysiwyg-toolbar border border-bottom-0 rounded-top bg-body-tertiary p-2 d-flex flex-wrap align-items-center gap-1">
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Bold" onclick="execWysiwygCmd('bold', this)"><i class="fa-solid fa-bold"></i></button>
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Italic" onclick="execWysiwygCmd('italic', this)"><i class="fa-solid fa-italic"></i></button>
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Underline" onclick="execWysiwygCmd('underline', this)"><i class="fa-solid fa-underline"></i></button>
                                            <div class="vr mx-1"></div>
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Bullet List" onclick="execWysiwygCmd('insertUnorderedList', this)"><i class="fa-solid fa-list-ul"></i></button>
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Numbered List" onclick="execWysiwygCmd('insertOrderedList', this)"><i class="fa-solid fa-list-ol"></i></button>
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Heading" onclick="execWysiwygCmd('formatBlock', this, '<h3>')"><i class="fa-solid fa-heading"></i></button>
                                            <div class="vr mx-1"></div>
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Insert Link" onclick="insertWysiwygLink(this)"><i class="fa-solid fa-link"></i></button>
                                            <button type="button" class="btn btn-xs btn-light border py-1 px-2" title="Clear Formatting" onclick="execWysiwygCmd('removeFormat', this)"><i class="fa-solid fa-eraser"></i></button>
                                            <button type="button" class="btn btn-xs btn-outline-secondary border py-1 px-2 ms-auto" title="Reset Default Template" onclick="resetWysiwygEditor(this)">
                                                <i class="fa-solid fa-rotate-left me-1"></i> Reset Template
                                            </button>
                                        </div>

                                        <!-- WYSIWYG Canvas -->
                                        @php
                                            $defaultMsg = $defaultTemplate->message ?? '<p>Dear <strong>{candidate_name}</strong>,</p><p>We are pleased to invite you for an interview regarding your application for <strong>{job_title}</strong>.</p><div style="background: #f1f5f9; border-left: 4px solid #2563eb; padding: 16px; margin: 20px 0; border-radius: 4px;"><p style="margin: 0 0 8px 0;"><strong>Date:</strong> {interview_date}</p><p style="margin: 0 0 8px 0;"><strong>Time:</strong> {interview_time}</p><p style="margin: 0 0 8px 0;"><strong>Mode:</strong> {interview_mode}</p><p style="margin: 0 0 8px 0;"><strong>Venue / Link:</strong> {interview_place}</p><p style="margin: 0;"><strong>Interviewer Panel:</strong> {panelists}</p></div><p><strong>Instructions / Remarks:</strong></p><p style="background: #fafafa; border-left: 3px solid #cbd5e1; padding: 10px 15px; font-style: italic; color: #334155;">{remarks}</p><p style="margin-top: 25px;">Please confirm your availability for this interview schedule.</p><p>Best regards,<br><strong>Recruitment Team</strong></p>';
                                        @endphp

                                        <div class="wysiwyg-canvas border rounded-bottom p-3 bg-body fs-8 text-body" contenteditable="true" style="min-height: 220px; max-height: 380px; overflow-y: auto; line-height: 1.6;" data-default-template="{{ e($defaultMsg) }}">
                                            {!! old('custom_email_body', $defaultMsg) !!}
                                        </div>
                                        <textarea name="custom_email_body" class="wysiwyg-hidden-input d-none">{{ old('custom_email_body', $defaultMsg) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Schedule Interview</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    function execWysiwygCmd(cmd, btn, value) {
        var $canvas = $(btn).closest('.card-body, .modal-body').find('.wysiwyg-canvas');
        if ($canvas.length) {
            $canvas.focus();
            document.execCommand(cmd, false, value || null);
            syncWysiwygContent($canvas);
        }
    }

    function insertWysiwygLink(btn) {
        var url = prompt("Enter Hyperlink URL (e.g. https://meet.google.com/xyz):", "https://");
        if (url) {
            execWysiwygCmd('createLink', btn, url);
        }
    }

    function syncWysiwygContent($canvas) {
        var html = $canvas.html();
        var $textarea = $canvas.siblings('.wysiwyg-hidden-input');
        if ($textarea.length) {
            $textarea.val(html);
        }
    }

    function resetWysiwygEditor(btn) {
        var $box = $(btn).closest('.card-body, .modal-body');
        var $canvas = $box.find('.wysiwyg-canvas');
        var defaultHtml = $canvas.attr('data-default-template') || '';
        if (defaultHtml) {
            $canvas.html(defaultHtml);
            syncWysiwygContent($canvas);
        }
    }

    (function() {
        function initModalSelect2($modal) {
            if (typeof $.fn.select2 !== 'undefined' && $modal && $modal.length) {
                $modal.find('.select-search').each(function() {
                    var $s = $(this);
                    if ($s.data('select2')) {
                        try { $s.select2('destroy'); } catch(e) {}
                    }
                    $s.select2({
                        width: '100%',
                        placeholder: $s.attr('data-placeholder') || 'Search & Select...',
                        allowClear: true,
                        dropdownParent: $modal
                    });
                });
            }
        }

        $(document).ready(function() {
            $(document).on('keyup blur paste input', '.wysiwyg-canvas', function() {
                syncWysiwygContent($(this));
            });

            $(document).on('submit', 'form', function() {
                $(this).find('.wysiwyg-canvas').each(function() {
                    syncWysiwygContent($(this));
                });
            });

            @if($errors->any())
                var oldNextRoundId = @json(old('next_round_interview_id'));
                if (oldNextRoundId) {
                    var modalEl = document.getElementById('nextRoundModal' + oldNextRoundId);
                    if (modalEl) {
                        var nrModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        nrModal.show();
                    }
                } else {
                    var modalEl = document.getElementById('scheduleInterviewModal');
                    if (modalEl) {
                        var intvModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        intvModal.show();
                    }
                }
            @endif
        });

        document.addEventListener('shown.bs.modal', function(e) {
            if (e.target) {
                initModalSelect2($(e.target));
            }
        });
    })();
</script>
@endpush
