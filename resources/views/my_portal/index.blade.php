@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-user-gear me-2 text-primary"></i> Employee Self-Service Dashboard</h4>
        <p class="text-muted fs-8 mb-0">Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name ?? 'Employee' }}! Here is your personal activity hub.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">My Leave Applications</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ count($leaves) }}</h3>
                </div>
                <div class="bg-light-primary text-primary p-3 rounded-circle">
                    <i class="fa-solid fa-calendar-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">My Payslips</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ count($payslips) }}</h3>
                </div>
                <div class="bg-light-success text-success p-3 rounded-circle">
                    <i class="fa-solid fa-wallet fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Booked Meetings</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ count($meetings) }}</h3>
                </div>
                <div class="bg-light-warning text-warning p-3 rounded-circle">
                    <i class="fa-solid fa-handshake fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Company Broadcasts</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ count($announcements) }}</h3>
                </div>
                <div class="bg-light-danger text-danger p-3 rounded-circle">
                    <i class="fa-solid fa-bullhorn fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-0 pt-3 bg-white">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-bullhorn me-2 text-danger"></i> Corporate Announcements</h5>
            </div>
            <div class="card-body p-4">
                @forelse($announcements as $anc)
                    <div class="p-3 border rounded-3 mb-3 bg-light">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-gray-900 mb-0">{{ $anc->title }}</h6>
                            <span class="badge bg-danger fs-9">{{ $anc->announcement_type }}</span>
                        </div>
                        <p class="text-muted fs-8 mb-2">{{ $anc->summary }}</p>
                        <a href="{{ route('announcements.show', $anc->announcement_id) }}" class="btn btn-light-primary btn-sm fs-9 fw-bold">Read Announcement</a>
                    </div>
                @empty
                    <p class="text-muted fs-8 mb-0">No active announcements.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header border-0 pt-3 bg-white">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-bolt me-2 text-warning"></i> Quick Self-Service Actions</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('my-portal.profile-update') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-user-pen me-2"></i> Update Profile & Family Info
                    </a>
                    <a href="{{ route('my-portal.performance_feedback') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-star me-2"></i> Performance Self-Rating
                    </a>
                    <a href="{{ route('my-portal.benefits') }}" class="btn btn-light-success text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-shield-heart me-2"></i> Corporate Benefits & Policies
                    </a>
                    <a href="{{ route('my-portal.referrals') }}" class="btn btn-light-info text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-user-plus me-2"></i> Refer Candidate
                    </a>
                    <a href="{{ route('my-portal.meetings') }}" class="btn btn-light-warning text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-door-open me-2"></i> Book Conference Room
                    </a>
                    <a href="{{ route('my-portal.conveyance') }}" class="btn btn-light-secondary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-car me-2"></i> Conveyance Reimbursements
                    </a>
                    <a href="{{ route('my-portal.tax_documents') }}" class="btn btn-light-danger text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-file-arrow-up me-2"></i> Tax Document Uploads
                    </a>
                    <a href="{{ route('my-portal.resignation') }}" class="btn btn-light text-dark text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Resignation & Exit Clearance
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
