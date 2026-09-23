@extends('layouts.app')

@section('title', 'Recruitment Candidate Pipeline')

@php $errors = $errors ?? new \Illuminate\Support\ViewErrorBag; @endphp

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-user-gear me-1"></i> Talent Acquisition
                </span>
                <span class="text-body-secondary fs-9">• Hiring Funnel & Candidate Pipeline</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Recruitment Candidate Pipeline</h4>
            <p class="text-body-secondary fs-8 mb-0">Track applicant sourcing, screening stages, scheduled interviews, and hiring offers in real time.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('recruitment-interviews.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-calendar-check me-1.5 text-primary"></i> Scheduled Interviews
            </a>
            <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#createCandidateModal">
                <i class="fa-solid fa-user-plus me-1.5"></i> Submit Candidate Profile
            </button>
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Applicants -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Applicants</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $summary['total_applicants'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Cumulative candidate profiles</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Shortlisted Candidates -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Shortlisted</span>
                        <h3 class="fw-bolder text-info mb-1 mt-1">{{ $summary['shortlisted_count'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Screened & qualified for evaluation</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-tag fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Interviews Scheduled -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Interviews Active</span>
                        <h3 class="fw-bolder text-warning mb-1 mt-1">{{ $summary['interview_count'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Assessment rounds scheduled</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-comments fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Hired / Offered -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Hired / Offered</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $summary['hired_count'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Accepted job offers</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar & Search Bar -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('recruitment-applications.index') }}" class="row g-2 align-items-center">
                <!-- Search Input -->
                <div class="col-lg-4 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body text-body-secondary border-end-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-body text-body-emphasis border-start-0" placeholder="Search candidate name, email, phone, company..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Stage Filter -->
                <div class="col-lg-3 col-md-6">
                    <select name="status" class="form-select form-select-sm bg-body text-body-emphasis" onchange="this.form.submit()">
                        <option value="">All Pipeline Stages</option>
                        <option value="Applied" {{ request('status') === 'Applied' ? 'selected' : '' }}>Applied / New</option>
                        <option value="Shortlisted" {{ request('status') === 'Shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="Interview Scheduled" {{ request('status') === 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                        <option value="Hired" {{ request('status') === 'Hired' ? 'selected' : '' }}>Hired / Offered</option>
                        <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Target Job Requisition Filter -->
                <div class="col-lg-3 col-md-6">
                    <select name="job_id" class="form-select form-select-sm bg-body text-body-emphasis" onchange="this.form.submit()">
                        <option value="">All Job Openings</option>
                        @foreach($jobs as $jb)
                            <option value="{{ $jb->job_id }}" {{ request('job_id') == $jb->job_id ? 'selected' : '' }}>
                                {{ $jb->job_title }} ({{ $jb->job_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="col-lg-2 col-md-6 d-flex gap-1.5 justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold rounded-2 w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('status') || request()->filled('job_id'))
                        <a href="{{ route('recruitment-applications.index') }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Candidate Pipeline Data Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0">Candidate Roster</h5>
                    <span class="text-body-secondary fs-9">Applicant tracking records and screening statuses</span>
                </div>
            </div>
            <span class="badge bg-body text-body-secondary border px-2.5 py-1.5 rounded-pill fs-9 fw-semibold">
                {{ $applications->total() }} Candidates Found
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 170px;">Actions</th>
                            <th>Candidate</th>
                            <th>Target Requisition</th>
                            <th>Current Employer & Location</th>
                            <th>Experience</th>
                            <th>CTC Range</th>
                            <th class="pe-4 text-center" style="width: 150px;">Pipeline Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                            @php
                                $daysInStage = $app->created_at ? now()->diffInDays(\Carbon\Carbon::parse($app->created_at)) : 0;
                                $isStalled = ($daysInStage >= 7 && !in_array($app->application_status, ['Hired', 'Rejected']));
                            @endphp
                            <tr>
                                <!-- Column 1: Actions (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- View Details Dossier Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" data-bs-toggle="modal" data-bs-target="#viewCandidateModal{{ $app->application_id }}" title="View Candidate Profile">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                        <!-- Edit Candidate Profile Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" data-bs-toggle="modal" data-bs-target="#editCandidateModal{{ $app->application_id }}" title="Edit Candidate Profile">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Stage Transition Dropdown -->
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-sm btn-outline-secondary px-2.5 rounded-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Update Pipeline Stage">
                                                <i class="fa-solid fa-sliders"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-subtle fs-8">
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-applications.status', $app->application_id) }}" onsubmit="submitWithLoader(this.querySelector('button'))">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Shortlisted">
                                                        <button type="submit" class="dropdown-item py-1.5 text-primary">
                                                            <i class="fa-solid fa-user-check me-2"></i> Move to Shortlisted
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-applications.status', $app->application_id) }}" onsubmit="submitWithLoader(this.querySelector('button'))">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Interview Scheduled">
                                                        <button type="submit" class="dropdown-item py-1.5 text-warning">
                                                            <i class="fa-solid fa-calendar-check me-2"></i> Schedule Interview
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-applications.status', $app->application_id) }}" onsubmit="submitWithLoader(this.querySelector('button'))">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Hired">
                                                        <button type="submit" class="dropdown-item py-1.5 text-success">
                                                            <i class="fa-solid fa-award me-2"></i> Mark Hired / Offer
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider my-1"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('recruitment-applications.status', $app->application_id) }}" onsubmit="submitWithLoader(this.querySelector('button'))">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Rejected">
                                                        <button type="submit" class="dropdown-item py-1.5 text-danger">
                                                            <i class="fa-solid fa-ban me-2"></i> Mark Rejected
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Download Resume Icon Button -->
                                        @if(!empty($app->job_resume))
                                            <a href="{{ route('recruitment-applications.resume', $app->application_id) }}" target="_blank" class="btn btn-sm btn-outline-info px-2.5 rounded-2" title="Download Resume Document">
                                                <i class="fa-solid fa-file-arrow-down"></i>
                                            </a>
                                        @endif

                                        <!-- Email Candidate Direct Link -->
                                        @if(!empty($app->email))
                                            <a href="mailto:{{ $app->email }}?subject=Regarding your application for {{ urlencode($app->job->job_title ?? 'Job Opening') }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Email Candidate">
                                                <i class="fa-solid fa-envelope"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Candidate Profile Info -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-8" style="width: 36px; height: 36px; min-width: 36px;">
                                            {{ strtoupper(substr($app->candidate_name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis leading-tight">
                                                {{ $app->candidate_name }}
                                                @if($isStalled)
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-10 ms-1 px-1.5 py-0.5 rounded-pill" title="Candidate in stage for over 7 days">
                                                        <i class="fa-solid fa-hourglass-half me-1"></i>Stalled ({{ $daysInStage }}d)
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="fs-9 text-body-secondary">
                                                {{ $app->email }} &bull; <x-human-date :value="$app->created_at" />
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Applied Requisition -->
                                <td>
                                    <div class="fw-bold text-body-emphasis">
                                        {{ $app->job->job_title ?? 'General Requisition' }}
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                        <span class="badge bg-body text-body-secondary border font-monospace fs-10">
                                            {{ $app->job->job_code ?? 'JOB-GEN' }}
                                        </span>
                                        <span class="fs-9 text-body-secondary">
                                            {{ $app->department->department_name ?? 'General' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Current Employer & Location -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis">
                                        {{ $app->current_company ?: 'Not specified' }}
                                    </div>
                                    <div class="fs-9 text-body-secondary">
                                        <i class="fa-solid fa-location-dot me-1 text-primary"></i> {{ $app->current_location ?: 'Location N/A' }}
                                    </div>
                                </td>

                                <!-- Experience -->
                                <td>
                                    <span class="badge bg-body text-body-emphasis border px-2 py-1 rounded-2 fw-semibold">
                                        {{ $app->experience ?: 'Fresh' }}
                                    </span>
                                </td>

                                <!-- CTC Range -->
                                <td>
                                    <div class="fs-8 font-monospace text-body-emphasis">
                                        {{ $app->current_package ?: '—' }} <span class="text-body-secondary">/</span> <strong>{{ $app->expected_package ?: '—' }}</strong>
                                    </div>
                                    @if(!empty($app->notice_period))
                                        <div class="fs-9 text-body-secondary mt-0.5">
                                            <i class="fa-solid fa-business-time me-1"></i> {{ $app->notice_period }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Stage Status Badge -->
                                <td class="pe-4 text-center">
                                    <x-status-badge :status="$app->application_status ?? 'Applied'" :pulse="$isStalled" />
                                </td>
                            </tr>

                            <!-- Modal: Candidate Details Dossier -->
                            <div class="modal fade" id="viewCandidateModal{{ $app->application_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-body border-0 shadow">
                                        <div class="modal-header border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                                                    <i class="fa-solid fa-id-card"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">
                                                        Candidate Profile — {{ $app->candidate_name }}
                                                    </h5>
                                                    <span class="fs-9 text-body-secondary font-monospace">Application #{{ $app->application_id }} &bull; Stage: {{ $app->application_status ?? 'Applied' }}</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <!-- Candidate Overview Card -->
                                            <div class="p-3 bg-body-tertiary rounded-3 mb-4 border">
                                                <div class="row g-3">
                                                    <div class="col-sm-4">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Email Address</span>
                                                        <span class="fw-semibold text-body-emphasis fs-8">{{ $app->email }}</span>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Contact Phone</span>
                                                        <span class="fw-semibold text-body-emphasis fs-8">{{ $app->contact_no ?: 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Gender</span>
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-2 fs-9">{{ $app->gender ?? 'Male' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Requisition & Department -->
                                            <div class="row g-3 mb-4">
                                                <div class="col-sm-6">
                                                    <div class="p-3 bg-body-tertiary rounded-3 border h-100">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Target Job Opening</span>
                                                        <div class="fw-bold text-body-emphasis fs-7">{{ $app->job->job_title ?? 'General Requisition' }}</div>
                                                        <span class="badge bg-body text-body-secondary border font-monospace fs-9 mt-1">{{ $app->job->job_code ?? 'JOB-GEN' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="p-3 bg-body-tertiary rounded-3 border h-100">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Assigned Department</span>
                                                        <div class="fw-bold text-body-emphasis fs-7">{{ $app->department->department_name ?? 'General' }}</div>
                                                        @if(!empty($app->department?->company))
                                                            <div class="fs-9 text-body-secondary mt-1"><i class="fa-regular fa-building me-1"></i> Company: {{ $app->department->company->name }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Experience & Compensation Breakdown -->
                                            <div class="p-3 bg-body-tertiary rounded-3 mb-4 border">
                                                <div class="row g-3">
                                                    <div class="col-sm-3">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Current Employer</span>
                                                        <span class="fw-semibold text-body-emphasis fs-8">{{ $app->current_company ?: 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Experience</span>
                                                        <span class="badge bg-body text-body-emphasis border px-2 py-1 fs-9">{{ $app->experience ?: 'Fresh' }}</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Current CTC</span>
                                                        <span class="fw-semibold text-body-emphasis font-monospace fs-8">{{ $app->current_package ?: 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Expected CTC</span>
                                                        <span class="fw-bold text-primary font-monospace fs-8">{{ $app->expected_package ?: 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Additional Details -->
                                            <div class="row g-3 mb-4">
                                                <div class="col-sm-6">
                                                    <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Reason for Career Move</span>
                                                    <div class="p-3 bg-body-tertiary rounded-3 border text-body-emphasis fs-8 leading-relaxed">
                                                        {{ $app->change_reason ?: 'No specific career change reason provided.' }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">HR / Recruiter Remarks</span>
                                                    <div class="p-3 bg-body-tertiary rounded-3 border text-body-emphasis fs-8 leading-relaxed">
                                                        {{ $app->hr_remarks ?: 'No internal recruiter remarks recorded.' }}
                                                    </div>
                                                </div>
                                            </div>

                                            @if(!empty($app->application_remarks))
                                                <div class="mb-4">
                                                    <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Sourcing & Application Notes</span>
                                                    <div class="p-3 bg-body-tertiary rounded-3 border text-body-emphasis fs-8 leading-relaxed">
                                                        {{ $app->application_remarks }}
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Footer Metadata -->
                                            <div class="d-flex flex-wrap align-items-center justify-content-between pt-3 border-top fs-9 text-body-secondary gap-2">
                                                <div><i class="fa-solid fa-calendar me-1"></i> CV Sourced: <strong>{{ $app->date_cv_sourced ?? date('Y-m-d') }}</strong></div>
                                                <div><i class="fa-solid fa-user me-1"></i> Added By: <strong>{{ $app->creator_name }}</strong></div>
                                                <div><x-status-badge :status="$app->application_status ?? 'Applied'" /></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top bg-body-tertiary">
                                            @if(!empty($app->job_resume))
                                                <a href="{{ route('recruitment-applications.resume', $app->application_id) }}" target="_blank" class="btn btn-outline-primary btn-sm me-auto fw-semibold">
                                                    <i class="fa-solid fa-file-arrow-down me-1"></i> Download Candidate Resume
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal: Edit Candidate Profile -->
                            <div class="modal fade" id="editCandidateModal{{ $app->application_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-body border-0 shadow">
                                        <form method="POST" action="{{ route('recruitment-applications.update', $app->application_id) }}" enctype="multipart/form-data" onsubmit="submitWithLoader(this)">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="edit_application_id" value="{{ $app->application_id }}">

                                            <div class="modal-header border-bottom">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="p-2 rounded-2 bg-warning-subtle text-warning fs-8">
                                                        <i class="fa-solid fa-user-pen"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">
                                                            Edit Candidate Profile — {{ $app->candidate_name }}
                                                        </h5>
                                                        <span class="fs-9 text-body-secondary font-monospace">Application #{{ $app->application_id }}</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-4 text-start">
                                                <!-- Rule 10: In-Modal Validation Alerts -->
                                                @if(isset($errors) && $errors->any() && old('edit_application_id') == $app->application_id)
                                                    <div class="alert alert-danger border-0 shadow-xs mb-3 py-2 px-3 fs-8" role="alert">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="fa-solid fa-circle-exclamation me-1.5 text-danger"></i>
                                                            <strong class="text-danger">Validation Issues:</strong>
                                                        </div>
                                                        <ul class="mb-0 ps-3">
                                                            @foreach($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="candidate_name" value="{{ old('candidate_name', $app->candidate_name) }}" class="form-control form-control-sm bg-body text-body-emphasis" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Email Address <span class="text-danger">*</span></label>
                                                        <input type="email" name="email" value="{{ old('email', $app->email) }}" class="form-control form-control-sm bg-body text-body-emphasis" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Contact Phone</label>
                                                        <input type="text" name="contact_no" value="{{ old('contact_no', $app->contact_no) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Gender</label>
                                                        <select name="gender" class="form-select form-select-sm bg-body text-body-emphasis">
                                                            <option value="Male" {{ old('gender', $app->gender ?? 'Male') == 'Male' ? 'selected' : '' }}>Male</option>
                                                            <option value="Female" {{ old('gender', $app->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                            <option value="Other" {{ old('gender', $app->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Target Job Requisition <span class="text-danger">*</span></label>
                                                        <select name="job_id" class="form-select form-select-sm select-search bg-body text-body-emphasis" data-placeholder="Search Job Opening..." required>
                                                            <option value=""></option>
                                                            @foreach($jobs as $jb)
                                                                <option value="{{ $jb->job_id }}" {{ old('job_id', $app->job_id) == $jb->job_id ? 'selected' : '' }}>
                                                                    {{ $jb->job_title }} ({{ $jb->job_code }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Department</label>
                                                        <select name="department_id" class="form-select form-select-sm select-search bg-body text-body-emphasis" data-placeholder="Search Department...">
                                                            <option value=""></option>
                                                            @foreach($departments as $dept)
                                                                <option value="{{ $dept->department_id }}" {{ old('department_id', $app->department_id) == $dept->department_id ? 'selected' : '' }}>
                                                                    {{ $dept->department_name }} @if(!empty($dept->company)) (Company: {{ $dept->company->name }}) @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Experience (Years)</label>
                                                        <input type="text" name="experience" value="{{ old('experience', $app->experience) }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. 4.5 Years">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Current Employer / Company</label>
                                                        <input type="text" name="current_company" value="{{ old('current_company', $app->current_company) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Current Location</label>
                                                        <input type="text" name="current_location" value="{{ old('current_location', $app->current_location) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Current CTC</label>
                                                        <input type="text" name="current_package" value="{{ old('current_package', $app->current_package) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Expected CTC</label>
                                                        <input type="text" name="expected_package" value="{{ old('expected_package', $app->expected_package) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Notice Period</label>
                                                        <input type="text" name="notice_period" value="{{ old('notice_period', $app->notice_period) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Reason for Change</label>
                                                        <input type="text" name="change_reason" value="{{ old('change_reason', $app->change_reason) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">HR / Recruiter Remarks</label>
                                                        <input type="text" name="hr_remarks" value="{{ old('hr_remarks', $app->hr_remarks) }}" class="form-control form-control-sm bg-body text-body-emphasis">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Application Remarks / Sourcing Notes</label>
                                                    <textarea name="application_remarks" class="form-control form-control-sm bg-body text-body-emphasis" rows="2">{{ old('application_remarks', $app->application_remarks) }}</textarea>
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">
                                                        <i class="fa-solid fa-paperclip me-1 text-primary"></i> Replace Resume Document (PDF / DOCX)
                                                    </label>
                                                    <input type="file" name="job_resume" class="form-control form-control-sm bg-body text-body-emphasis" accept=".pdf,.doc,.docx">
                                                    @if(!empty($app->job_resume))
                                                        <div class="fs-9 text-success mt-1">
                                                            <i class="fa-solid fa-circle-check me-1"></i> Current file on file: {{ basename($app->job_resume) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="modal-footer border-top bg-body-tertiary">
                                                <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-warning fw-semibold px-3">
                                                    <i class="fa-solid fa-check me-1"></i> Update Candidate
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-user-slash" 
                                        title="No Candidates Found" 
                                        description="No candidate profiles match your specified search keywords or stage filters." 
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($applications->hasPages())
                <div class="card-footer border-top bg-transparent py-3 px-4">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Submit Candidate Profile -->
<div class="modal fade" id="createCandidateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-body border-0 shadow">
            <form method="POST" action="{{ route('recruitment-applications.store') }}" enctype="multipart/form-data" onsubmit="submitWithLoader(this)">
                @csrf
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Submit Candidate Profile</h5>
                            <span class="fs-9 text-body-secondary">Register new applicant for requisition evaluation</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 text-start">
                    <!-- Rule 10: In-Modal Validation Alerts -->
                    @if(isset($errors) && $errors->any() && !old('edit_application_id'))
                        <div class="alert alert-danger border-0 shadow-xs mb-3 py-2 px-3 fs-8" role="alert">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fa-solid fa-circle-exclamation me-1.5 text-danger"></i>
                                <strong class="text-danger">Submission Issues:</strong>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="candidate_name" value="{{ old('candidate_name') }}" class="form-control form-control-sm bg-body text-body-emphasis @error('candidate_name') is-invalid @enderror" required placeholder="e.g. Rahul Sharma">
                            @error('candidate_name') <div class="invalid-feedback fs-9">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm bg-body text-body-emphasis @error('email') is-invalid @enderror" required placeholder="rahul@example.com">
                            @error('email') <div class="invalid-feedback fs-9">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Contact Phone</label>
                            <input type="text" name="contact_no" value="{{ old('contact_no') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. +91 9876543210">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Gender</label>
                            <select name="gender" class="form-select form-select-sm bg-body text-body-emphasis">
                                <option value="Male" {{ old('gender', 'Male') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Target Job Requisition <span class="text-danger">*</span></label>
                            <select name="job_id" class="form-select form-select-sm select-search bg-body text-body-emphasis" data-placeholder="Search Job Opening..." required>
                                <option value=""></option>
                                @foreach($jobs as $jb)
                                    <option value="{{ $jb->job_id }}" {{ old('job_id') == $jb->job_id ? 'selected' : '' }}>
                                        {{ $jb->job_title }} ({{ $jb->job_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Department</label>
                            <select name="department_id" class="form-select form-select-sm select-search bg-body text-body-emphasis" data-placeholder="Search Department...">
                                <option value=""></option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                                        {{ $dept->department_name }} @if(!empty($dept->company)) (Company: {{ $dept->company->name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Experience (Years)</label>
                            <input type="text" name="experience" value="{{ old('experience') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. 4.5 Years">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Current Employer / Company</label>
                            <input type="text" name="current_company" value="{{ old('current_company') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. TCS / Infosys">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Current Location</label>
                            <input type="text" name="current_location" value="{{ old('current_location') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. New Delhi, India">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Current CTC</label>
                            <input type="text" name="current_package" value="{{ old('current_package') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. 8.5 LPA">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Expected CTC</label>
                            <input type="text" name="expected_package" value="{{ old('expected_package') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. 12 LPA">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Notice Period</label>
                            <input type="text" name="notice_period" value="{{ old('notice_period') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. 30 Days / Immediate">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Reason for Move / Change</label>
                            <input type="text" name="change_reason" value="{{ old('change_reason') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="e.g. Better Career Growth">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">HR / Recruiter Remarks</label>
                            <input type="text" name="hr_remarks" value="{{ old('hr_remarks') }}" class="form-control form-control-sm bg-body text-body-emphasis" placeholder="Screening assessment remarks...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Application Remarks / Sourcing Notes</label>
                        <textarea name="application_remarks" class="form-control form-control-sm bg-body text-body-emphasis" rows="2" placeholder="Optional recruiter comments or special skill tags...">{{ old('application_remarks') }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">
                            <i class="fa-solid fa-paperclip me-1 text-primary"></i> Candidate Resume Document (PDF / DOCX)
                        </label>
                        <input type="file" name="job_resume" class="form-control form-control-sm bg-body text-body-emphasis" accept=".pdf,.doc,.docx">
                        <span class="fs-9 text-body-secondary">Max file size: 10MB (Supported formats: .pdf, .doc, .docx)</span>
                    </div>
                </div>

                <div class="modal-footer border-top bg-body-tertiary">
                    <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                    <!-- Rule 12: Submit button with loader -->
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Candidate Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
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
            @if(isset($errors) && $errors->any())
                var oldEditId = @json(old('edit_application_id'));
                if (oldEditId) {
                    var editEl = document.getElementById('editCandidateModal' + oldEditId);
                    if (editEl) {
                        var editModal = bootstrap.Modal.getOrCreateInstance(editEl);
                        editModal.show();
                    }
                } else {
                    var modalEl = document.getElementById('createCandidateModal');
                    if (modalEl) {
                        var candModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        candModal.show();
                    }
                }
            @endif
        });

        document.addEventListener('shown.bs.modal', function(e) {
            if (e.target && e.target.classList.contains('modal')) {
                initModalSelect2($(e.target));
            }
        });
    })();
</script>
@endpush
