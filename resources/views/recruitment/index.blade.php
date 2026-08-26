@extends('layouts.app')

@section('title', 'Recruitment & Job Applications')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Recruitment Candidate Pipeline</h1>
            <p class="text-muted fs-7 mb-0">Track applicant profiles, screening stages, interviews, and hiring offers.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('recruitment-interviews.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-calendar-check me-1"></i> Scheduled Interviews
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCandidateModal">
                <i class="fa-solid fa-user-plus me-1"></i> Submit Candidate Profile
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                <div>
                    <strong>Submission Failed:</strong>
                    <ul class="mb-0 ps-3 mt-1 fs-8">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Pipeline Summary Statistics Cards (Maintainable KPI Components) -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <x-kpi-card 
                title="Total Applicants" 
                :value="($summary['total_applicants'] ?? 0) . ' Candidates'" 
                icon="fa-solid fa-users" 
                variant="primary" 
            />
        </div>
        <div class="col-xl-3 col-sm-6">
            <x-kpi-card 
                title="Shortlisted" 
                :value="($summary['shortlisted_count'] ?? 0) . ' Candidates'" 
                icon="fa-solid fa-user-tag" 
                variant="info" 
            />
        </div>
        <div class="col-xl-3 col-sm-6">
            <x-kpi-card 
                title="Interviews Scheduled" 
                :value="($summary['interview_count'] ?? 0) . ' Candidates'" 
                icon="fa-solid fa-comments" 
                variant="warning" 
            />
        </div>
        <div class="col-xl-3 col-sm-6">
            <x-kpi-card 
                title="Hired / Offered" 
                :value="($summary['hired_count'] ?? 0) . ' Candidates'" 
                icon="fa-solid fa-user-check" 
                variant="success" 
            />
        </div>
    </div>

    <!-- Main Candidate Pipeline Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom border-subtle pt-3 bg-body-tertiary">
            <form method="GET" action="{{ route('recruitment-applications.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body border-end-0"><i class="fa-solid fa-magnifying-glass text-body-secondary"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search candidate name, email, phone, or company..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Pipeline Stages</option>
                        <option value="Applied" {{ request('status') === 'Applied' ? 'selected' : '' }}>Applied / New</option>
                        <option value="Shortlisted" {{ request('status') === 'Shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="Interview Scheduled" {{ request('status') === 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                        <option value="Hired" {{ request('status') === 'Hired' ? 'selected' : '' }}>Hired / Offered</option>
                        <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('recruitment-applications.index') }}" class="btn btn-outline-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="bg-body-secondary text-body-secondary fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4" style="min-width: 90px;">Actions</th>
                            <th>Candidate</th>
                            <th>Applied Requisition</th>
                            <th>Current Company</th>
                            <th>Experience</th>
                            <th>CTC Range</th>
                            <th class="pe-4">Stage Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                            @php
                                $daysInStage = $app->created_at ? now()->diffInDays($app->created_at) : 0;
                                $isStalled = ($daysInStage >= 7 && !in_array($app->application_status, ['Hired', 'Rejected']));
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <!-- View Details Button -->
                                    <button type="button" class="btn btn-sm btn-light border text-primary py-1 px-2 fs-8 me-1" data-bs-toggle="modal" data-bs-target="#viewCandidateModal{{ $app->application_id }}" title="View Candidate Profile">
                                        <i class="fa-solid fa-eye me-1"></i> View
                                    </button>

                                    <!-- Stage Update Dropdown -->
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-sm btn-outline-secondary py-1 px-2 fs-8 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Stage
                                        </button>
                                        <ul class="dropdown-menu fs-8 shadow border-subtle">
                                            <li>
                                                <form method="POST" action="{{ route('recruitment-applications.status', $app->application_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Shortlisted">
                                                    <button type="submit" class="dropdown-item text-primary"><i class="fa-solid fa-user-check me-2"></i> Shortlist Candidate</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('recruitment-applications.status', $app->application_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Hired">
                                                    <button type="submit" class="dropdown-item text-success"><i class="fa-solid fa-award me-2"></i> Mark Hired / Offer</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('recruitment-applications.status', $app->application_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-ban me-2"></i> Reject Candidate</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Candidate Details Modal -->
                                    <div class="modal fade" id="viewCandidateModal{{ $app->application_id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header border-bottom">
                                                    <h5 class="modal-title fw-bold text-body-emphasis">
                                                        <i class="fa-solid fa-id-card text-primary me-2"></i> {{ $app->candidate_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="p-3 bg-body-tertiary rounded-3 mb-3 border">
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <span class="fs-9 text-body-secondary d-block">Candidate Email</span>
                                                                <span class="fw-semibold text-body-emphasis">{{ $app->email }}</span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <span class="fs-9 text-body-secondary d-block">Contact Phone</span>
                                                                <span class="fw-semibold text-body-emphasis">{{ $app->contact_no ?? 'N/A' }}</span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <span class="fs-9 text-body-secondary d-block">Gender</span>
                                                                <span class="badge bg-primary-subtle text-primary">{{ $app->gender ?? 'Male' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <span class="fs-9 text-body-secondary d-block">Target Job Opening</span>
                                                            <span class="fw-bold text-body-emphasis">{{ $app->job->job_title ?? 'General Requisition' }}</span>
                                                            <span class="badge bg-secondary-subtle text-body-secondary font-mono ms-1">{{ $app->job->job_code ?? 'JOB-GEN' }}</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fs-9 text-body-secondary d-block">Department</span>
                                                            <span class="fw-semibold text-body-emphasis">{{ $app->department->department_name ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-4">
                                                            <span class="fs-9 text-body-secondary d-block">Current Employer</span>
                                                            <span class="fw-semibold text-body-emphasis">{{ $app->current_company ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span class="fs-9 text-body-secondary d-block">Current Location</span>
                                                            <span class="fw-semibold text-body-emphasis">{{ $app->current_location ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span class="fs-9 text-body-secondary d-block">Experience</span>
                                                            <span class="fw-semibold text-body-emphasis">{{ $app->experience ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-4">
                                                            <span class="fs-9 text-body-secondary d-block">Current CTC</span>
                                                            <span class="fw-semibold text-body-emphasis font-mono">{{ $app->current_package ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span class="fs-9 text-body-secondary d-block">Expected CTC</span>
                                                            <span class="fw-semibold text-primary font-mono">{{ $app->expected_package ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span class="fs-9 text-body-secondary d-block">Notice Period</span>
                                                            <span class="fw-semibold text-body-emphasis">{{ $app->notice_period ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <span class="fs-9 text-body-secondary d-block">Reason to Leave / Change</span>
                                                            <div class="p-2 bg-body-tertiary rounded fs-8 text-body-emphasis">{{ $app->change_reason ?? 'Not specified' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fs-9 text-body-secondary d-block">HR / Recruiter Remarks</span>
                                                            <div class="p-2 bg-body-tertiary rounded fs-8 text-body-emphasis">{{ $app->hr_remarks ?? 'No remarks added' }}</div>
                                                        </div>
                                                    </div>

                                                    @if(!empty($app->application_remarks))
                                                    <div class="mb-3">
                                                        <span class="fs-9 text-body-secondary d-block">Sourcing & Application Notes</span>
                                                        <div class="p-2 bg-body-tertiary rounded fs-8 text-body-emphasis">{{ $app->application_remarks }}</div>
                                                    </div>
                                                    @endif

                                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top fs-9 text-body-secondary">
                                                        <div><i class="fa-solid fa-calendar me-1"></i> CV Sourced: {{ $app->date_cv_sourced ?? date('Y-m-d') }}</div>
                                                        <div><i class="fa-solid fa-user me-1"></i> Added By: User ID {{ $app->added_by ?? '1' }}</div>
                                                        <div><x-status-badge :status="$app->application_status ?? 'Applied'" /></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    @if(!empty($app->job_resume))
                                                        <a href="{{ asset('storage/' . $app->job_resume) }}" target="_blank" class="btn btn-outline-primary btn-sm me-auto fw-bold">
                                                            <i class="fa-solid fa-file-pdf me-1"></i> View / Download Resume Document
                                                        </a>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-md rounded-circle me-2 bg-primary-subtle text-primary fw-bold fs-7 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                            {{ substr($app->candidate_name ?? 'C', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis">
                                                {{ $app->candidate_name }}
                                                @if($isStalled)
                                                    <span class="badge bg-danger-subtle text-danger fs-9 ms-1" title="Candidate in stage for over 7 days">
                                                        <i class="fa-solid fa-hourglass-half me-1"></i>Stalled ({{ $daysInStage }}d)
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="fs-9 text-body-secondary">{{ $app->email }} | {{ $app->contact_no }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-body-emphasis">{{ $app->job->job_title ?? 'General Requisition' }}</div>
                                    <span class="badge bg-primary-subtle text-primary font-mono fs-9">{{ $app->job->job_code ?? 'JOB-GEN' }}</span>
                                </td>
                                <td>
                                    <span class="fw-medium text-body-emphasis">{{ $app->current_company ?? 'N/A' }}</span>
                                    <div class="fs-9 text-body-secondary">{{ $app->current_location ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-body-secondary font-mono fs-8">{{ $app->experience ?? 'Fresh' }}</span>
                                </td>
                                <td>
                                    <div class="fs-8 font-mono text-body-emphasis">{{ $app->current_package ?? '--' }} / <strong>{{ $app->expected_package ?? '--' }}</strong></div>
                                </td>
                                <td class="pe-4">
                                    <x-status-badge :status="$app->application_status ?? 'Applied'" :pulse="$isStalled" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-user-slash" 
                                        title="No Candidates Found" 
                                        description="No applicant profiles match the selected filters or stage criteria." 
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($applications->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $applications->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Submit Candidate Profile -->
<div class="modal fade" id="createCandidateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('recruitment-applications.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-user-plus me-2 text-primary"></i> Submit Candidate Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3 p-3 fs-8 border-danger-subtle" role="alert">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fa-solid fa-circle-exclamation me-2 fs-6 text-danger"></i>
                                <strong class="text-danger">Validation Error:</strong>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Candidate Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="candidate_name" value="{{ old('candidate_name') }}" class="form-control form-control-sm @error('candidate_name') is-invalid @enderror" required placeholder="e.g. Rahul Sharma">
                            @error('candidate_name') <div class="invalid-feedback fs-9">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm @error('email') is-invalid @enderror" required placeholder="rahul@example.com">
                            @error('email') <div class="invalid-feedback fs-9">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Contact Phone Number</label>
                            <input type="text" name="contact_no" value="{{ old('contact_no') }}" class="form-control form-control-sm" placeholder="e.g. +91 9876543210">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Gender</label>
                            <select name="gender" class="form-select form-select-sm">
                                <option value="Male" {{ old('gender', 'Male') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Target Job Opening Requisition <span class="text-danger">*</span></label>
                            <select name="job_id" class="form-select form-select-sm select-search" data-control="select2" data-placeholder="Search Job Opening..." required>
                                <option value=""></option>
                                @foreach($jobs as $jb)
                                    <option value="{{ $jb->job_id }}" {{ old('job_id') == $jb->job_id ? 'selected' : '' }}>{{ $jb->job_title }} ({{ $jb->job_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Experience</label>
                            <input type="text" name="experience" value="{{ old('experience') }}" class="form-control form-control-sm" placeholder="e.g. 4.5 Years">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Department</label>
                            <select name="department_id" class="form-select form-select-sm select-search" data-control="select2" data-placeholder="Search Department...">
                                <option value=""></option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                                        {{ $dept->department_name }} @if(!empty($dept->company)) ({{ $dept->company->name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Current Employer / Company</label>
                            <input type="text" name="current_company" value="{{ old('current_company') }}" class="form-control form-control-sm" placeholder="e.g. TCS / Infosys">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Current Location</label>
                            <input type="text" name="current_location" value="{{ old('current_location') }}" class="form-control form-control-sm" placeholder="e.g. Bangalore, India">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Current Package (CTC)</label>
                            <input type="text" name="current_package" value="{{ old('current_package') }}" class="form-control form-control-sm" placeholder="e.g. $60,000 / 8 LPA">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Expected Package (CTC)</label>
                            <input type="text" name="expected_package" value="{{ old('expected_package') }}" class="form-control form-control-sm" placeholder="e.g. $80,000 / 12 LPA">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Notice Period</label>
                            <input type="text" name="notice_period" value="{{ old('notice_period') }}" class="form-control form-control-sm" placeholder="e.g. 30 Days / Immediate">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Reason to Leave / Change</label>
                            <input type="text" name="change_reason" value="{{ old('change_reason') }}" class="form-control form-control-sm" placeholder="e.g. Career Growth / Better Opportunity">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">HR / Recruiter Remarks</label>
                            <input type="text" name="hr_remarks" value="{{ old('hr_remarks') }}" class="form-control form-control-sm" placeholder="Internal HR assessment notes...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Application Remarks / Sourcing Notes</label>
                        <textarea name="application_remarks" class="form-control form-control-sm" rows="2" placeholder="Optional recruiter comments or skill summary...">{{ old('application_remarks') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold"><i class="fa-solid fa-paperclip me-1 text-primary"></i> Upload Candidate Resume Document (PDF / DOCX)</label>
                        <input type="file" name="job_resume" class="form-control form-control-sm" accept=".pdf,.doc,.docx">
                        <span class="fs-9 text-body-secondary">Max file size: 10MB (Supported formats: .pdf, .doc, .docx)</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Submit Candidate</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    (function() {
        function initModalSelect2() {
            var $modal = $('#createCandidateModal');
            if (typeof $.fn.select2 !== 'undefined') {
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
            $('#createCandidateModal').on('shown.bs.modal', function() {
                initModalSelect2();
            });

            @if($errors->any())
                var modalEl = document.getElementById('createCandidateModal');
                if (modalEl) {
                    var candModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    candModal.show();
                }
            @endif
        });

        document.addEventListener('shown.bs.modal', function(e) {
            if (e.target && e.target.id === 'createCandidateModal') {
                initModalSelect2();
            }
        });
    })();
</script>
@endpush
