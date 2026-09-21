@extends('layouts.app')

@section('title', 'Refer a Candidate')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-solid fa-users-viewfinder me-1"></i> Self-Service Portal
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Talent Network</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Candidate Referrals & Rewards</h1>
            <p class="text-body-secondary fs-7 mb-0">Recommend qualified professionals from your network, track recruiter review stages, and qualify for referral rewards.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#referralModal">
                <i class="fa-solid fa-user-plus me-1.5"></i> Submit New Referral
            </button>
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

    <!-- Metric KPI Cards (Airy & Elevated: DENSITY: 3, MOTION: 4, VARIANCE: 6) -->
    <div class="row g-3 mb-4">
        <!-- Total Referrals -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Total Referred</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-users fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-emphasis mb-1">{{ $stats['total'] ?? $referrals->count() }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">All-time candidates submitted</p>
                </div>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Under Review</span>
                        <div class="stat-icon-wrapper rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-hourglass-half fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-warning mb-1">{{ $stats['pending'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Awaiting recruiter screening</p>
                </div>
            </div>
        </div>

        <!-- In Interview -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">In Interview</span>
                        <div class="stat-icon-wrapper rounded-2 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-comments fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-info mb-1">{{ $stats['interviewing'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Shortlisted & interviewing</p>
                </div>
            </div>
        </div>

        <!-- Hired -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Hired & Awarded</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-trophy fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-success mb-1">{{ $stats['hired'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Eligible for referral reward</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referrals Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body">
        <!-- Card Header with Search & Filter Tabs -->
        <div class="card-header bg-transparent border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-check text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis">My Referral Portfolio</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 fs-9 fw-normal ms-1">
                        {{ $referrals->count() }} Candidates
                    </span>
                </div>

                <!-- Table Quick Filters -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="btn-group btn-group-sm" role="group" id="referralStatusTabs">
                        <button type="button" class="btn btn-outline-secondary active fw-semibold" data-ref-status="all">All</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-ref-status="pending">Pending</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-ref-status="interviewing">Interviewing</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-ref-status="hired">Hired</button>
                    </div>
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <span class="input-group-text bg-body border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass fs-9"></i></span>
                        <input type="text" id="referralTableSearch" class="form-control bg-body border-start-0 fs-8" placeholder="Search candidates...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8" id="referralTable">
                    <thead class="table-light border-bottom">
                        <tr>
                            <!-- Rule 8: First-Column Icon-Only Actions -->
                            <th class="ps-4" style="width: 100px;">Actions</th>
                            <th style="width: 220px;">Candidate</th>
                            <th style="width: 210px;">Requisition / Job Role</th>
                            <th style="width: 150px;">Contact No</th>
                            <th style="width: 120px;">Resume</th>
                            <th style="width: 140px;">Submitted On</th>
                            <th class="pe-4 text-end" style="width: 130px;">Hiring Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $ref)
                            @php
                                $refId = $ref->referral_id ?? $ref->id;
                                $candidateName = $ref->clean_subject ?: $ref->name;
                                $statusStr = strtolower($ref->status ?? 'pending');

                                $filterCategory = 'pending';
                                if (in_array($statusStr, ['hired', 'approved', '2'], true)) {
                                    $filterCategory = 'hired';
                                } elseif (in_array($statusStr, ['interviewing', 'shortlisted', 'screening', 'in progress'], true)) {
                                    $filterCategory = 'interviewing';
                                }

                                $jobTitle = $ref->job?->job_title ?: 'General Talent Pool';
                            @endphp
                            <tr class="referral-row transition-colors" data-ref-category="{{ $filterCategory }}">
                                <!-- Rule 8: First-Column Icon-Only Actions with explicit spacing -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewReferralModal{{ $refId }}" 
                                                title="View Candidate Details">
                                            <i class="fa-solid fa-eye fs-8"></i>
                                        </button>
                                        @if(!empty($ref->resume))
                                            <a href="{{ asset($ref->resume) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-success px-2.5 rounded-2" 
                                               title="Download Candidate Resume">
                                                <i class="fa-solid fa-file-arrow-down fs-8"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Candidate Name & Email -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 32px; height: 32px; min-width: 32px;">
                                            {{ strtoupper(substr($candidateName, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-body-emphasis d-block">{{ $candidateName }}</span>
                                            <span class="fs-9 text-body-secondary">
                                                <i class="fa-regular fa-envelope me-1"></i> {{ $ref->email }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Target Job Requisition -->
                                <td>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <i class="fa-solid fa-briefcase text-primary fs-9"></i>
                                        <span class="fw-medium text-body-emphasis">{{ $jobTitle }}</span>
                                    </div>
                                </td>

                                <!-- Contact Number -->
                                <td>
                                    <span class="font-monospace fs-9 text-body-emphasis">
                                        {{ $ref->contact_number ?? $ref->contact_no ?? '--' }}
                                    </span>
                                </td>

                                <!-- Resume -->
                                <td>
                                    @if(!empty($ref->resume))
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-9 fw-medium">
                                            <i class="fa-solid fa-paperclip me-1"></i> Attached
                                        </span>
                                    @else
                                        <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2 py-0.5 fs-9">
                                            None
                                        </span>
                                    @endif
                                </td>

                                <!-- Submitted On -->
                                <td>
                                    <span class="text-body-secondary fs-9">
                                        <x-human-date :value="$ref->created_at ?? $ref->added_date" />
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="pe-4 text-end">
                                    @if($filterCategory === 'hired')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Hired
                                        </span>
                                    @elseif($filterCategory === 'interviewing')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-comments me-1"></i> Interviewing
                                        </span>
                                    @elseif(in_array($statusStr, ['rejected', 'declined', '3'], true))
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Declined
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-clock me-1"></i> Pending Review
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Detail Modal for each candidate referral -->
                            <div class="modal fade" id="viewReferralModal{{ $refId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-bottom py-3 px-4">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 36px; height: 36px;">
                                                    {{ strtoupper(substr($candidateName, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis mb-0">{{ $candidateName }}</h5>
                                                    <span class="fs-9 text-body-secondary">Candidate Referral Profile</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 fs-8">
                                            <!-- Status Banner inside Modal -->
                                            <div class="p-3 rounded-3 mb-3 d-flex align-items-center justify-content-between {{ $filterCategory === 'hired' ? 'bg-success-subtle border border-success-subtle text-success' : ($filterCategory === 'interviewing' ? 'bg-info-subtle border border-info-subtle text-info' : 'bg-warning-subtle border border-warning-subtle text-warning') }}">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid {{ $filterCategory === 'hired' ? 'fa-circle-check' : ($filterCategory === 'interviewing' ? 'fa-comments' : 'fa-hourglass-half') }} fs-6"></i>
                                                    <span class="fw-bold">Current Stage: {{ $ref->status ?? 'Pending Review' }}</span>
                                                </div>
                                                <span class="badge bg-body text-body-emphasis border fs-9">
                                                    Ref #{{ $refId }}
                                                </span>
                                            </div>

                                            <div class="row g-3 mb-3">
                                                <div class="col-6">
                                                    <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Target Requisition</label>
                                                    <div class="fw-bold text-body-emphasis fs-8">
                                                        <i class="fa-solid fa-briefcase me-1 text-primary"></i> {{ $jobTitle }}
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Contact Number</label>
                                                    <div class="fw-bold text-body-emphasis fs-8 font-monospace">
                                                        <i class="fa-solid fa-phone me-1 text-success"></i> {{ $ref->contact_number ?? $ref->contact_no ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 rounded-2 bg-body-tertiary border mb-3">
                                                <div class="row g-2">
                                                    <div class="col-12 mb-2">
                                                        <span class="text-body-secondary fs-9 d-block">Candidate Email:</span>
                                                        <span class="fw-semibold text-body font-monospace">{{ $ref->email }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-body-secondary fs-9 d-block mb-1">Resume / CV Document:</span>
                                                        @if(!empty($ref->resume))
                                                            <a href="{{ asset($ref->resume) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2.5 fs-9 fw-semibold">
                                                                <i class="fa-solid fa-file-pdf me-1"></i> Open Resume Attachment
                                                            </a>
                                                        @else
                                                            <span class="text-body-secondary fs-9 italic">No resume attached upon referral submission.</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if(!empty($ref->remarks))
                                                <div class="mb-3">
                                                    <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Recruiter / HR Remarks</label>
                                                    <div class="p-3 rounded-2 bg-body-secondary border text-body-emphasis">
                                                        {{ $ref->remarks }}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top fs-9 text-body-secondary">
                                                <span>Submitted on:</span>
                                                <span class="fw-medium text-body">
                                                    <x-human-date :value="$ref->created_at ?? $ref->added_date" />
                                                </span>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top py-2 px-4">
                                            <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-body-secondary">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                        <div class="rounded-circle bg-body-tertiary p-3 mb-3 border text-body-secondary opacity-75">
                                            <i class="fa-solid fa-user-plus fs-2"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No Candidate Referrals Yet</h6>
                                        <p class="fs-8 text-body-secondary mb-3">Recommend qualified friends or colleagues and track their interview progress here.</p>
                                        <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#referralModal">
                                            <i class="fa-solid fa-plus me-1"></i> Submit First Referral
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Submit Referral (Rule 9, 10, 11, 12 Compliant) -->
<div class="modal fade" id="referralModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fa-solid fa-user-plus fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-body-emphasis mb-0">Refer a Candidate</h5>
                        <p class="fs-9 text-body-secondary mb-0">Submit talent for active corporate job openings</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('my-portal.referrals.store') }}" enctype="multipart/form-data" id="referralSubmissionForm">
                @csrf
                <div class="modal-body p-4 fs-8">
                    <!-- Rule 10: In-modal error alerts -->
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-3 py-2 px-3 fs-8 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif

                    <!-- Target Job Requisition (Rule 9: Searchable Select) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                            Target Job Requisition <span class="text-danger">*</span>
                        </label>
                        <select name="job_id" id="job_id_select" class="form-select fs-8 select-search" required>
                            <option value="">-- Select Open Job Role --</option>
                            @foreach($openJobs as $job)
                                <option value="{{ $job->job_id }}" {{ old('job_id') == $job->job_id ? 'selected' : '' }}>
                                    {{ $job->job_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                                Candidate Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control fs-8 bg-body" 
                                   value="{{ old('name') }}" 
                                   required 
                                   placeholder="e.g. Rahul Sharma">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                                Candidate Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control fs-8 bg-body" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="candidate@example.com">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                                Contact Mobile Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="contact_number" 
                                   class="form-control fs-8 bg-body font-monospace" 
                                   value="{{ old('contact_number') }}" 
                                   required 
                                   placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                                Resume / CV Attachment (PDF, DOC, DOCX)
                            </label>
                            <input type="file" 
                                   name="resume" 
                                   class="form-control fs-8 bg-body" 
                                   accept=".pdf,.doc,.docx">
                            <span class="fs-9 text-body-secondary d-block mt-1">Maximum file size: 5 MB</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-2 bg-body-tertiary border d-flex align-items-center gap-2 fs-9 text-body-secondary">
                        <i class="fa-solid fa-gift text-primary fs-7"></i>
                        <span>Referral rewards will be granted in according with company HR policy once the candidate completes probation.</span>
                    </div>
                </div>

                <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner -->
                <div class="modal-footer border-top py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Referral
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.08) !important;
    }
    .transition-colors {
        transition: background-color 0.15s ease-in-out;
    }
    #referralTable th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quick filter tabs
        const filterTabs = document.querySelectorAll('#referralStatusTabs button');
        const rows = document.querySelectorAll('.referral-row');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                filterTabs.forEach(t => t.classList.remove('active', 'btn-primary'));
                filterTabs.forEach(t => t.classList.add('btn-outline-secondary'));
                this.classList.remove('btn-outline-secondary');
                this.classList.add('active', 'btn-primary');

                const filterVal = this.getAttribute('data-ref-status');
                rows.forEach(row => {
                    const rowCat = row.getAttribute('data-ref-category');
                    if (filterVal === 'all' || rowCat === filterVal) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Instant search filtering
        const searchInput = document.getElementById('referralTableSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }

        // Re-open modal if validation errors exist (Rule 10)
        @if(isset($errors) && $errors->any())
            const referralModalEl = document.getElementById('referralModal');
            if (referralModalEl && typeof bootstrap !== 'undefined') {
                const modalInstance = new bootstrap.Modal(referralModalEl);
                modalInstance.show();
            }
        @endif
    });
</script>
@endpush
