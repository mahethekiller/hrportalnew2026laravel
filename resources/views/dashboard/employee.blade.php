@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')

@php
    $emp = auth()->user()?->employee;
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

<div class="container-fluid px-0">
    <!-- Header Title Banner (Rule 13 Benchmark) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-9 font-monospace">
                    <i class="fa-solid fa-id-badge me-1"></i> Self-Service Workspace • Personal Hub
                </span>
                <span class="text-body-secondary fs-9">• Daily Attendance & Personnel Services</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Welcome back, {{ auth()->user()?->first_name ?? auth()->user()?->name ?? 'Team Member' }} 👋</h4>
            <p class="text-body-secondary fs-8 mb-0">Track your daily attendance sessions, submit leave requests, download monthly payslips, and review company broadcasts.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <a href="{{ route('my-portal.profile-update') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-user-pen me-1.5 text-primary"></i> My Profile
            </a>
            <a href="{{ route('my-portal.leaves') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs">
                <i class="fa-solid fa-calendar-plus me-1.5"></i> Apply for Leave
            </a>
        </div>
    </div>

    <!-- Celebration Banner (Birthday / Anniversary) -->
    @if($isBirthday || $isAnniversary)
        <div class="card border-0 shadow-sm mb-4 text-white rounded-3 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-4 mb-2 pointer-events-none" style="font-size: 7rem;">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-20 p-3 rounded-circle text-white fs-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-cake-candles animate-bounce"></i>
                    </div>
                    <div>
                        @if($isBirthday && $isAnniversary)
                            <h5 class="fw-bolder mb-1 text-white">🎉 Double Celebration Today! Happy Birthday & Work Anniversary!</h5>
                            <p class="mb-0 fs-8 text-white-50">Today marks your special day and another amazing milestone year with our organization. Thank you for your dedication!</p>
                        @elseif($isBirthday)
                            <h5 class="fw-bolder mb-1 text-white">🎂 Happy Birthday, {{ auth()->user()?->first_name ?? 'Team Member' }}!</h5>
                            <p class="mb-0 fs-8 text-white-50">Wishing you a fantastic day filled with joy, happiness, and continued success. Have a wonderful celebration!</p>
                        @else
                            <h5 class="fw-bolder mb-1 text-white">🏆 Happy Work Anniversary!</h5>
                            <p class="mb-0 fs-8 text-white-50">Congratulations on another milestone year with the company! We truly appreciate your hard work and contribution.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-check fs-5 me-2.5"></i>
            <span class="fs-8 fw-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center" role="alert">
            <i class="fa-solid fa-triangle-exclamation fs-5 me-2.5"></i>
            <span class="fs-8 fw-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- 4 Telemetry Metrics Cards (Rule 13 Benchmark) -->
    <div class="row g-3 mb-4">
        <!-- Leave Requests -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Leave Applications</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ count($leaves) }}</h3>
                        <span class="fs-9 text-body-secondary">Recent ESS logs</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-calendar-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- My Payslips -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Salary Slips</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ count($payslips) }}</h3>
                        <span class="fs-9 text-body-secondary">Generated payroll records</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-wallet fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Meetings Today -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Meetings Today</span>
                        <h3 class="fw-bolder text-warning mb-1 mt-1">{{ count($meetings) }}</h3>
                        <span class="fs-9 text-body-secondary">Scheduled calendar events</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-handshake fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Broadcasts -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Broadcasts</span>
                        <h3 class="fw-bolder text-danger mb-1 mt-1">{{ count($announcements) }}</h3>
                        <span class="fs-9 text-body-secondary">Company notices & updates</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-bullhorn fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Main Studio Grid -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Interactive Attendance / Clock In Widget -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary overflow-hidden">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-body-emphasis fs-6 mb-1">
                            <i class="fa-solid fa-business-time text-primary me-2"></i> Daily Work Session Hub
                        </h5>
                        <p class="text-body-secondary fs-8 mb-0">Record your remote telecommuting attendance or verify your biometric office check-in.</p>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 font-monospace">
                        <span class="badge-pulse-dot bg-success me-1"></span> Live Punch Terminal
                    </span>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <div class="p-3.5 border border-subtle rounded-3 bg-body mb-3">
                        <div class="row align-items-center g-3">
                            <div class="col-md-7">
                                @if($activeWfh)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-md rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                                            <i class="fa-solid fa-house-laptop fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-0.5">
                                                <span class="fw-bold text-body-emphasis fs-8">WFH Session in Progress</span>
                                                <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 fs-9 animate-pulse">Active</span>
                                            </div>
                                            <span class="fs-9 text-body-secondary d-block">
                                                <i class="fa-solid fa-clock me-1 text-warning"></i> Clocked in at {{ \Carbon\Carbon::parse($activeWfh->clock_in)->format('h:i A') }}
                                            </span>
                                            @if($activeWfh->clean_description)
                                                <div class="mt-1.5 p-2 rounded bg-body-tertiary text-body-secondary fs-9 border border-subtle">
                                                    <i class="fa-solid fa-quote-left me-1 text-primary opacity-50"></i> {{ $activeWfh->clean_description }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($todayOfficePunch)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-md rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                                            <i class="fa-solid fa-building-circle-check fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-0.5">
                                                <span class="fw-bold text-body-emphasis fs-8">Office Session Clocked In</span>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-9">Verified</span>
                                            </div>
                                            <span class="fs-9 text-body-secondary">
                                                <i class="fa-solid fa-fingerprint me-1 text-success"></i> Check-in recorded at {{ \Carbon\Carbon::parse($todayOfficePunch->check_in_time ?? $todayOfficePunch->check_in_datetime)->format('h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-md rounded-circle bg-body-secondary text-body-secondary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                                            <i class="fa-solid fa-hourglass-start fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-body-emphasis fs-8 d-block mb-0.5">No Active Session Logged Today</span>
                                            <span class="fs-9 text-body-secondary">Begin your telecommuting day by submitting a work description below.</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-5 text-md-end">
                                @if($activeWfh)
                                    <form action="{{ route('attendance.wfh-clock-out') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="clocking_id" value="{{ $activeWfh->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold px-3 py-2 rounded-2 w-100 shadow-xs">
                                            <i class="fa-solid fa-right-from-bracket me-1.5"></i> Clock Out (WFH)
                                        </button>
                                    </form>
                                @elseif(!$todayOfficePunch)
                                    <button class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 w-100 shadow-xs" data-bs-toggle="collapse" data-bs-target="#wfhClockInCollapse">
                                        <i class="fa-solid fa-house-laptop me-1.5"></i> Start WFH Session
                                    </button>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2 px-3 py-2 fs-9 w-100 d-inline-block text-center font-monospace">
                                        <i class="fa-solid fa-circle-check me-1"></i> Checked In (Office Terminal)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Clock-in Collapse Form -->
                    <div class="collapse" id="wfhClockInCollapse">
                        <div class="p-3.5 border border-subtle rounded-3 bg-body">
                            <form action="{{ route('attendance.wfh-clock-in') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fs-9 fw-bold text-body-emphasis">
                                        Today's WFH Task Plan / Deliverables <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" class="form-control form-control-sm bg-body-tertiary text-body-emphasis border-subtle" rows="2" required placeholder="e.g. Completing sprint deliverables, resolving tickets, and attending client syncs..."></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-body border text-body-emphasis px-3 rounded-2 fs-9" data-bs-toggle="collapse" data-bs-target="#wfhClockInCollapse">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-circle-check me-1"></i> Confirm Clock In
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Corporate Announcements Feed -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary overflow-hidden">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold text-body-emphasis fs-6 mb-1">
                            <i class="fa-solid fa-bullhorn text-danger me-2"></i> Corporate Broadcasts & Notices
                        </h5>
                        <p class="text-body-secondary fs-8 mb-0">Official company announcements, policy releases, and organization updates.</p>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fs-9">
                        <i class="fa-solid fa-newspaper me-1"></i> Latest News
                    </span>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    @forelse($announcements as $anc)
                        <div class="p-3 border border-subtle rounded-3 mb-3 bg-body transition-all hover-shadow">
                            <div class="d-flex align-items-center justify-content-between mb-1.5 flex-wrap gap-2">
                                <h6 class="fw-bold text-body-emphasis mb-0 fs-8">{{ $anc->title }}</h6>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 fs-9">
                                    {{ $anc->announcement_type ?? 'Company Notice' }}
                                </span>
                            </div>
                            <p class="text-body-secondary fs-9 mb-2.5">{{ Str::limit($anc->summary, 120) }}</p>
                            <a href="{{ route('announcements.show', $anc->announcement_id) }}" class="btn btn-sm btn-body border text-body-emphasis px-2.5 py-1 rounded-2 fs-9 fw-semibold shadow-xs">
                                <i class="fa-solid fa-arrow-right me-1 text-primary"></i> Read Full Post
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-4 text-body-secondary">
                            <i class="fa-solid fa-bullhorn fs-2 mb-2 text-secondary opacity-50 d-block"></i>
                            <h6 class="fw-bold text-body-emphasis fs-8 mb-1">No Active Broadcasts</h6>
                            <span class="fs-9">There are currently no company announcements or policy updates posted.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- My Leaves History Table -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary overflow-hidden">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold text-body-emphasis fs-6 mb-1">
                            <i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Recent Leave Applications
                        </h5>
                        <p class="text-body-secondary fs-8 mb-0">Track status and review outcomes of recent time-off requests.</p>
                    </div>
                    <a href="{{ route('my-portal.leaves') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs px-2.5 py-1 rounded-2 fs-9 fw-semibold">
                        <i class="fa-solid fa-plus me-1 text-primary"></i> Apply Leave
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 fs-8">
                            <thead class="bg-body-secondary">
                                <tr>
                                    <th class="ps-4 text-body-secondary fw-semibold">Leave Duration</th>
                                    <th class="text-body-secondary fw-semibold">Type</th>
                                    <th class="text-body-secondary fw-semibold">Reason</th>
                                    <th class="pe-4 text-end text-body-secondary fw-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $lv)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="d-block fw-bold text-body-emphasis font-monospace">
                                                {{ \Carbon\Carbon::parse($lv->from_date)->format('M d, Y') }}
                                            </span>
                                            <span class="fs-9 text-body-secondary font-monospace">
                                                to {{ \Carbon\Carbon::parse($lv->to_date)->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-body-emphasis border border-secondary-subtle rounded-pill px-2.5 py-0.5 fs-9">
                                                {{ $lv->leave_type_id == 1 ? 'Casual Leave' : 'Medical Leave' }}
                                            </span>
                                        </td>
                                        <td class="text-body-secondary fs-9">
                                            {{ Str::limit($lv->reason, 40) }}
                                        </td>
                                        <td class="pe-4 text-end">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 fw-bold">
                                                <i class="fa-solid fa-circle-check me-1"></i> {{ $lv->status_label ?? 'Submitted' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-body-secondary">
                                            <i class="fa-solid fa-calendar-xmark fs-2 mb-2 text-secondary opacity-50 d-block"></i>
                                            <h6 class="fw-bold text-body-emphasis fs-8 mb-1">No Leave Records</h6>
                                            <span class="fs-9 d-block mb-3">You have not submitted any leave applications recently.</span>
                                            <a href="{{ route('my-portal.leaves') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-1.5 rounded-2 fs-9 shadow-xs">
                                                <i class="fa-solid fa-calendar-plus me-1"></i> Apply for Leave
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Self-Service Actions Panel -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-body-emphasis fs-7 mb-0">
                        <i class="fa-solid fa-bolt text-warning me-2"></i> Self-Service Desk
                    </h5>
                    <p class="text-body-secondary fs-9 mb-0">Quick shortcuts to employee portals</p>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <div class="d-grid gap-2">
                        <a href="{{ route('my-portal.profile-update') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-user-pen text-primary"></i>
                                <span>Update Profile & Family</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>
                        <a href="{{ route('my-portal.leaves') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-calendar-plus text-success"></i>
                                <span>Apply for Time Off</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>
                        <a href="{{ route('my-portal.payslips') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-wallet text-warning"></i>
                                <span>Download Salary Payslips</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>
                    </div>
                </div>
            </div>

            @include('dashboard.partials.sidebar_widgets')
        </div>
    </div>
</div>
@endsection

