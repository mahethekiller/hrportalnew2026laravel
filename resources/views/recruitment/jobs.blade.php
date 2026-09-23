@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@extends('layouts.app')

@section('title', 'Job Openings & Requisitions')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner (Matching /recruitment-applications) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-briefcase me-1"></i> Talent Acquisition
                </span>
                <span class="text-body-secondary fs-9">• Hiring Openings & Requisitions</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Job Openings & Requisitions</h4>
            <p class="text-body-secondary fs-8 mb-0">Publish job requisitions, track active vacancies, locations, departments, and closing deadlines.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('recruitment-applications.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-users me-1.5 text-primary"></i> Candidate Pipeline
            </a>
            <a href="{{ route('recruitment-job-codes.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-tags me-1.5 text-info"></i> Job Codes
            </a>
            <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#createJobPostModal">
                <i class="fa-solid fa-plus me-1.5"></i> Create Requisition
            </button>
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards (Matching /recruitment-applications exact pattern) -->
    <div class="row g-3 mb-4">
        <!-- Total Job Posts -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Job Posts</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $summary['total_posts'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Registered hiring requisitions</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-briefcase fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Published Openings -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Published Openings</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $summary['active_posts'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Active & accepting candidates</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Total Vacancies -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Vacancies</span>
                        <h3 class="fw-bolder text-info mb-1 mt-1">{{ $summary['total_vacancies'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Approved open positions</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users-viewfinder fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Closed / Draft -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Closed / Draft</span>
                        <h3 class="fw-bolder text-danger mb-1 mt-1">{{ $summary['closed_posts'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Archived or expired requisitions</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-folder-closed fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar & Search Bar (Matching /recruitment-applications exact pattern) -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('recruitment-job-posts.index') }}" class="row g-2 align-items-center">
                <!-- Search Input -->
                <div class="col-lg-5 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body text-body-secondary border-end-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-body text-body-emphasis border-start-0" placeholder="Search job code, title, location, or department..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-lg-3 col-md-6">
                    <select name="status" class="form-select form-select-sm bg-body text-body-emphasis" onchange="this.form.submit()">
                        <option value="">All Requisition Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published / Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Closed / Draft</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="col-lg-4 col-md-12 d-flex gap-1.5 justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold rounded-2 w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('recruitment-job-posts.index') }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Job Requisitions Data Table Card (Matching /recruitment-applications exact structure) -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0">Job Requisitions Roster</h5>
                    <span class="text-body-secondary fs-9">Active vacancy listings, candidate capacity, and application deadlines</span>
                </div>
            </div>
            <span class="badge bg-body text-body-secondary border px-2.5 py-1.5 rounded-pill fs-9 fw-semibold">
                {{ $jobs->total() }} Requisitions Found
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 150px;">Actions</th>
                            <th>Job Code</th>
                            <th>Position & Department</th>
                            <th>Job Type & Location</th>
                            <th>Vacancies & Influx</th>
                            <th>Experience</th>
                            <th>Closing Date</th>
                            <th class="pe-4 text-center" style="width: 140px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $jb)
                            @php
                                $candidateCount = $jb->applications->count();
                                $isPastClosing = !empty($jb->date_of_closing) && strtotime($jb->date_of_closing) < strtotime('today');
                                $isClosingSoon = !empty($jb->date_of_closing) && !$isPastClosing && (strtotime($jb->date_of_closing) - strtotime('today') <= 7 * 86400);
                            @endphp
                            <tr>
                                <!-- Column 1: Actions (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- View Details Dossier Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" data-bs-toggle="modal" data-bs-target="#viewJobPostModal{{ $jb->job_id }}" title="View Requisition Dossier">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                        <!-- Edit Requisition Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" data-bs-toggle="modal" data-bs-target="#editJobPostModal{{ $jb->job_id }}" title="Edit Requisition">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Direct Jump to Candidates for this Opening -->
                                        <a href="{{ route('recruitment-applications.index', ['job_id' => $jb->job_id]) }}" class="btn btn-sm btn-outline-info px-2.5 rounded-2 position-relative" title="View Candidates ({{ $candidateCount }} in pipeline)">
                                            <i class="fa-solid fa-users"></i>
                                            @if($candidateCount > 0)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info text-white fs-10 px-1 py-0.5">
                                                    {{ $candidateCount > 99 ? '99+' : $candidateCount }}
                                                </span>
                                            @endif
                                        </a>
                                    </div>
                                </td>

                                <!-- Job Code Tag -->
                                <td>
                                    <span class="badge bg-body text-primary border px-2 py-1 rounded-2 font-monospace fw-semibold">
                                        {{ $jb->job_code ?? 'JOB-000' }}
                                    </span>
                                </td>

                                <!-- Position & Department -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis">
                                        {{ $jb->job_title }}
                                    </div>
                                    <div class="fs-9 text-body-secondary">
                                        <i class="fa-regular fa-building me-1 text-primary"></i>{{ $jb->department ?? 'General Operations' }}
                                        @if(!empty($jb->company))
                                            <span class="text-muted ms-1">({{ $jb->company->name }})</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Job Type & Location -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis">
                                        {{ $jb->job_type ?? 'Full Time' }}
                                    </div>
                                    <div class="fs-9 text-body-secondary">
                                        <i class="fa-solid fa-location-dot me-1 text-primary"></i> {{ $jb->job_location ?: 'Location N/A' }}
                                    </div>
                                </td>

                                <!-- Vacancies & Influx -->
                                <td>
                                    <div class="font-monospace fw-bold text-body-emphasis">
                                        {{ $jb->job_vacancy }} Positions
                                    </div>
                                    <div class="fs-9 text-body-secondary mt-0.5">
                                        <a href="{{ route('recruitment-applications.index', ['job_id' => $jb->job_id]) }}" class="text-info text-decoration-none fw-semibold">
                                            <i class="fa-solid fa-users me-1"></i>{{ $candidateCount }} Candidates
                                        </a>
                                    </div>
                                </td>

                                <!-- Experience Range -->
                                <td>
                                    <span class="badge bg-body text-body-emphasis border px-2 py-1 rounded-2 fw-semibold">
                                        {{ $jb->minimum_experience ?? '0' }} - {{ $jb->maximum_experience ?? '5' }} Yrs
                                    </span>
                                </td>

                                <!-- Closing Date -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis">
                                        {{ $jb->formatted_closing_date }}
                                    </div>
                                    @if($isPastClosing && $jb->status == 1)
                                        <div class="fs-9 text-danger fw-semibold mt-0.5">
                                            <i class="fa-solid fa-circle-exclamation me-1"></i>Expired
                                        </div>
                                    @elseif($isClosingSoon && $jb->status == 1)
                                        <div class="fs-9 text-warning fw-semibold mt-0.5">
                                            <i class="fa-solid fa-hourglass-half me-1"></i>Closing Soon
                                        </div>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="pe-4 text-center">
                                    <span class="badge {{ $jb->status_badge_class }} px-2.5 py-1 rounded-pill fs-9">
                                        {{ $jb->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-body-secondary">
                                    <div class="py-4">
                                        <div class="symbol symbol-50px bg-body text-body-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width:50px; height:50px;">
                                            <i class="fa-solid fa-briefcase fs-3 text-body-tertiary"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No Job Requisitions Found</h6>
                                        <p class="fs-8 text-body-secondary mb-3">No openings match your current search or filter criteria.</p>
                                        <div class="d-flex justify-content-center gap-2">
                                            @if(request()->filled('search') || request()->filled('status'))
                                                <a href="{{ route('recruitment-job-posts.index') }}" class="btn btn-sm btn-outline-secondary px-3 rounded-2">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-primary px-3 rounded-2" data-bs-toggle="modal" data-bs-target="#createJobPostModal">
                                                <i class="fa-solid fa-plus me-1"></i> Create Requisition
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Bar (Matching /recruitment-applications) -->
        @if($jobs->hasPages())
            <div class="card-footer py-3 border-top bg-transparent">
                {{ $jobs->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- ========================================== -->
<!-- MODALS: REQUISITION DOSSIER & EDIT MODALS  -->
<!-- ========================================== -->
@foreach($jobs as $jb)
    <!-- Modal: Job Requisition Dossier (Matching /recruitment-applications modal style) -->
    <div class="modal fade" id="viewJobPostModal{{ $jb->job_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-body border-0 shadow">
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">
                                Requisition Profile — {{ $jb->job_title }}
                            </h5>
                            <span class="fs-9 text-body-secondary font-monospace">Code: {{ $jb->job_code ?? 'N/A' }} &bull; Status: {{ $jb->status_label }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Overview Card -->
                    <div class="p-3 bg-body-tertiary rounded-3 mb-4 border">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Approved Vacancies</span>
                                <span class="fw-semibold text-body-emphasis fs-8 font-monospace">{{ $jb->job_vacancy }} Positions</span>
                            </div>
                            <div class="col-sm-4">
                                <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Candidate Influx</span>
                                <span class="fw-semibold text-body-emphasis fs-8 font-monospace">{{ $jb->applications->count() }} Applicants</span>
                            </div>
                            <div class="col-sm-4">
                                <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-1">Closing Target</span>
                                <span class="fw-semibold text-body-emphasis fs-8">{{ $jb->formatted_closing_date }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Workplace & Experience Details -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 bg-body-tertiary rounded-3 border h-100">
                                <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-2">
                                    <i class="fa-solid fa-location-dot me-1 text-primary"></i> Workplace & Type
                                </span>
                                <div class="mb-2">
                                    <span class="fs-9 text-body-secondary d-block">Location:</span>
                                    <span class="fw-semibold text-body-emphasis fs-8">{{ $jb->job_location ?: 'Not specified' }}</span>
                                </div>
                                <div>
                                    <span class="fs-9 text-body-secondary d-block">Employment Type:</span>
                                    <span class="fw-semibold text-body-emphasis fs-8">{{ $jb->job_type ?? 'Full Time' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-body-tertiary rounded-3 border h-100">
                                <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-2">
                                    <i class="fa-solid fa-building me-1 text-info"></i> Department & Organization
                                </span>
                                <div class="mb-2">
                                    <span class="fs-9 text-body-secondary d-block">Department:</span>
                                    <span class="fw-semibold text-body-emphasis fs-8">{{ $jb->department ?: 'General Department' }}</span>
                                </div>
                                <div>
                                    <span class="fs-9 text-body-secondary d-block">Experience Required:</span>
                                    <span class="fw-semibold text-body-emphasis fs-8">{{ $jb->minimum_experience ?? '0' }} to {{ $jb->maximum_experience ?? '5' }} Years</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div class="p-3 bg-body-tertiary rounded-3 border">
                        <span class="fs-9 text-body-secondary fw-bold text-uppercase d-block mb-2">
                            <i class="fa-solid fa-align-left me-1 text-secondary"></i> Requisition Summary
                        </span>
                        <p class="fs-8 text-body-secondary mb-0" style="line-height: 1.6;">
                            {{ $jb->short_description ?: 'No additional requisition description entered.' }}
                        </p>
                    </div>
                </div>

                <div class="modal-footer border-top bg-body-tertiary">
                    <a href="{{ route('recruitment-applications.index', ['job_id' => $jb->job_id]) }}" class="btn btn-outline-primary btn-sm me-auto fw-semibold">
                        <i class="fa-solid fa-users me-1"></i> View Pipeline Candidates ({{ $jb->applications->count() }})
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-warning fw-semibold me-1" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editJobPostModal{{ $jb->job_id }}">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Requisition
                    </button>
                    <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Job Post Requisition (Matching /recruitment-applications modal style) -->
    <div class="modal fade text-start" id="editJobPostModal{{ $jb->job_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="POST" action="{{ route('recruitment-job-posts.update', $jb->job_id) }}" class="modal-content bg-body border-0 shadow" onsubmit="submitWithLoader(this)">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_job_id" value="{{ $jb->job_id }}">

                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-2 bg-warning-subtle text-warning fs-8">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Edit Requisition: {{ $jb->job_title }}</h5>
                            <span class="fs-9 text-body-secondary">Update vacancy details, requirements, and closing schedules</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    @if($errors->any() && old('edit_job_id') == $jb->job_id)
                        <div class="alert alert-danger p-3 fs-8 mb-3 rounded-3 border-danger-subtle">
                            <div class="fw-bold text-danger mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Please resolve validation errors:</div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Title / Role <span class="text-danger">*</span></label>
                            <input type="text" name="job_title" class="form-control form-control-sm" required value="{{ old('job_title', $jb->job_title) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Code Tag</label>
                            <select name="job_code" class="form-select form-select-sm select-search" data-placeholder="Select Job Code Tag...">
                                <option value=""></option>
                                @foreach($jobCodes as $jc)
                                    <option value="{{ $jc->job_code }}" {{ old('job_code', $jb->job_code) === $jc->job_code ? 'selected' : '' }}>
                                        {{ $jc->job_code }} - {{ $jc->position }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Type</label>
                            <select name="job_type" class="form-select form-select-sm">
                                <option value="Full Time" {{ old('job_type', $jb->job_type) === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time" {{ old('job_type', $jb->job_type) === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Contract" {{ old('job_type', $jb->job_type) === 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Internship" {{ old('job_type', $jb->job_type) === 'Internship' ? 'selected' : '' }}>Internship</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Vacancies <span class="text-danger">*</span></label>
                            <input type="number" name="job_vacancy" class="form-control form-control-sm" required value="{{ old('job_vacancy', $jb->job_vacancy) }}" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Department</label>
                            <select name="department" class="form-select form-select-sm select-search" data-placeholder="Select Department...">
                                <option value=""></option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_name }}" {{ old('department', $jb->department) === $dept->department_name ? 'selected' : '' }}>
                                        {{ $dept->department_name }} @if(!empty($dept->company)) (Company: {{ $dept->company->name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Location</label>
                            <input type="text" name="job_location" class="form-control form-control-sm" value="{{ old('job_location', $jb->job_location) }}" placeholder="e.g. Noida, India / Hybrid">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Min Experience (Yrs)</label>
                            <input type="text" name="minimum_experience" class="form-control form-control-sm" value="{{ old('minimum_experience', $jb->minimum_experience) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Max Experience (Yrs)</label>
                            <input type="text" name="maximum_experience" class="form-control form-control-sm" value="{{ old('maximum_experience', $jb->maximum_experience) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Application Closing Date</label>
                            <input type="date" name="date_of_closing" class="form-control form-control-sm" value="{{ old('date_of_closing', $jb->date_of_closing) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Publish Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="1" {{ old('status', $jb->status) == 1 ? 'selected' : '' }}>Published / Active</option>
                                <option value="0" {{ old('status', $jb->status) == 0 ? 'selected' : '' }}>Draft / Closed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Short Requisition Summary</label>
                        <textarea name="short_description" class="form-control form-control-sm" rows="3" placeholder="Key responsibilities and qualifications required...">{{ old('short_description', $jb->short_description) }}</textarea>
                    </div>
                </div>

                <div class="modal-footer border-top bg-body-tertiary">
                    <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i> Update Requisition
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<!-- Modal: Create Job Requisition (Matching /recruitment-applications modal style) -->
<div class="modal fade" id="createJobPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('recruitment-job-posts.store') }}" class="modal-content bg-body border-0 shadow" onsubmit="submitWithLoader(this)">
            @csrf
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Create Job Opening Requisition</h5>
                        <span class="fs-9 text-body-secondary">Publish a new hiring vacancy and set application deadline</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                @if($errors->any() && !old('edit_job_id'))
                    <div class="alert alert-danger p-3 fs-8 mb-3 rounded-3 border-danger-subtle">
                        <div class="fw-bold text-danger mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Please resolve validation errors:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Title / Role <span class="text-danger">*</span></label>
                        <input type="text" name="job_title" class="form-control form-control-sm" required placeholder="e.g. Senior Laravel Architect / HR Lead" value="{{ old('job_title') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Code Tag</label>
                        <select name="job_code" class="form-select form-select-sm select-search" data-placeholder="Select Job Code Tag (Or Auto-Generate)...">
                            <option value=""></option>
                            @foreach($jobCodes as $jc)
                                <option value="{{ $jc->job_code }}" {{ old('job_code') === $jc->job_code ? 'selected' : '' }}>
                                    {{ $jc->job_code }} - {{ $jc->position }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Type</label>
                        <select name="job_type" class="form-select form-select-sm">
                            <option value="Full Time" {{ old('job_type', 'Full Time') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                            <option value="Part Time" {{ old('job_type', 'Part Time') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                            <option value="Contract" {{ old('job_type', 'Contract') === 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Internship" {{ old('job_type', 'Internship') === 'Internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Vacancies <span class="text-danger">*</span></label>
                        <input type="number" name="job_vacancy" class="form-control form-control-sm" required value="{{ old('job_vacancy', 1) }}" min="1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Department</label>
                        <select name="department" class="form-select form-select-sm select-search" data-placeholder="Select Department...">
                            <option value=""></option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_name }}" {{ old('department') === $dept->department_name ? 'selected' : '' }}>
                                    {{ $dept->department_name }} @if(!empty($dept->company)) (Company: {{ $dept->company->name }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Location</label>
                        <input type="text" name="job_location" class="form-control form-control-sm" placeholder="e.g. Noida, India / Hybrid" value="{{ old('job_location') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Min Experience (Yrs)</label>
                        <input type="text" name="minimum_experience" class="form-control form-control-sm" placeholder="e.g. 2" value="{{ old('minimum_experience') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Max Experience (Yrs)</label>
                        <input type="text" name="maximum_experience" class="form-control form-control-sm" placeholder="e.g. 5" value="{{ old('maximum_experience') }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Application Closing Date</label>
                        <input type="date" name="date_of_closing" class="form-control form-control-sm" value="{{ old('date_of_closing', date('Y-m-d', strtotime('+30 days'))) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Publish Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Published / Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Draft / Closed</option>
                        </select>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Short Requisition Summary</label>
                    <textarea name="short_description" class="form-control form-control-sm" rows="3" placeholder="Brief job opening summary and primary skills required...">{{ old('short_description') }}</textarea>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary">
                <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                    <i class="fa-solid fa-paper-plane me-1"></i> Publish Requisition
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
            @if($errors->any() || session('error'))
                var oldEditId = @json(old('edit_job_id'));
                if (oldEditId) {
                    var modalEl = document.getElementById('editJobPostModal' + oldEditId);
                    if (modalEl) {
                        bootstrap.Modal.getOrCreateInstance(modalEl).show();
                    }
                } else {
                    var modalEl = document.getElementById('createJobPostModal');
                    if (modalEl) {
                        bootstrap.Modal.getOrCreateInstance(modalEl).show();
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
