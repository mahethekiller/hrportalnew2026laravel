@extends('layouts.app')

@section('title', 'Employee Dashboard')
@section('page_title', 'My Self-Service Dashboard')

@section('content')

@php
    $emp = auth()->user()->employee;
    $isBirthday = false;
    $isAnniversary = false;
    if ($emp) {
        if (!empty($emp->date_of_birth)) {
            $isBirthday = \Carbon\Carbon::parse($emp->date_of_birth)->format('m-d') === today()->format('m-d');
        }
        if (!empty($emp->date_of_joining)) {
            $isAnniversary = \Carbon\Carbon::parse($emp->date_of_joining)->format('m-d') === today()->format('m-d');
        }
    }
@endphp

<!-- Anniversary / Birthday Confetti Celebration Banner -->
@if($isBirthday || $isAnniversary)
    <div class="card border-0 shadow-sm mb-4 bg-gradient text-white rounded-3 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 position-relative">
            <div class="position-absolute end-0 bottom-0 opacity-10 me-3 mb-2" style="font-size: 8rem;">
                <i class="fa-solid fa-gift"></i>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-20 p-3 rounded-circle text-white fs-2">
                    <i class="fa-solid fa-cake-candles animate-bounce"></i>
                </div>
                <div>
                    @if($isBirthday && $isAnniversary)
                        <h4 class="fw-bolder mb-1">🎉 Double Celebration Today! Happy Birthday & Work Anniversary! 🎉</h4>
                        <p class="mb-0 fs-8 opacity-90">Today marks your special day and another amazing milestone year with our organization. Thank you for your dediciation!</p>
                    @elseif($isBirthday)
                        <h4 class="fw-bolder mb-1">🎂 Happy Birthday, {{ auth()->user()->first_name }}! 🎂</h4>
                        <p class="mb-0 fs-8 opacity-90">Wishing you a fantastic day filled with joy, laughter, and success. Have a wonderful celebration!</p>
                    @else
                        <h4 class="fw-bolder mb-1">🏆 Happy Work Anniversary! 🏆</h4>
                        <p class="mb-0 fs-8 opacity-90">Congratulations on another successful year with the company! We truly appreciate your contributions and hard work.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
    </div>
@endif

