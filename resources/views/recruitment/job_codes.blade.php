@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@extends('layouts.app')

@section('title', 'Company Job Code Tags')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner (Matching /recruitment-applications) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-tags me-1"></i> Talent Acquisition
                </span>
                <span class="text-body-secondary fs-9">• Position Codes & Standard Identifiers</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Company Job Code Tags</h4>
            <p class="text-body-secondary fs-8 mb-0">Standardize requisition identifiers across departments (e.g. <code>JOB-DEV-001</code>) paired with openings.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('recruitment-job-posts.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-briefcase me-1.5 text-primary"></i> Job Openings
            </a>
            <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#createJobCodeModal">
                <i class="fa-solid fa-plus me-1.5"></i> Create Job Code
            </button>
        </div>
    </div>

    <!-- Telemetry Metrics Cards (Matching /recruitment-applications exact pattern) -->
    <div class="row g-3 mb-4">
        <!-- Total Job Codes -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Job Codes</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $summary['total'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Configured position identifiers</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-tags fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Active Job Codes -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Active Codes</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $summary['active'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Available for new requisitions</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Inactive / Deprecated -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Inactive / Deprecated</span>
                        <h3 class="fw-bolder text-danger mb-1 mt-1">{{ $summary['inactive'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Archived role identifiers</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-ban fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar & Search Bar -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('recruitment-job-codes.index') }}" class="row g-2 align-items-center">
                <!-- Search Input -->
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body text-body-secondary border-end-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-body text-body-emphasis border-start-0" placeholder="Search job code or target position..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm bg-body text-body-emphasis" onchange="this.form.submit()">
                        <option value="">All Job Code Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="col-md-2 d-flex gap-1.5 justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold rounded-2 w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('recruitment-job-codes.index') }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Job Codes Roster Table Card (Matching /recruitment-applications structure) -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0">Job Codes Roster</h5>
                    <span class="text-body-secondary fs-9">Standardized position codes, roles, and status mapping</span>
                </div>
            </div>
            <span class="badge bg-body text-body-secondary border px-2.5 py-1.5 rounded-pill fs-9 fw-semibold">
                {{ $jobCodes->total() }} Job Codes Found
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 120px;">Actions</th>
                            <th>Job Code Tag</th>
                            <th>Target Position / Role</th>
                            <th>Added By</th>
                            <th>Created Date</th>
                            <th class="pe-4 text-center" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobCodes as $jc)
                            <tr>
                                <!-- Column 1: Actions (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- Edit Action (Icon Only) -->
                                        <button type="button" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" data-bs-toggle="modal" data-bs-target="#editJobCodeModal{{ $jc->job_code_id }}" title="Edit Job Code">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Filter Openings with this Code (Icon Only) -->
                                        <a href="{{ route('recruitment-job-posts.index', ['search' => $jc->job_code]) }}" class="btn btn-sm btn-outline-info px-2.5 rounded-2" title="View Openings for this Code">
                                            <i class="fa-solid fa-briefcase"></i>
                                        </a>
                                    </div>
                                </td>

                                <!-- Job Code Tag -->
                                <td>
                                    <span class="badge bg-body text-primary border px-2.5 py-1 rounded-2 font-monospace fw-semibold fs-8">
                                        {{ $jc->job_code }}
                                    </span>
                                </td>

                                <!-- Target Position / Role -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis fs-8">{{ $jc->position }}</div>
                                </td>

                                <!-- Added By -->
                                <td>
                                    <span class="fs-8 text-body-secondary">{{ $jc->added_by ?? 'Recruiter' }}</span>
                                </td>

                                <!-- Created Date -->
                                <td>
                                    <span class="fs-8 text-body-secondary"><x-human-date :value="$jc->added_date" /></span>
                                </td>

                                <!-- Status Badge -->
                                <td class="pe-4 text-center">
                                    <span class="badge {{ $jc->status_badge_class }} px-2.5 py-1 rounded-pill fs-9">
                                        {{ $jc->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    <div class="py-4">
                                        <div class="symbol symbol-50px bg-body text-body-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width:50px; height:50px;">
                                            <i class="fa-solid fa-tags fs-3 text-body-tertiary"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No Job Codes Found</h6>
                                        <p class="fs-8 text-body-secondary mb-3">No codes match your current search or filter criteria.</p>
                                        <div class="d-flex justify-content-center gap-2">
                                            @if(request()->filled('search') || request()->filled('status'))
                                                <a href="{{ route('recruitment-job-codes.index') }}" class="btn btn-sm btn-outline-secondary px-3 rounded-2">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-primary px-3 rounded-2" data-bs-toggle="modal" data-bs-target="#createJobCodeModal">
                                                <i class="fa-solid fa-plus me-1"></i> Create Job Code
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

        <!-- Pagination Bar -->
        @if($jobCodes->hasPages())
            <div class="card-footer py-3 border-top bg-transparent">
                {{ $jobCodes->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- ========================================== -->
<!-- MODALS: EDIT & CREATE JOB CODE TAGS        -->
<!-- ========================================== -->
@foreach($jobCodes as $jc)
    <div class="modal fade text-start" id="editJobCodeModal{{ $jc->job_code_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('recruitment-job-codes.update', $jc->job_code_id) }}" class="modal-content bg-body border-0 shadow" onsubmit="submitWithLoader(this)">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_job_code_id" value="{{ $jc->job_code_id }}">
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-2 bg-warning-subtle text-warning fs-8">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Edit Job Code Tag</h5>
                            <span class="fs-9 text-body-secondary">Update code identifier and position mapping</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    @if($errors->any() && old('edit_job_code_id') == $jc->job_code_id)
                        <div class="alert alert-danger p-3 fs-8 mb-3 rounded-3 border-danger-subtle">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Code Tag <span class="text-danger">*</span></label>
                        <input type="text" name="job_code" class="form-control form-control-sm font-monospace bg-body text-body-emphasis" required value="{{ old('job_code', $jc->job_code) }}">
                        <div class="fs-9 text-body-secondary mt-1">Unique code format e.g. <code>JOB-DEV-001</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Target Position / Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="position" class="form-control form-control-sm bg-body text-body-emphasis" required value="{{ old('position', $jc->position) }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Status</label>
                        <select name="status" class="form-select form-select-sm bg-body text-body-emphasis">
                            <option value="active" {{ (old('status', $jc->status) === 'active' || old('status', $jc->status) == 1) ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ (old('status', $jc->status) === 'inactive' || old('status', $jc->status) == 0) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top bg-body-tertiary">
                    <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-semibold px-3">
                        <i class="fa-solid fa-check me-1"></i> Update Job Code
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<!-- Modal: Create Job Code -->
<div class="modal fade" id="createJobCodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('recruitment-job-codes.store') }}" class="modal-content bg-body border-0 shadow" onsubmit="submitWithLoader(this)">
            @csrf
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Create Job Code Tag</h5>
                        <span class="fs-9 text-body-secondary">Register a new standard job code identifier</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                @if($errors->any() && !old('edit_job_code_id'))
                    <div class="alert alert-danger p-3 fs-8 mb-3 rounded-3 border-danger-subtle">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Job Code Tag <span class="text-danger">*</span></label>
                    <input type="text" name="job_code" class="form-control form-control-sm font-monospace bg-body text-body-emphasis" required placeholder="e.g. JOB-DEV-001 / SR-ACC-2026" value="{{ old('job_code') }}">
                    <div class="fs-9 text-body-secondary mt-1">Structured identifier for standardizing positions across job requisitions.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Target Position / Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control form-control-sm bg-body text-body-emphasis" required placeholder="e.g. Senior Software Engineer" value="{{ old('position') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Status</label>
                    <select name="status" class="form-select form-select-sm bg-body text-body-emphasis">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top bg-body-tertiary">
                <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                    <i class="fa-solid fa-paper-plane me-1"></i> Save Job Code
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        @if($errors->any() || session('error'))
            var oldEditCodeId = @json(old('edit_job_code_id'));
            if (oldEditCodeId) {
                var modalEl = document.getElementById('editJobCodeModal' + oldEditCodeId);
                if (modalEl) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            } else {
                var modalEl = document.getElementById('createJobCodeModal');
                if (modalEl) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            }
        @endif
    });
</script>
@endpush
