@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-right-from-bracket me-2 text-danger"></i> Resignation Notice & Exit Clearance</h4>
        <p class="text-muted fs-8 mb-0">Submit formal resignation notice, specify last working day, and track exit clearance.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header border-0 pt-3 bg-white">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-file-signature me-2 text-danger"></i> Resignation Application Form</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('my-portal.resignation.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Notice Date <span class="text-danger">*</span></label>
                            <input type="date" name="notice_date" class="form-control form-control-sm" required value="{{ old('notice_date', $resignation->notice_date ?? date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Requested Last Working Day <span class="text-danger">*</span></label>
                            <input type="date" name="resignation_date" class="form-control form-control-sm" required value="{{ old('resignation_date', $resignation->resignation_date ?? date('Y-m-d', strtotime('+30 days'))) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fs-8 fw-semibold">Reason for Resignation & Handover Details <span class="text-danger">*</span></label>
                            <textarea name="reason" rows="4" class="form-control form-control-sm" required placeholder="Detailed reason for resignation and proposed project handover timeline...">{{ old('reason', $resignation->reason ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-danger btn-sm fw-bold px-4">
                            <i class="fa-solid fa-paper-plane me-1"></i> Submit Resignation Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header border-0 pt-3 bg-white">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-list-check me-2 text-primary"></i> Exit Clearance & Status Tracker</h5>
            </div>
            <div class="card-body p-4">
                @if($resignation)
                    <div class="mb-4">
                        <span class="text-muted fs-8">Current Application Status:</span>
                        <h4 class="fw-bold text-warning mb-1">{{ $resignation->status }}</h4>
                        <span class="fs-9 text-muted"><i class="fa-regular fa-clock me-1"></i> Notice Date: {{ $resignation->notice_date }} | Target Exit: {{ $resignation->resignation_date }}</span>
                    </div>

                    <h6 class="fw-bold text-gray-900 fs-8 mb-3">Clearance Department Checkpoints:</h6>
                    <ul class="list-group list-group-flush fs-8">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa-solid fa-user-tie text-primary me-2"></i> Reporting Manager Handover</span>
                            <span class="badge bg-soft-warning text-warning">Pending Review</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa-solid fa-laptop text-info me-2"></i> IT Assets Return (Laptop/Access)</span>
                            <span class="badge bg-soft-secondary text-secondary">Awaiting Last Day</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa-solid fa-file-invoice-dollar text-success me-2"></i> Finance Full & Final (FnF) Settlement</span>
                            <span class="badge bg-soft-secondary text-secondary">Awaiting Clearance</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa-solid fa-user-shield text-danger me-2"></i> HR Relieving & Experience Certificate</span>
                            <span class="badge bg-soft-secondary text-secondary">Awaiting Clearance</span>
                        </li>
                    </ul>
                @else
                    <div class="text-center py-4">
                        <i class="fa-solid fa-circle-info fa-2x text-muted mb-2"></i>
                        <p class="text-muted fs-8 mb-0">No active resignation notice filed. Submit the form on the left to initiate your exit clearance workflow.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