<!-- Quick Metrics Overview -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">Leave Requests</span>
                    <h3 class="fw-bolder text-body-emphasis mb-0 mt-1">{{ count($leaves) }}</h3>
                    <span class="badge bg-primary-subtle text-primary fw-bold fs-9 mt-1">
                        <i class="fa-solid fa-calendar-check me-1"></i> ESS Log
                    </span>
                </div>
                <div class="avatar-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center p-3" style="width: 52px; height: 52px;">
                    <i class="fa-solid fa-calendar-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">My Payslips</span>
                    <h3 class="fw-bolder text-body-emphasis mb-0 mt-1">{{ count($payslips) }}</h3>
                    <span class="badge bg-success-subtle text-success fw-bold fs-9 mt-1">
                        <i class="fa-solid fa-shield-check me-1"></i> Verified
                    </span>
                </div>
                <div class="avatar-md rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center p-3" style="width: 52px; height: 52px;">
                    <i class="fa-solid fa-wallet fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">Meetings Today</span>
                    <h3 class="fw-bolder text-body-emphasis mb-0 mt-1">{{ count($meetings) }}</h3>
                    <span class="badge bg-warning-subtle text-warning fw-bold fs-9 mt-1">
                        <i class="fa-solid fa-clock me-1"></i> Scheduled
                    </span>
                </div>
                <div class="avatar-md rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center p-3" style="width: 52px; height: 52px;">
                    <i class="fa-solid fa-handshake fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">Broadcasts</span>
                    <h3 class="fw-bolder text-body-emphasis mb-0 mt-1">{{ count($announcements) }}</h3>
                    <span class="badge bg-danger-subtle text-danger fw-bold fs-9 mt-1">
                        <i class="fa-solid fa-bullhorn me-1"></i> Corporate
                    </span>
                </div>
                <div class="avatar-md rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center p-3" style="width: 52px; height: 52px;">
                    <i class="fa-solid fa-bullhorn fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        
        <!-- Interactive Attendance / Clock In Widget -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
            <div class="card-header border-0 bg-transparent pt-4 pb-0 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-business-time me-2 text-primary"></i> Daily Work Session</h5>
                    <p class="text-body-secondary fs-9 mb-0">Clock in to log your Work From Home sessions or verify your office check-in.</p>
                </div>
                <span class="badge bg-success-subtle text-success fs-9 fw-bold px-2 py-1">
                    <span class="badge-pulse-dot bg-success me-1"></span> Live Punch System
                </span>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-3 mb-md-0">
                        @if($activeWfh)
                            <div class="p-3 border border-warning-subtle rounded-3 bg-warning-subtle bg-opacity-20 d-flex gap-3 align-items-center">
                                <div class="spinner-grow text-warning" role="status" style="width: 1.5rem; height: 1.5rem;"></div>
                                <div>
                                    <span class="d-block fw-bold text-body-emphasis fs-8">WFH Session Active</span>
                                    <span class="fs-9 text-body-secondary">Clocked in at {{ \Carbon\Carbon::parse($activeWfh->clock_in)->format('h:i A') }}</span>
                                    <span class="d-block fs-9 text-body-secondary mt-1"><i class="fa-solid fa-quote-left me-1"></i> {{ $activeWfh->clean_description }}</span>
                                </div>
                            </div>
                        @elseif($todayOfficePunch)
                            <div class="p-3 border border-success-subtle rounded-3 bg-success-subtle bg-opacity-20 d-flex gap-3 align-items-center">
                                <div class="bg-success text-white p-2 rounded-circle fs-8"><i class="fa-solid fa-circle-check"></i></div>
                                <div>
                                    <span class="d-block fw-bold text-body-emphasis fs-8">Office Session Clocked In</span>
                                    <span class="fs-9 text-body-secondary">Check-in recorded at {{ \Carbon\Carbon::parse($todayOfficePunch->check_in_time ?? $todayOfficePunch->check_in_datetime)->format('h:i A') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="p-3 border border-subtle rounded-3 bg-body d-flex gap-3 align-items-center">
                                <div class="bg-secondary-subtle text-secondary p-2 rounded-circle fs-8"><i class="fa-solid fa-hourglass-start"></i></div>
                                <div>
                                    <span class="d-block fw-bold text-body-emphasis fs-8">No Active Work Session</span>
                                    <span class="fs-9 text-body-secondary">Submit a session log below to start your WFH work day.</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-5 text-md-end">
                        @if($activeWfh)
                            <form action="{{ route('attendance.wfh-clock-out') }}" method="POST">
                                @csrf
                                <input type="hidden" name="clocking_id" value="{{ $activeWfh->id }}">
                                <button type="submit" class="btn btn-danger fw-bold py-2 w-100 fs-8 shadow-sm">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Clock Out (WFH)
                                </button>
                            </form>
                        @elseif(!$todayOfficePunch)
                            <button class="btn btn-primary fw-bold py-2 w-100 fs-8 shadow-sm" data-bs-toggle="collapse" data-bs-target="#wfhClockInCollapse">
                                <i class="fa-solid fa-house-laptop me-1"></i> Start WFH Session
                            </button>
                        @else
                            <button class="btn btn-outline-secondary btn-sm w-100 fs-9 fw-semibold" disabled>
                                Checked In (Office)
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Clock-in Collapse Form -->
                <div class="collapse mt-3" id="wfhClockInCollapse">
                    <div class="p-3 border border-subtle rounded-3 bg-body">
                        <form action="{{ route('attendance.wfh-clock-in') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fs-9 fw-bold text-body-emphasis">WFH Work Description / Task Plan <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control fs-8 bg-body-tertiary text-body-emphasis" rows="2" required placeholder="e.g. Working on bug fixes for core HR modules and dashboard updates..."></textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light btn-sm fs-9" data-bs-toggle="collapse" data-bs-target="#wfhClockInCollapse">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm fs-9 fw-bold shadow-sm">Confirm Clock In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Corporate Announcements -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-bullhorn me-2 text-danger"></i> Corporate Broadcasts</h5>
            </div>
            <div class="card-body">
                @forelse($announcements as $anc)
                    <div class="p-3 border border-subtle rounded-3 mb-3 bg-body">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-body-emphasis mb-0 fs-8">{{ $anc->title }}</h6>
                            <span class="badge bg-danger-subtle text-danger fs-9 fw-bold">{{ $anc->announcement_type }}</span>
                        </div>
                        <p class="text-body-secondary fs-8 mb-2">{{ $anc->summary }}</p>
                        <a href="{{ route('announcements.show', $anc->announcement_id) }}" class="btn btn-primary-subtle text-primary btn-sm fs-9 fw-bold py-1">Read Post</a>
                    </div>
                @empty
                    <x-empty-state 
                        icon="fa-solid fa-bullhorn" 
                        title="No Active Broadcasts" 
                        description="There are currently no corporate announcements or policy updates broadcasted." 
                    />
                @endforelse
            </div>
        </div>

        <!-- My Leaves History -->
        <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary dashboard-card">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-info"></i> Recent Leave Applications</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 fs-8">
                        <thead class="bg-body-secondary">
                            <tr>
                                <th class="ps-4">Leave Duration</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $lv)
                                <tr>
                                    <td class="ps-4">
                                        <span class="d-block fw-bold text-body-emphasis">{{ \Carbon\Carbon::parse($lv->from_date)->format('M d, Y') }}</span>
                                        <span class="fs-9 text-body-secondary">to {{ \Carbon\Carbon::parse($lv->to_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-secondary fs-9 fw-bold">{{ $lv->leave_type_id == 1 ? 'Casual' : 'Medical' }}</span></td>
                                    <td class="text-body-secondary">{{ Str::limit($lv->reason, 35) }}</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success fw-bold fs-9">{{ $lv->status_label }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <x-empty-state 
                                            icon="fa-solid fa-calendar-xmark" 
                                            title="No Leave Records" 
                                            description="You have not submitted any leave applications recently."
                                            actionUrl="{{ route('my-portal.leaves') }}"
                                            actionText="Apply for Leave"
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column Sidebar Widgets -->
    <div class="col-lg-4">
        
        <!-- Quick Actions Panel -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-body-emphasis fs-7 mb-0"><i class="fa-solid fa-bolt text-warning me-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('my-portal.profile-update') }}" class="btn btn-body text-start fs-8 fw-semibold py-2 border border-subtle text-body-emphasis shadow-xs">
                        <i class="fa-solid fa-user-pen me-2 text-primary"></i> Update Profile & Family
                    </a>
                    <a href="{{ route('my-portal.leaves') }}" class="btn btn-body text-start fs-8 fw-semibold py-2 border border-subtle text-body-emphasis shadow-xs">
                        <i class="fa-solid fa-calendar-plus me-2 text-success"></i> Apply For Leave
                    </a>
                    <a href="{{ route('my-portal.payslips') }}" class="btn btn-body text-start fs-8 fw-semibold py-2 border border-subtle text-body-emphasis shadow-xs">
                        <i class="fa-solid fa-wallet me-2 text-warning"></i> Download Payslips
                    </a>
                </div>
            </div>
        </div>

        @include('dashboard.partials.sidebar_widgets')
    </div>
</div>

@endsection
