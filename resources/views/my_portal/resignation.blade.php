@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title -->
    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h4 class="mb-1 text-body-emphasis fw-bold">
                <i class="fa-solid fa-right-from-bracket me-2 text-danger"></i> Employee Separation & Exit Formalities
            </h4>
            <p class="text-body-secondary fs-7 mb-0">Submit formal resignation notice, track sequential departmental No-Dues clearance, and download official relieving documents.</p>
        </div>
        <div class="col-sm-4 text-sm-end mt-2 mt-sm-0">
            @if($resignation && (int) $resignation->hr_status === 1)
                <div class="btn-group">
                    <a href="{{ route('my-portal.resignation.relieving_letter', $resignation->resignation_id) }}" class="btn btn-success btn-sm fw-bold me-2">
                        <i class="fa-solid fa-file-pdf me-1"></i> Download Relieving Letter
                    </a>
                    <a href="{{ route('my-portal.resignation.experience_certificate', $resignation->resignation_id) }}" class="btn btn-outline-success btn-sm fw-bold">
                        <i class="fa-solid fa-award me-1"></i> Experience Certificate
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Alert Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Resignation Application Form -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header border-0 pt-4 bg-body-tertiary">
                    <h5 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                        <i class="fa-solid fa-file-signature me-2 text-danger"></i> Resignation Notice Initiation
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Notice Period Rule Callout Banner -->
                    <div class="p-3 rounded bg-primary-subtle border border-primary-subtle mb-4">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-calculator text-primary fs-5"></i>
                            <span class="fw-bold text-primary fs-7">Automated LWD Calculator</span>
                        </div>
                        <div class="text-body-secondary fs-8">
                            Configured Notice Period: <strong>{{ $employee->notice_period_months ?? 1 }} Month(s)</strong><br>
                            Standard Calculated LWD: <strong id="calculatedLwdDisplay" class="text-primary">{{ $calculatedLwd->format('d M Y') }}</strong>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('my-portal.resignation.store') }}" id="resignationForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Notice Date <span class="text-danger">*</span></label>
                            <input type="date" name="notice_date" id="notice_date_input" class="form-control form-control-sm" required value="{{ old('notice_date', $resignation->notice_date ?? date('Y-m-d')) }}" {{ $resignation ? 'readonly' : '' }}>
                            <div class="form-text fs-9">Defaults to today's date.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Requested Last Working Day (LWD) <span class="text-danger">*</span></label>
                            <input type="date" name="resignation_date" id="resignation_date_input" class="form-control form-control-sm" required value="{{ old('resignation_date', $resignation->resignation_date ?? $calculatedLwd->format('Y-m-d')) }}" {{ $resignation ? 'readonly' : '' }}>
                            <div id="shortfallNoticeBadge" class="mt-2" style="display: none;">
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-9">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Early Exit Requested: <strong id="shortfallDaysCount">0</strong> Notice Shortfall Days (FnF Buyout Recovery Applies)
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Reason for Resignation & Handover Notes <span class="text-danger">*</span></label>
                            <textarea name="reason" rows="4" class="form-control form-control-sm" required placeholder="Provide clear reasons for resignation and brief project handover summary..." {{ $resignation ? 'readonly' : '' }}>{{ old('reason', $resignation->plain_reason ?? '') }}</textarea>
                        </div>

                        @if(!$resignation)
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-danger btn-sm fw-bold px-4">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Submit Resignation Notice
                                </button>
                            </div>
                        @else
                            <div class="alert alert-info border-0 mb-0 fs-8">
                                <i class="fa-solid fa-info-circle me-1"></i> Resignation notice already submitted on <strong>{{ $resignation->notice_date }}</strong>.
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Exit Status & Sequential Clearance Tracker -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header border-0 pt-4 bg-body-tertiary d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                        <i class="fa-solid fa-list-check me-2 text-primary"></i> Exit Formalities & Department Clearance
                    </h5>
                    @if($resignation)
                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#exitFormModal">
                            <i class="fa-solid fa-file-upload me-1"></i> Submit Exit & No-Dues Form
                        </button>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if($resignation)
                        <!-- Resignation Status Header Card -->
                        <div class="p-3 rounded bg-body-tertiary border mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <span class="text-body-secondary fs-8">Separation Status:</span>
                                    <div class="fs-5 fw-bold text-body-emphasis">
                                        @if($resignation->status === 'Completed' || $resignation->status === 'Relieved')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-circle-check me-1"></i> Completed / Relieved</span>
                                        @elseif($resignation->status === 'Approved')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="fa-solid fa-thumbs-up me-1"></i> Manager Approved</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="fa-solid fa-clock me-1"></i> Pending Review</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <div class="fs-8 text-body-secondary">
                                        Notice Date: <strong>{{ $resignation->notice_date }}</strong><br>
                                        Confirmed LWD: <strong class="text-danger">{{ $resignation->resignation_date }}</strong>
                                        @if($resignation->shortfall_days > 0)
                                            <div class="text-warning fs-9 fw-bold">Shortfall: {{ $resignation->shortfall_days }} Day(s)</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4-Stage Sequential Clearance Checkpoints -->
                        <h6 class="fw-bold text-body-emphasis fs-7 mb-3">
                            <i class="fa-solid fa-sitemap me-1 text-primary"></i> 4-Stage Department Clearance Workflow
                        </h6>
                        <div class="list-group list-group-flush mb-4">
                            <!-- Stage 1: Reporting Manager Clearance -->
                            @php $mgrHelper = $resignation->getStageStatusHelper((int) $resignation->manager_status); @endphp
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="fw-bold text-body-emphasis"><i class="fa-solid fa-user-tie text-primary me-2"></i> Stage 1: Reporting Manager Clearance</span>
                                        @if($resignation->managerPerson)
                                            <div class="fs-9 text-body-secondary mt-1">
                                                <i class="fa-solid fa-user-check me-1"></i> Reviewed by: <strong>{{ $resignation->managerPerson->first_name }} {{ $resignation->managerPerson->last_name }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="{{ $mgrHelper['class'] }} fs-8"><i class="fa-solid {{ $mgrHelper['icon'] }} me-1"></i> {{ $mgrHelper['label'] }}</span>
                                </div>
                                @if(!empty($resignation->manager_comment))
                                    <div class="p-2 rounded bg-body-tertiary fs-8 text-body-secondary mt-2 border">
                                        <strong>Remarks:</strong> {!! $resignation->clean_manager_comment !!}
                                    </div>
                                @endif
                            </div>

                            <!-- Stage 2: IT Department Clearance -->
                            @php $itHelper = $resignation->getStageStatusHelper((int) $resignation->it_status); @endphp
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="fw-bold text-body-emphasis"><i class="fa-solid fa-laptop text-info me-2"></i> Stage 2: IT Department Assets Clearance</span>
                                        @if($resignation->itPerson)
                                            <div class="fs-9 text-body-secondary mt-1">
                                                <i class="fa-solid fa-user-check me-1"></i> Reviewed by: <strong>{{ $resignation->itPerson->first_name }} {{ $resignation->itPerson->last_name }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="{{ $itHelper['class'] }} fs-8"><i class="fa-solid {{ $itHelper['icon'] }} me-1"></i> {{ $itHelper['label'] }}</span>
                                </div>
                                @if(!empty($resignation->it_comment))
                                    <div class="p-2 rounded bg-body-tertiary fs-8 text-body-secondary mt-2 border">
                                        <strong>Remarks:</strong> {!! $resignation->clean_it_comment !!}
                                    </div>
                                @endif
                            </div>

                            <!-- Stage 3: Accounts Department Clearance -->
                            @php $accHelper = $resignation->getStageStatusHelper((int) $resignation->account_status); @endphp
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="fw-bold text-body-emphasis"><i class="fa-solid fa-money-bill-wave text-success me-2"></i> Stage 3: Accounts Full & Final (FnF) Settlement</span>
                                        @if($resignation->accountPerson)
                                            <div class="fs-9 text-body-secondary mt-1">
                                                <i class="fa-solid fa-user-check me-1"></i> Reviewed by: <strong>{{ $resignation->accountPerson->first_name }} {{ $resignation->accountPerson->last_name }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="{{ $accHelper['class'] }} fs-8"><i class="fa-solid {{ $accHelper['icon'] }} me-1"></i> {{ $accHelper['label'] }}</span>
                                </div>
                                @if(!empty($resignation->account_comment))
                                    <div class="p-2 rounded bg-body-tertiary fs-8 text-body-secondary mt-2 border">
                                        <strong>Remarks:</strong> {!! $resignation->clean_account_comment !!}
                                    </div>
                                @endif
                            </div>

                            <!-- Stage 4: HR Department Clearance -->
                            @php $hrHelper = $resignation->getStageStatusHelper((int) $resignation->hr_status); @endphp
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="fw-bold text-body-emphasis"><i class="fa-solid fa-award text-purple me-2"></i> Stage 4: HR Final Clearance & Relieving Certificate</span>
                                        @if($resignation->hrPerson)
                                            <div class="fs-9 text-body-secondary mt-1">
                                                <i class="fa-solid fa-user-check me-1"></i> Reviewed by: <strong>{{ $resignation->hrPerson->first_name }} {{ $resignation->hrPerson->last_name }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="{{ $hrHelper['class'] }} fs-8"><i class="fa-solid {{ $hrHelper['icon'] }} me-1"></i> {{ $hrHelper['label'] }}</span>
                                </div>
                                @if(!empty($resignation->hr_comment))
                                    <div class="p-2 rounded bg-body-tertiary fs-8 text-body-secondary mt-2 border">
                                        <strong>Remarks:</strong> {!! $resignation->clean_hr_comment !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-circle-info fa-3x text-body-secondary mb-3"></i>
                            <h6 class="fw-bold text-body-emphasis">No Active Resignation Notice</h6>
                            <p class="text-body-secondary fs-8 mb-0">Fill out the form on the left to initiate your separation workflow.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Submit Exit Questionnaire & Itemized Asset Return Form -->
@if($resignation)
<div class="modal fade" id="exitFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('my-portal.resignation.exit_form') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="resignation_id" value="{{ $resignation->resignation_id }}">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-clipboard-check text-primary me-2"></i> Exit Questionnaire & Itemized Asset Checklist
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Asset Handover Checklist -->
                    <h6 class="fw-bold text-body-emphasis fs-8 mb-3">Itemized Asset Return Checklist</h6>
                    <div class="row g-2 mb-4 p-3 rounded bg-body-tertiary border">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="asset_laptop" id="chkLaptop" value="1" checked>
                                <label class="form-check-label fs-8" for="chkLaptop">Laptop / Desktop & Charger Handover</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="asset_idcard" id="chkIdCard" value="1" checked>
                                <label class="form-check-label fs-8" for="chkIdCard">Employee ID Badge & Access Card Return</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="asset_sim" id="chkSim" value="1">
                                <label class="form-check-label fs-8" for="chkSim">Company SIM Card / Handset Return</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="asset_keys" id="chkKeys" value="1">
                                <label class="form-check-label fs-8" for="chkKeys">Cabinet Keys & Office Storage Keys Return</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="asset_files" id="chkFiles" value="1" checked>
                                <label class="form-check-label fs-8" for="chkFiles">Project Files, Source Repositories & Credentials Handover</label>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Signed No-Dues PDF -->
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Signed No-Dues Document / File Upload</label>
                        <input type="file" name="exit_form_file" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <div class="form-text fs-9">Upload signed No-Dues form or supporting handover documentation (Max 5MB).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis">Detailed Handover Summary & Remarks</label>
                        <textarea name="handover_summary" rows="3" class="form-control form-control-sm" placeholder="Summarize project file locations, replacement contacts, and pending task statuses..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Submit Exit & No-Dues Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- JavaScript Notice Period Live Calculator -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const noticeDateInput = document.getElementById('notice_date_input');
    const resignationDateInput = document.getElementById('resignation_date_input');
    const calculatedLwdDisplay = document.getElementById('calculatedLwdDisplay');
    const shortfallNoticeBadge = document.getElementById('shortfallNoticeBadge');
    const shortfallDaysCount = document.getElementById('shortfallDaysCount');

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
                    shortfallDaysCount.innerText = diffDays;
                    shortfallNoticeBadge.style.display = 'block';
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
});
</script>
@endsection
