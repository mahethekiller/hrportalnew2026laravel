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
                            <th class="ps-4">Candidate</th>
                            <th>Interview Date & Time</th>
                            <th>Mode / Location</th>
                            <th>Interviewer Panel</th>
                            <th>Offered CTC</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interviews as $itv)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900">{{ $itv->jobApplication->candidate_name ?? 'Candidate' }}</div>
                                    <div class="fs-9 text-muted">{{ $itv->jobApplication->email ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium text-gray-800">{{ $itv->formatted_interview_date }}</span>
                                    <div class="fs-9 text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $itv->interview_time }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary text-uppercase fs-9">{{ $itv->interview_mode }}</span>
                                    <div class="fs-9 text-muted">{{ $itv->interview_place ?? 'Online Meeting' }}</div>
                                </td>
                                <td>
                                    <span class="text-gray-800 fs-8">{{ $itv->interviewer ? ($itv->interviewer->first_name . ' ' . $itv->interviewer->last_name) : ($itv->added_by ?? 'Recruiter') }}</span>
                                </td>
                                <td>
                                    <span class="font-monospace text-success fw-bold">{{ $itv->offered_ctc ?? '--' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $itv->status_badge_class }}">
                                        {{ $itv->status_label }}
                                    </span>
                                    @if($itv->convert_to_employee != 0)
                                        <div class="mt-1"><span class="badge badge-light-success fs-9"><i class="fa-solid fa-user-check me-1"></i>Converted</span></div>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        <!-- Convert to Employee Action -->
                                        @if(in_array(strtolower($itv->status), ['confirmed', 'selected', 'offeraccepted']) && $itv->convert_to_employee == 0)
                                            <form method="POST" action="{{ route('recruitment-interviews.convert', $itv->job_interview_id) }}" class="d-inline" onsubmit="return confirm('Convert candidate {{ $itv->jobApplication->candidate_name ?? '' }} to active employee?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success py-1 px-2 fs-8" title="Convert to Active Employee">
                                                    <i class="fa-solid fa-user-plus me-1"></i> Convert to Staff
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Status Change Dropdown -->
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-sm btn-light-secondary py-1 px-2 fs-8 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Status
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end fs-8">
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
                                                    <form method="POST" action="{{ route('recruitment-interviews.status', $itv->job_interview_id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="nextround">
                                                        <button type="submit" class="dropdown-item text-info"><i class="fa-solid fa-forward me-2"></i> Next Round</button>
                                                    </form>
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

<!-- Modal: Schedule New Interview -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('recruitment-interviews.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Schedule Candidate Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Select Candidate <span class="text-danger">*</span></label>
                        <select name="application_id" class="form-select form-select-sm" required>
                            <option value="">Choose Candidate</option>
                            @foreach($applications as $app)
                                <option value="{{ $app->application_id }}">
                                    {{ $app->candidate_name }} ({{ $app->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interview Mode <span class="text-danger">*</span></label>
                            <select name="interview_mode" class="form-select form-select-sm" required>
                                <option value="Online Video Call">Online Video Call (Google Meet/Teams)</option>
                                <option value="In-Person Office">In-Person Office Interview</option>
                                <option value="Telephonic">Telephonic Screening</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interviewer Panelist</label>
                            <select name="interviewers_id" class="form-select form-select-sm">
                                <option value="">Select Interviewer</option>
                                @foreach($interviewers as $emp)
                                    <option value="{{ $emp->user_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interview Date <span class="text-danger">*</span></label>
                            <input type="date" name="interview_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Interview Time <span class="text-danger">*</span></label>
                            <input type="time" name="interview_time" class="form-control form-control-sm" required value="11:00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Meeting Link / Office Room</label>
                        <input type="text" name="interview_place" class="form-control form-control-sm" placeholder="e.g. https://meet.google.com/abc-defg-hij or Room 302">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Schedule Interview</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
