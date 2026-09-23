@extends('layouts.app')

@section('title', 'HR Workstation')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner (Rule 13 Benchmark) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-9 font-monospace">
                    <i class="fa-solid fa-people-roof me-1"></i> People Operations • Workforce Command
                </span>
                <span class="text-body-secondary fs-9">• Operational Workforce Intelligence</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">HR Operations & Workforce Command Center</h4>
            <p class="text-body-secondary fs-8 mb-0">Monitor workforce distribution, process pending team approvals, manage onboarding pipelines, and track daily attendance.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-address-book me-1.5 text-primary"></i> Employee Directory
            </a>
            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs">
                <i class="fa-solid fa-user-plus me-1.5"></i> Register Employee
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-check fs-5 me-2.5"></i>
            <span class="fs-8 fw-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- 4 Telemetry Metrics Cards (Rule 13 Benchmark) -->
    <div class="row g-3 mb-4">
        <!-- Active Headcount -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Active Headcount</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $activeEmployeesCount }}</h3>
                        <span class="fs-9 text-body-secondary">Verified active staff records</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Pending Profile Verifications -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('manager-portal.profile_approvals.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden transition-all hover-shadow">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Profile Verifications</span>
                            <h3 class="fw-bolder text-warning mb-1 mt-1">{{ $pendingProfileUpdates }}</h3>
                            <span class="fs-9 text-body-secondary">
                                @if($pendingProfileUpdates > 0)
                                    <span class="text-warning fw-semibold"><i class="fa-solid fa-circle-exclamation me-1"></i> Requires HR review</span>
                                @else
                                    Queue clear & up to date
                                @endif
                            </span>
                        </div>
                        <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-user-clock fs-5"></i>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
                </div>
            </a>
        </div>

        <!-- Pending Leave Approvals -->
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('manager-portal.team_leaves') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden transition-all hover-shadow">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Leave Applications</span>
                            <h3 class="fw-bolder text-danger mb-1 mt-1">{{ $pendingLeaves }}</h3>
                            <span class="fs-9 text-body-secondary">
                                @if($pendingLeaves > 0)
                                    <span class="text-danger fw-semibold"><i class="fa-solid fa-circle-exclamation me-1"></i> Awaiting sanction</span>
                                @else
                                    All leaves evaluated
                                @endif
                            </span>
                        </div>
                        <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-calendar-check fs-5"></i>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
                </div>
            </a>
        </div>

        <!-- Total Checked In Today -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Present Today</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $todayOfficePunchCount + $todayActiveWfhCount }}</h3>
                        <span class="fs-9 text-body-secondary">WFO & WFH combined check-ins</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-fingerprint fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Main Operations Studio Grid -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Today's Workforce Attendance Breakdown Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4 overflow-hidden">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-body-emphasis fs-6 mb-1">
                            <i class="fa-solid fa-clock-rotate-left text-success me-2"></i> Real-time Workforce Attendance Distribution
                        </h5>
                        <p class="text-body-secondary fs-8 mb-0">Daily active log-in tracking distinguishing on-site punches from remote telecommuting.</p>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 font-monospace">
                        <i class="fa-solid fa-circle-dot fa-beat-fade me-1 text-success"></i> Live Today
                    </span>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    @php
                        $totalCheckIns = $todayOfficePunchCount + $todayActiveWfhCount;
                        $wfoPct = $totalCheckIns > 0 ? round(($todayOfficePunchCount / $totalCheckIns) * 100) : 0;
                        $wfhPct = $totalCheckIns > 0 ? (100 - $wfoPct) : 0;
                    @endphp

                    <!-- Attendance Ratio Bar -->
                    @if($totalCheckIns > 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between text-body-secondary fs-9 fw-semibold mb-1">
                                <span><i class="fa-solid fa-building me-1 text-success"></i> Work From Office ({{ $wfoPct }}%)</span>
                                <span><i class="fa-solid fa-house-laptop me-1 text-warning"></i> Work From Home ({{ $wfhPct }}%)</span>
                            </div>
                            <div class="progress rounded-pill bg-body" style="height: 10px;">
                                <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $wfoPct }}%" aria-valuenow="{{ $wfoPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $wfhPct }}%" aria-valuenow="{{ $wfhPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3.5 border border-subtle rounded-3 bg-body h-100 d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="fa-solid fa-building-user fs-6"></i>
                                        </div>
                                        <div>
                                            <span class="fs-9 text-body-secondary fw-bold text-uppercase tracking-wider d-block">Work From Office (WFO)</span>
                                            <span class="fs-9 text-body-secondary">Biometric terminal & punch logs</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline gap-2 mt-2">
                                    <h3 class="fw-bolder text-success mb-0">{{ $todayOfficePunchCount }}</h3>
                                    <span class="fs-9 text-body-secondary">employees on-site</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3.5 border border-subtle rounded-3 bg-body h-100 d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="fa-solid fa-house-laptop fs-6"></i>
                                        </div>
                                        <div>
                                            <span class="fs-9 text-body-secondary fw-bold text-uppercase tracking-wider d-block">Work From Home (WFH)</span>
                                            <span class="fs-9 text-body-secondary">Logged via Employee Self-Service</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline gap-2 mt-2">
                                    <h3 class="fw-bolder text-warning mb-0">{{ $todayActiveWfhCount }}</h3>
                                    <span class="fs-9 text-body-secondary">active remote sessions</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority Attention Banner (if pending queues exist) -->
            @if($pendingProfileUpdates > 0 || $pendingLeaves > 0)
                <div class="card border-0 shadow-sm rounded-3 bg-warning-subtle border-start border-4 border-warning mb-4">
                    <div class="card-body p-3.5">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fa-solid fa-bell fs-6"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-body-emphasis mb-0.5 fs-8">Action Required: Pending Administrative Queues</h6>
                                    <p class="text-body-secondary fs-9 mb-0">
                                        You have 
                                        @if($pendingProfileUpdates > 0)
                                            <strong>{{ $pendingProfileUpdates }}</strong> profile verification {{ Str::plural('request', $pendingProfileUpdates) }}
                                        @endif
                                        @if($pendingProfileUpdates > 0 && $pendingLeaves > 0) and @endif
                                        @if($pendingLeaves > 0)
                                            <strong>{{ $pendingLeaves }}</strong> leave {{ Str::plural('application', $pendingLeaves) }}
                                        @endif
                                        awaiting review.
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($pendingProfileUpdates > 0)
                                    <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-sm btn-warning text-dark fw-semibold px-2.5 py-1.5 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-user-check me-1"></i> Review Profiles
                                    </a>
                                @endif
                                @if($pendingLeaves > 0)
                                    <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-sm btn-body border text-body-emphasis fw-semibold px-2.5 py-1.5 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-calendar-check me-1 text-danger"></i> Review Leaves
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- HR Operations Hub (Bento Service Grid) -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold text-body-emphasis fs-6 mb-1">
                            <i class="fa-solid fa-circle-nodes text-primary me-2"></i> HR Operations Hub
                        </h5>
                        <p class="text-body-secondary fs-8 mb-0">Direct routes to execute critical people operations workflows.</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-9">
                        <i class="fa-solid fa-bolt me-1"></i> Quick Workflows
                    </span>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <div class="row g-3">
                        <!-- Card 1: Onboard New Hire -->
                        <div class="col-md-6">
                            <div class="p-3 border border-subtle rounded-3 bg-body h-100 d-flex flex-column justify-content-between transition-all">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="avatar-sm rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-user-plus fs-7"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis fs-8 mb-0">Onboard New Staff</h6>
                                    </div>
                                    <p class="text-body-secondary fs-9 mb-3">Create employee skeleton profiles and generate secure passwordless onboarding links.</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary fw-semibold px-2.5 py-1.5 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-plus me-1"></i> Register New
                                    </a>
                                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-body border text-body-emphasis px-2.5 py-1.5 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-link me-1 text-primary"></i> Copy Links
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Team Leave Requests -->
                        <div class="col-md-6">
                            <div class="p-3 border border-subtle rounded-3 bg-body h-100 d-flex flex-column justify-content-between transition-all">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="avatar-sm rounded-2 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-calendar-days fs-7"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis fs-8 mb-0">Leave Approvals Queue</h6>
                                    </div>
                                    <p class="text-body-secondary fs-9 mb-3">Process time-off applications, review manager recommendations, and audit annual quotas.</p>
                                </div>
                                <div>
                                    <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-sm btn-body border text-body-emphasis fw-semibold px-2.5 py-1.5 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-arrow-right me-1 text-danger"></i> Review Leave Queue ({{ $pendingLeaves }})
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Profile Approvals -->
                        <div class="col-md-6">
                            <div class="p-3 border border-subtle rounded-3 bg-body h-100 d-flex flex-column justify-content-between transition-all">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="avatar-sm rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-user-pen fs-7"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis fs-8 mb-0">Profile Data Verifications</h6>
                                    </div>
                                    <p class="text-body-secondary fs-9 mb-3">Validate employee-submitted address changes, family details, and KYC compliance documents.</p>
                                </div>
                                <div>
                                    <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-sm btn-body border text-body-emphasis fw-semibold px-2.5 py-1.5 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-arrow-right me-1 text-warning"></i> Open Approvals ({{ $pendingProfileUpdates }})
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Employee Directory -->
                        <div class="col-md-6">
                            <div class="p-3 border border-subtle rounded-3 bg-body h-100 d-flex flex-column justify-content-between transition-all">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="avatar-sm rounded-2 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-address-book fs-7"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis fs-8 mb-0">Workforce Directory</h6>
                                    </div>
                                    <p class="text-body-secondary fs-9 mb-3">Browse all staff members, filter by department or designation, and view comprehensive dossiers.</p>
                                </div>
                                <div>
                                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-body border text-body-emphasis fw-semibold px-2.5 py-1.5 rounded-2 fs-9 shadow-xs">
                                        <i class="fa-solid fa-arrow-right me-1 text-info"></i> Browse Directory ({{ $activeEmployeesCount }})
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column Sidebar -->
        <div class="col-lg-4">
            <!-- HR Quick Action Desk -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-body-emphasis fs-7 mb-0">
                        <i class="fa-solid fa-list-check text-warning me-2"></i> Operational Queues
                    </h5>
                    <p class="text-body-secondary fs-9 mb-0">Real-time status of pending tasks</p>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <div class="d-grid gap-2">
                        <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-user-check text-warning"></i>
                                <span>Profile Change Queue</span>
                            </div>
                            <span class="badge {{ $pendingProfileUpdates > 0 ? 'bg-warning text-dark' : 'bg-body-secondary text-body-secondary' }} rounded-pill px-2 py-0.5 fs-9">
                                {{ $pendingProfileUpdates }}
                            </span>
                        </a>
                        <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-calendar-check text-danger"></i>
                                <span>Team Leave Queue</span>
                            </div>
                            <span class="badge {{ $pendingLeaves > 0 ? 'bg-danger text-white' : 'bg-body-secondary text-body-secondary' }} rounded-pill px-2 py-0.5 fs-9">
                                {{ $pendingLeaves }}
                            </span>
                        </a>
                        <a href="{{ route('employees.create') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-user-plus text-primary"></i>
                                <span>Register New Employee</span>
                            </div>
                            <i class="fa-solid fa-plus fs-9 text-body-secondary"></i>
                        </a>
                    </div>
                </div>
            </div>

            @include('dashboard.partials.sidebar_widgets')
        </div>
    </div>
</div>
@endsection

