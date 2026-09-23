@extends('layouts.app')

@section('title', 'Employee Separation & Exit Formalities')

@section('content')
@php
    $hasResignation = !empty($resignation);
    $statusText = $hasResignation ? ($resignation->status ?? 'Pending Review') : 'Not Initiated';
    
    // Count cleared stages
    $clearedCount = 0;
    if ($hasResignation) {
        if ((int) $resignation->manager_status === 1) $clearedCount++;
        if ((int) $resignation->it_status === 1) $clearedCount++;
        if ((int) $resignation->account_status === 1) $clearedCount++;
        if ((int) $resignation->hr_status === 1) $clearedCount++;
    }
    $progressPercent = $hasResignation ? ($clearedCount * 25) : 0;
    
    // Target LWD
    $confirmedLwd = $hasResignation && !empty($resignation->resignation_date)
        ? \Carbon\Carbon::parse($resignation->resignation_date)
        : $calculatedLwd;

    $daysToLwd = \Carbon\Carbon::today()->diffInDays($confirmedLwd, false);
@endphp

<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-solid fa-user-shield me-1"></i> Self-Service Portal
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Separation & Offboarding Hub</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Separation & Exit Clearance</h1>
            <p class="text-body-secondary fs-7 mb-0">Submit resignation notice, track multi-departmental clearance milestones, and access official relieving documentation.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($hasResignation && (int) $resignation->hr_status === 1)
                <a href="{{ route('my-portal.resignation.relieving_letter', $resignation->resignation_id) }}" class="btn btn-success btn-sm fw-semibold shadow-xs">
                    <i class="fa-solid fa-file-pdf me-1.5"></i> Relieving Letter
                </a>
                <a href="{{ route('my-portal.resignation.experience_certificate', $resignation->resignation_id) }}" class="btn btn-outline-success btn-sm fw-semibold">
                    <i class="fa-solid fa-award me-1.5"></i> Experience Certificate
                </a>
            @endif

            @if($hasResignation)
                <button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#exitFormModal">
                    <i class="fa-solid fa-file-signature me-1.5"></i> Submit Exit & No-Dues Form
                </button>
            @endif
        </div>
    </div>

    <!-- Metric KPI Cards (Airy & Elevated: DENSITY: 3, MOTION: 4, VARIANCE: 6) -->
    <div class="row g-3 mb-4">
        <!-- Separation Status -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Separation State</span>
                        <div class="stat-icon-wrapper rounded-2 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-right-from-bracket fs-7"></i>
                        </div>
                    </div>
                    @if(!$hasResignation)
                        <h4 class="h3 fw-bold text-body-emphasis mb-1">Active</h4>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill fs-9">
                            <i class="fa-solid fa-circle-check me-1"></i> Active Employment
                        </span>
                    @elseif($resignation->status === 'Completed' || $resignation->status === 'Relieved')
                        <h4 class="h3 fw-bold text-success mb-1">Relieved</h4>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill fs-9">
                            <i class="fa-solid fa-circle-check me-1"></i> Offboarding Complete
                        </span>
                    @elseif($resignation->status === 'Approved')
                        <h4 class="h3 fw-bold text-primary mb-1">Approved</h4>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-9">
                            <i class="fa-solid fa-thumbs-up me-1"></i> Manager Accepted
                        </span>
                    @else
                        <h4 class="h3 fw-bold text-warning mb-1">In Review</h4>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5 rounded-pill fs-9">
                            <i class="fa-solid fa-clock me-1"></i> Notice Submitted
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contractual Notice Period -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Notice Policy</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-business-time fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-emphasis mb-1">{{ $employee->notice_period_months ?? 1 }} {{ Str::plural('Month', $employee->notice_period_months ?? 1) }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Employment policy obligation</p>
                </div>
            </div>
        </div>

        <!-- Last Working Day (LWD) -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">{{ $hasResignation ? 'Confirmed LWD' : 'Calculated LWD' }}</span>
                        <div class="stat-icon-wrapper rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-calendar-day fs-7"></i>
                        </div>
                    </div>
                    <h4 class="h3 fw-bold text-body-emphasis mb-1">{{ $confirmedLwd->format('d M, Y') }}</h4>
                    @if($hasResignation)
                        @if($daysToLwd > 0)
                            <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2 py-0.5 fs-9">
                                <i class="fa-regular fa-clock me-1 text-primary"></i> {{ $daysToLwd }} {{ Str::plural('Day', $daysToLwd) }} Remaining
                            </span>
                        @elseif($daysToLwd === 0)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 fs-9">
                                <i class="fa-solid fa-flag-checkered me-1"></i> Today is your LWD
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-0.5 fs-9">
                                <i class="fa-solid fa-calendar-check me-1"></i> Completed
                            </span>
                        @endif
                    @else
                        <p class="fs-9 text-body-secondary mb-0">Standard timeline if initiated today</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Clearance Milestones -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Clearance Status</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-clipboard-check fs-7"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-1">
                        <h3 class="h2 fw-bold text-body-emphasis mb-0">{{ $clearedCount }}/4</h3>
                        <span class="fs-9 text-body-secondary fw-semibold">Stages Cleared ({{ $progressPercent }}%)</span>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $progressPercent }}%;" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Separation Studio: 2 Columns (5 cols left, 7 cols right) -->
    <div class="row g-4">
        <!-- Left Column: Resignation Application / Active Notice Status -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 bg-body h-100">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-file-signature fs-7"></i>
                        </div>
                        <div>
                            <h5 class="card-title fw-bold text-body-emphasis mb-0 fs-7">Resignation Notice Initiation</h5>
                            <span class="fs-9 text-body-secondary">Formal notice submission & last working day telemetry</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 fs-8">
                    @if(!$hasResignation)
                        <!-- Automated LWD Calculator Callout Banner -->
                        <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle mb-4">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fa-solid fa-calculator text-primary fs-6"></i>
                                <span class="fw-bold text-primary fs-8">Automated Notice & LWD Calculator</span>
                            </div>
                            <div class="text-body-emphasis fs-9 line-height-base">
                                Mandatory Notice Period: <strong>{{ $employee->notice_period_months ?? 1 }} Month(s)</strong><br>
                                Standard Calculated LWD: <strong id="calculatedLwdDisplay" class="text-primary font-monospace">{{ $calculatedLwd->format('d M, Y') }}</strong>
                            </div>
                        </div>

                        <!-- Resignation Form (Rule 10, 12) -->
                        <form method="POST" action="{{ route('my-portal.resignation.store') }}" id="resignationForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Notice Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="notice_date" 
                                       id="notice_date_input" 
                                       class="form-control fs-8 @error('notice_date') is-invalid @enderror" 
                                       required 
                                       value="{{ old('notice_date', date('Y-m-d')) }}">
                                <div class="form-text fs-9 text-body-secondary">Date you are formally tendering your resignation (defaults to today).</div>
                                @error('notice_date')
                                    <div class="invalid-feedback fs-9">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Requested Last Working Day (LWD) <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="resignation_date" 
                                       id="resignation_date_input" 
                                       class="form-control fs-8 @error('resignation_date') is-invalid @enderror" 
                                       required 
                                       value="{{ old('resignation_date', $calculatedLwd->format('Y-m-d')) }}">
                                
                                <!-- Dynamic Shortfall Warning Badge -->
                                <div id="shortfallNoticeBadge" class="mt-2" style="display: none;">
                                    <div class="p-2.5 rounded-2 bg-warning-subtle text-warning-emphasis border border-warning-subtle fs-9 d-flex align-items-start gap-2">
                                        <i class="fa-solid fa-triangle-exclamation text-warning mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-warning-emphasis">Early Exit Requested:</strong> Notice shortfall of <span id="shortfallDaysCount" class="fw-bold text-danger">0</span> days. 
                                            Subject to management approval & Full & Final (FnF) salary buyout recovery.
                                        </div>
                                    </div>
                                </div>
                                @error('resignation_date')
                                    <div class="invalid-feedback fs-9">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-0">Reason for Resignation & Handover Notes <span class="text-danger">*</span></label>
                                    <span class="fs-9 text-body-secondary font-monospace" id="reasonCharCounter">0 / 2000</span>
                                </div>
                                <textarea name="reason" 
                                          id="resignation_reason_input"
                                          rows="5" 
                                          class="form-control fs-8 @error('reason') is-invalid @enderror" 
                                          required 
                                          maxlength="2000"
                                          oninput="updateReasonCounter(this)"
                                          placeholder="State clear reasons for your resignation and provide a brief handover overview...">{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback fs-9">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="p-3 rounded-2 bg-body-tertiary border mb-4 fs-9 text-body-secondary">
                                <i class="fa-solid fa-circle-info text-primary me-1"></i>
                                Once submitted, automated notifications are dispatched to your Reporting Manager and Human Resources to initiate clearance.
                            </div>

                            <div class="d-flex justify-content-end">
                                <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner -->
                                <button type="submit" class="btn btn-danger btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                                    <i class="fa-solid fa-paper-plane me-1.5"></i> Submit Resignation Notice
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Active Resignation Record Card -->
                        <div class="p-3.5 rounded-3 bg-body-tertiary border mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-9 fw-bold">
                                    <i class="fa-solid fa-lock me-1"></i> Active Resignation on File
                                </span>
                                <span class="fs-9 text-body-secondary font-monospace">Ref #RES-{{ $resignation->resignation_id }}</span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <span class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-0.5">Notice Tenders Date</span>
                                    <div class="fw-bold text-body-emphasis fs-8">
                                        <i class="fa-regular fa-calendar-check text-primary me-1"></i> <x-human-date :value="$resignation->notice_date" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <span class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-0.5">Target Last Day (LWD)</span>
                                    <div class="fw-bold text-danger fs-8">
                                        <i class="fa-regular fa-calendar-xmark text-danger me-1"></i> <x-human-date :value="$resignation->resignation_date" />
                                    </div>
                                </div>
                            </div>

                            @if($resignation->shortfall_days > 0)
                                <div class="p-2.5 rounded-2 bg-warning-subtle text-warning-emphasis border border-warning-subtle fs-9 d-flex align-items-center gap-2 mb-3">
                                    <i class="fa-solid fa-triangle-exclamation text-warning fs-8 flex-shrink-0"></i>
                                    <div>Early Exit Requested: <strong class="text-warning-emphasis">{{ $resignation->shortfall_days }} Shortfall Day(s)</strong> (FnF adjustment required).</div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <span class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Reason for Separation</span>
                                <div class="p-3 rounded-2 bg-body border text-body-emphasis line-height-base fs-8">
                                    "{!! $resignation->clean_reason !!}"
                                </div>
                            </div>

                            @if(!empty($resignation->exit_form))
                                <div class="p-2.5 rounded-2 bg-body border mb-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fa-solid fa-file-pdf fs-8"></i>
                                        </div>
                                        <div class="text-truncate">
                                            <span class="fs-9 fw-bold text-body-emphasis d-block text-truncate">Signed No-Dues Document</span>
                                            <span class="fs-9 text-body-secondary font-monospace">{{ $resignation->exit_form }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('uploads/resignations/' . $resignation->exit_form) }}" target="_blank" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="View / Download Document">
                                        <i class="fa-solid fa-arrow-up-right-from-square fs-9"></i>
                                    </a>
                                </div>
                            @endif

                            @if(!empty($resignation->created_at))
                                <div class="text-body-secondary fs-9">
                                    <i class="fa-regular fa-clock me-1"></i> Submitted to HR on {{ $resignation->created_at }}
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-info border-0 shadow-xs mb-0 fs-8 d-flex align-items-start gap-2">
                            <i class="fa-solid fa-circle-info text-info fs-6 mt-0.5"></i>
                            <div>
                                <strong>Separation Workflow Active:</strong> Your notice is currently progressing through the 4-stage departmental clearance on the right. Contact your HR Business Partner for changes.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: 4-Stage Sequential Department Clearance Workflow -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 bg-body h-100">
                <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-list-check fs-7"></i>
                        </div>
                        <div>
                            <h5 class="card-title fw-bold text-body-emphasis mb-0 fs-7">Department Clearance Milestones</h5>
                            <span class="fs-9 text-body-secondary">4-stage approval and No-Dues audit progression</span>
                        </div>
                    </div>

                    @if($hasResignation)
                        <button type="button" class="btn btn-outline-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#exitFormModal">
                            <i class="fa-solid fa-file-arrow-up me-1"></i> Exit Checklist
                        </button>
                    @endif
                </div>

                <div class="card-body p-4 fs-8">
                    @if($hasResignation)
                        <!-- Timeline Progression Tracker -->
                        <div class="separation-timeline">
                            <!-- Stage 1: Reporting Manager Clearance -->
                            @php 
                                $mgrHelper = $resignation->getStageStatusHelper((int) $resignation->manager_status); 
                                $isMgrCleared = (int) $resignation->manager_status === 1;
                            @endphp
                            <div class="timeline-item p-3 rounded-3 bg-body-tertiary border mb-3 transition-all hover-lift">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle {{ $isMgrCleared ? 'bg-success text-white' : 'bg-primary-subtle text-primary' }} d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 32px; height: 32px; min-width: 32px;">
                                            @if($isMgrCleared)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                1
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-bold text-body-emphasis fs-8 d-block">Stage 1: Reporting Manager Handover & Release</span>
                                            <span class="fs-9 text-body-secondary">
                                                Reviewer: <strong>{{ $resignation->managerPerson ? ($resignation->managerPerson->first_name . ' ' . $resignation->managerPerson->last_name) : 'Assigned Manager' }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="{{ $mgrHelper['class'] }} rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                        <i class="fa-solid {{ $mgrHelper['icon'] }} me-1"></i> {{ $mgrHelper['label'] }}
                                    </span>
                                </div>
                                @if(!empty($resignation->manager_comment))
                                    <div class="p-2.5 rounded-2 bg-body border text-body-emphasis mt-2 fs-9 line-height-base">
                                        <strong class="text-primary"><i class="fa-solid fa-comment-dots me-1"></i> Manager Remarks:</strong>
                                        <div class="mt-0.5">{!! $resignation->clean_manager_comment !!}</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Stage 2: IT Department Assets Clearance -->
                            @php 
                                $itHelper = $resignation->getStageStatusHelper((int) $resignation->it_status); 
                                $isItCleared = (int) $resignation->it_status === 1;
                            @endphp
                            <div class="timeline-item p-3 rounded-3 bg-body-tertiary border mb-3 transition-all hover-lift">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle {{ $isItCleared ? 'bg-success text-white' : 'bg-info-subtle text-info' }} d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 32px; height: 32px; min-width: 32px;">
                                            @if($isItCleared)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                2
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-bold text-body-emphasis fs-8 d-block">Stage 2: IT Assets & Hardware Clearance</span>
                                            <span class="fs-9 text-body-secondary">
                                                Reviewer: <strong>{{ $resignation->itPerson ? ($resignation->itPerson->first_name . ' ' . $resignation->itPerson->last_name) : 'IT Asset Custodian' }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="{{ $itHelper['class'] }} rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                        <i class="fa-solid {{ $itHelper['icon'] }} me-1"></i> {{ $itHelper['label'] }}
                                    </span>
                                </div>
                                @if(!empty($resignation->it_comment))
                                    <div class="p-2.5 rounded-2 bg-body border text-body-emphasis mt-2 fs-9 line-height-base">
                                        <strong class="text-info"><i class="fa-solid fa-comment-dots me-1"></i> IT Remarks:</strong>
                                        <div class="mt-0.5">{!! $resignation->clean_it_comment !!}</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Stage 3: Accounts Department FnF Settlement -->
                            @php 
                                $accHelper = $resignation->getStageStatusHelper((int) $resignation->account_status); 
                                $isAccCleared = (int) $resignation->account_status === 1;
                            @endphp
                            <div class="timeline-item p-3 rounded-3 bg-body-tertiary border mb-3 transition-all hover-lift">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle {{ $isAccCleared ? 'bg-success text-white' : 'bg-warning-subtle text-warning' }} d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 32px; height: 32px; min-width: 32px;">
                                            @if($isAccCleared)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                3
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-bold text-body-emphasis fs-8 d-block">Stage 3: Finance & Accounts (FnF) Settlement</span>
                                            <span class="fs-9 text-body-secondary">
                                                Reviewer: <strong>{{ $resignation->accountPerson ? ($resignation->accountPerson->first_name . ' ' . $resignation->accountPerson->last_name) : 'Finance & Payroll' }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="{{ $accHelper['class'] }} rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                        <i class="fa-solid {{ $accHelper['icon'] }} me-1"></i> {{ $accHelper['label'] }}
                                    </span>
                                </div>
                                @if(!empty($resignation->account_comment))
                                    <div class="p-2.5 rounded-2 bg-body border text-body-emphasis mt-2 fs-9 line-height-base">
                                        <strong class="text-warning"><i class="fa-solid fa-comment-dots me-1"></i> Accounts Remarks:</strong>
                                        <div class="mt-0.5">{!! $resignation->clean_account_comment !!}</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Stage 4: HR Final Clearance & Relieving Certificate -->
                            @php 
                                $hrHelper = $resignation->getStageStatusHelper((int) $resignation->hr_status); 
                                $isHrCleared = (int) $resignation->hr_status === 1;
                            @endphp
                            <div class="timeline-item p-3 rounded-3 bg-body-tertiary border transition-all hover-lift">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle {{ $isHrCleared ? 'bg-success text-white' : 'bg-purple-subtle text-purple' }} d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 32px; height: 32px; min-width: 32px;">
                                            @if($isHrCleared)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                4
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-bold text-body-emphasis fs-8 d-block">Stage 4: HR Final Clearance & Document Issuance</span>
                                            <span class="fs-9 text-body-secondary">
                                                Reviewer: <strong>{{ $resignation->hrPerson ? ($resignation->hrPerson->first_name . ' ' . $resignation->hrPerson->last_name) : 'HR Operations' }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="{{ $hrHelper['class'] }} rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                        <i class="fa-solid {{ $hrHelper['icon'] }} me-1"></i> {{ $hrHelper['label'] }}
                                    </span>
                                </div>
                                @if(!empty($resignation->hr_comment))
                                    <div class="p-2.5 rounded-2 bg-body border text-body-emphasis mt-2 fs-9 line-height-base">
                                        <strong class="text-success"><i class="fa-solid fa-comment-dots me-1"></i> HR Remarks:</strong>
                                        <div class="mt-0.5">{!! $resignation->clean_hr_comment !!}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Informational Pre-Resignation State -->
                        <div class="text-center py-5">
                            <div class="rounded-circle bg-body-tertiary p-4 d-inline-flex align-items-center justify-content-center mb-3 text-body-secondary">
                                <i class="fa-solid fa-sitemap fa-3x text-primary"></i>
                            </div>
                            <h5 class="fw-bold text-body-emphasis mb-1">Standard Separation Protocol</h5>
                            <p class="text-body-secondary fs-8 mb-4" style="max-width: 380px; margin: 0 auto;">
                                Once your resignation is initiated, it progresses sequentially across 4 departments for comprehensive No-Dues certification.
                            </p>
                            <div class="row g-2 text-start max-w-lg mx-auto">
                                <div class="col-6">
                                    <div class="p-2.5 rounded bg-body-tertiary border fs-9 text-body-secondary">
                                        <strong class="text-body-emphasis d-block"><i class="fa-solid fa-user-tie text-primary me-1"></i> 1. Manager</strong> Handover signoff
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2.5 rounded bg-body-tertiary border fs-9 text-body-secondary">
                                        <strong class="text-body-emphasis d-block"><i class="fa-solid fa-laptop text-info me-1"></i> 2. IT Dept</strong> Asset & ID return
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2.5 rounded bg-body-tertiary border fs-9 text-body-secondary">
                                        <strong class="text-body-emphasis d-block"><i class="fa-solid fa-money-bill-wave text-warning me-1"></i> 3. Finance</strong> FnF computation
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2.5 rounded bg-body-tertiary border fs-9 text-body-secondary">
                                        <strong class="text-body-emphasis d-block"><i class="fa-solid fa-award text-success me-1"></i> 4. HR Dept</strong> Relieving release
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- Modal: Submit Exit Questionnaire & Itemized Asset Checklist -->
<!-- ======================================================= -->
@if($hasResignation)
<div class="modal fade" id="exitFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg bg-body">
            <form method="POST" action="{{ route('my-portal.resignation.exit_form') }}" enctype="multipart/form-data" id="exitHandoverForm">
                @csrf
                <input type="hidden" name="resignation_id" value="{{ $resignation->resignation_id }}">
                
                <div class="modal-header border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-clipboard-check fs-6"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis mb-0">Exit Questionnaire & Itemized Asset Checklist</h5>
                            <span class="fs-9 text-body-secondary">Provide asset return confirmation and handover documentation</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 fs-8">
                    <!-- Rule 10: In-Modal Error Alert -->
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-3 d-flex align-items-center gap-2 py-2 px-3">
                            <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                            <div class="fs-9 flex-grow-1">
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    <!-- Asset Handover Checklist -->
                    <div class="mb-4">
                        <label class="form-label fs-8 fw-bold text-body-emphasis mb-2 d-block">Itemized Hardware & Access Asset Return</label>
                        <div class="row g-2 p-3 rounded-3 bg-body-tertiary border">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="asset_laptop" id="chkLaptop" value="1" checked>
                                    <label class="form-check-label fs-8 fw-medium text-body-emphasis" for="chkLaptop">
                                        Laptop / Desktop, Charger & Accessories
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="asset_idcard" id="chkIdCard" value="1" checked>
                                    <label class="form-check-label fs-8 fw-medium text-body-emphasis" for="chkIdCard">
                                        Employee ID Badge & RFID Access Card
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="asset_sim" id="chkSim" value="1">
                                    <label class="form-check-label fs-8 fw-medium text-body-emphasis" for="chkSim">
                                        Company SIM Card / Official Phone Handset
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="asset_keys" id="chkKeys" value="1">
                                    <label class="form-check-label fs-8 fw-medium text-body-emphasis" for="chkKeys">
                                        Pedestal, Storage Keys & Cabin Key Return
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="asset_files" id="chkFiles" value="1" checked>
                                    <label class="form-check-label fs-8 fw-medium text-body-emphasis" for="chkFiles">
                                        Project Repositories, Documentation, API Keys & System Credentials Handover
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Signed No-Dues Document (Rule 4: Secure File Management) -->
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Signed No-Dues PDF / Document Upload</label>
                        @if(!empty($resignation->exit_form))
                            <div class="p-2 rounded bg-success-subtle border border-success-subtle mb-2 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                    <i class="fa-solid fa-file-pdf text-success fs-8"></i>
                                    <span class="fs-9 text-success fw-semibold text-truncate">Current File: {{ $resignation->exit_form }}</span>
                                </div>
                                <a href="{{ asset('uploads/resignations/' . $resignation->exit_form) }}" target="_blank" class="btn btn-xs btn-outline-success py-0.5 px-2 fs-9">
                                    <i class="fa-solid fa-eye me-1"></i> Preview
                                </a>
                            </div>
                        @endif
                        <input type="file" name="exit_form_file" class="form-control fs-8" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <div class="form-text fs-9 text-body-secondary">{{ !empty($resignation->exit_form) ? 'Select a new file only if you wish to replace the current document.' : 'Upload your countersigned physical clearance form or supporting handover documentation (Max 5MB).' }}</div>
                    </div>

                    <!-- Handover Remarks -->
                    <div class="mb-2">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Detailed Handover Summary & Remarks</label>
                        <textarea name="handover_summary" rows="3" class="form-control fs-8" placeholder="Summarize project file links, replacement contacts, knowledge transfer status..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top py-3 px-4 bg-body-tertiary d-flex justify-content-between align-items-center">
                    <span class="fs-9 text-body-secondary"><i class="fa-solid fa-shield-halved me-1"></i> Recorded on Employee Record</span>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner -->
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3 submit-loader" onclick="submitWithLoader(this)">
                            <i class="fa-solid fa-floppy-disk me-1.5"></i> Submit Exit & No-Dues Form
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    function updateReasonCounter(el) {
        const counter = document.getElementById('reasonCharCounter');
        if (counter && el) {
            counter.textContent = el.value.length + ' / 2000';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const noticeDateInput = document.getElementById('notice_date_input');
        const resignationDateInput = document.getElementById('resignation_date_input');
        const calculatedLwdDisplay = document.getElementById('calculatedLwdDisplay');
        const shortfallNoticeBadge = document.getElementById('shortfallNoticeBadge');
        const shortfallDaysCount = document.getElementById('shortfallDaysCount');
        const reasonInput = document.getElementById('resignation_reason_input');

        if (reasonInput) {
            updateReasonCounter(reasonInput);
        }

        const noticeMonths = {{ $employee->notice_period_months ?? 1 }};

        function recalculateLwd() {
            if (!noticeDateInput || !noticeDateInput.value) return;

            const noticeDate = new Date(noticeDateInput.value);
            if (isNaN(noticeDate.getTime())) return;

            // Add noticeMonths
            const expectedLwd = new Date(noticeDate);
            expectedLwd.setMonth(expectedLwd.getMonth() + noticeMonths);

            // Format Expected LWD
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            if (calculatedLwdDisplay) {
                calculatedLwdDisplay.innerText = expectedLwd.toLocaleDateString('en-GB', options);
            }

            // Check Shortfall against user picked LWD
            if (resignationDateInput && resignationDateInput.value) {
                const userLwd = new Date(resignationDateInput.value);
                if (!isNaN(userLwd.getTime()) && userLwd < expectedLwd) {
                    const diffTime = Math.abs(expectedLwd - userLwd);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays > 0) {
                        if (shortfallDaysCount) shortfallDaysCount.innerText = diffDays;
                        if (shortfallNoticeBadge) shortfallNoticeBadge.style.display = 'block';
                        return;
                    }
                }
            }

            if (shortfallNoticeBadge) {
                shortfallNoticeBadge.style.display = 'none';
            }
        }

        if (noticeDateInput) {
            noticeDateInput.addEventListener('change', recalculateLwd);
        }
        if (resignationDateInput) {
            resignationDateInput.addEventListener('change', recalculateLwd);
        }

        recalculateLwd();

        // Rule 10: Auto-reopen modal if validation errors exist
        @if(isset($errors) && $errors->any())
            const exitModalEl = document.getElementById('exitFormModal');
            if (exitModalEl && typeof bootstrap !== 'undefined') {
                const modalInstance = new bootstrap.Modal(exitModalEl);
                modalInstance.show();
            }
        @endif
    });
</script>
@endpush
@endsection
