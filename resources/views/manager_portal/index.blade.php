@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-users-gear me-1"></i> Leadership Workspace
                </span>
                <span class="text-body-secondary fs-9">• Direct Management Hub</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Manager Team Workstation</h4>
            <p class="text-body-secondary fs-8 mb-0">Oversee your direct reports, approve time-off requests, track daily punches, and manage offboarding.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('manager-portal.team_attendance') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-clock me-1.5 text-primary"></i> Team Timesheets
            </a>
            <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs">
                <i class="fa-solid fa-calendar-check me-1.5"></i> Leave Queue
                @if(($stats['pending_leaves'] ?? 0) > 0)
                    <span class="badge bg-white text-primary ms-1.5 px-1.5 py-0.5 rounded-pill fs-9 fw-bold">{{ $stats['pending_leaves'] }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- 4 Modern Metric KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Direct Team Members -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Direct Reports</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $stats['total_team'] ?? count($teamMembers) }}</h3>
                        <span class="fs-9 text-body-secondary">Active staff members</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Pending Leave Approvals -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Pending Leaves</span>
                        <h3 class="fw-bolder {{ ($stats['pending_leaves'] ?? 0) > 0 ? 'text-warning' : 'text-body-emphasis' }} mb-1 mt-1">
                            {{ $stats['pending_leaves'] ?? 0 }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Awaiting manager decision</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Pending Profile Updates -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Profile Verifications</span>
                        <h3 class="fw-bolder {{ ($stats['pending_profiles'] ?? 0) > 0 ? 'text-info' : 'text-body-emphasis' }} mb-1 mt-1">
                            {{ $stats['pending_profiles'] ?? 0 }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Employee staged edits</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-id-card-clip fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Pending Team Resignations -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Exit & Resignations</span>
                        <h3 class="fw-bolder {{ ($stats['pending_resignations'] ?? 0) > 0 ? 'text-danger' : 'text-body-emphasis' }} mb-1 mt-1">
                            {{ $stats['pending_resignations'] ?? 0 }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Active separation notices</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-person-walking-arrow-right fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Workspace -->
    <div class="row g-4">
        <!-- Left Column: Direct Reports & Pending Queue -->
        <div class="col-lg-8">
            <!-- Direct Reports Table -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
                <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-body-emphasis fs-6 mb-0">Direct Team Members</h5>
                            <span class="text-body-secondary fs-9">Employees reporting directly to your workstation</span>
                        </div>
                    </div>
                    <span class="badge bg-body text-body-secondary border px-2.5 py-1.5 rounded-pill fs-9 fw-semibold">
                        {{ count($teamMembers) }} Active Direct Reports
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 fs-8 border-top">
                            <thead class="bg-body-secondary text-body-secondary">
                                <tr>
                                    <th class="ps-4" style="width: 120px;">Actions</th>
                                    <th>Employee</th>
                                    <th>Designation & Dept</th>
                                    <th>Contact Info</th>
                                    <th class="pe-4 text-center" style="width: 100px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                    <tr>
                                        <!-- Column 1: Actions (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                        <td class="ps-4">
                                            <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                                <a href="{{ route('manager-portal.team_attendance') }}?date={{ date('Y-m-d') }}" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="View Daily Timesheet">
                                                    <i class="fa-solid fa-clock"></i>
                                                </a>
                                                <a href="{{ route('manager-portal.team_leaves') }}?search={{ urlencode($member->first_name) }}" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" title="View Leave Applications">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                </a>
                                                @if(!empty($member->email))
                                                    <a href="mailto:{{ $member->email }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Email Employee">
                                                        <i class="fa-solid fa-envelope"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <!-- Employee Name & Avatar -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-8" style="width: 36px; height: 36px; min-width: 36px;">
                                                    {{ strtoupper(substr($member->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($member->last_name ?? '', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-body-emphasis leading-tight">
                                                        {{ $member->first_name }} {{ $member->last_name }}
                                                    </div>
                                                    <div class="fs-9 text-body-secondary font-monospace">
                                                        ID: {{ $member->employee_id ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- Designation & Department -->
                                        <td>
                                            <div class="fw-semibold text-body-emphasis">
                                                {{ $member->designation->designation_name ?? 'Staff Member' }}
                                            </div>
                                            <div class="fs-9 text-body-secondary">
                                                <i class="fa-regular fa-building me-1 text-primary"></i> {{ $member->department->department_name ?? 'General' }}
                                            </div>
                                        </td>
                                        <!-- Contact Info -->
                                        <td>
                                            <div class="text-body-emphasis fs-8">
                                                {{ $member->email ?? '—' }}
                                            </div>
                                            <div class="fs-9 text-body-secondary">
                                                <i class="fa-solid fa-phone me-1 text-success"></i> {{ $member->contact_no ?? '—' }}
                                            </div>
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="pe-4 text-center">
                                            @if($member->is_active ?? true)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold fs-9 px-2 py-1 rounded-pill">
                                                    <i class="fa-solid fa-circle me-1 fs-10"></i> Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-body-secondary border fw-semibold fs-9 px-2 py-1 rounded-pill">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-0">
                                            <x-empty-state 
                                                icon="fa-solid fa-users-slash" 
                                                title="No Direct Reports Found" 
                                                description="There are currently no employee records assigned under your direct management hierarchy."
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals Quick Queue -->
            @if(count($pendingLeaves) > 0)
                <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
                    <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 bg-warning-subtle text-warning fs-8">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-body-emphasis fs-6 mb-0">Pending Leave Approvals</h5>
                                <span class="text-body-secondary fs-9">Direct report time-off requests requiring your immediate action</span>
                            </div>
                        </div>
                        <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-sm btn-outline-warning fw-semibold px-2.5 py-1 rounded-2 fs-9">
                            View All ({{ $stats['pending_leaves'] ?? count($pendingLeaves) }})
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 fs-8 border-top">
                                <thead class="bg-body-secondary text-body-secondary">
                                    <tr>
                                        <th class="ps-4" style="width: 130px;">Decision</th>
                                        <th>Employee</th>
                                        <th>Period</th>
                                        <th>Reason Snippet</th>
                                        <th class="pe-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingLeaves as $leave)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                                    <form method="POST" action="{{ route('manager-portal.team_leaves.status', $leave->leave_id) }}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="2">
                                                        <button type="submit" class="btn btn-sm btn-outline-success px-2.5 rounded-2" title="Approve Request" onclick="return confirm('Approve leave application for {{ $leave->employee->first_name ?? 'Employee' }}?');">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('manager-portal.team_leaves.status', $leave->leave_id) }}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="3">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Decline Request" onclick="return confirm('Decline leave application for {{ $leave->employee->first_name ?? 'Employee' }}?');">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-body-emphasis">
                                                {{ $leave->employee ? $leave->employee->first_name . ' ' . $leave->employee->last_name : 'Employee #' . $leave->employee_id }}
                                            </td>
                                            <td class="text-body-emphasis">
                                                <x-human-date :value="$leave->from_date" /> <span class="text-body-secondary">to</span> <x-human-date :value="$leave->to_date" />
                                            </td>
                                            <td class="text-body-secondary">
                                                {{ Str::limit($leave->reason, 36) }}
                                            </td>
                                            <td class="pe-4 text-center">
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-semibold fs-9 px-2 py-1 rounded-pill">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Command Quick Controls & Performance Overview -->
        <div class="col-lg-4">
            <!-- Manager Command Hub -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
                <div class="card-header border-0 pt-3 pb-2 bg-transparent">
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0">
                        <i class="fa-solid fa-sliders me-2 text-primary"></i> Manager Command Hub
                    </h5>
                    <span class="text-body-secondary fs-9">Fast navigation to team management modules</span>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <!-- Review Team Leave Requests -->
                        <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs d-flex align-items-center justify-content-between rounded-2 hover:bg-body-secondary transition">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar-xs rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center p-2" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-calendar-check fs-8"></i>
                                </div>
                                <span>Review Team Leaves</span>
                            </div>
                            @if(($stats['pending_leaves'] ?? 0) > 0)
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1 fs-9 fw-bold">
                                    {{ $stats['pending_leaves'] }}
                                </span>
                            @else
                                <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                            @endif
                        </a>

                        <!-- Daily Clock-in Timesheets -->
                        <a href="{{ route('manager-portal.team_attendance') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs d-flex align-items-center justify-content-between rounded-2 hover:bg-body-secondary transition">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar-xs rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center p-2" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-clock fs-8"></i>
                                </div>
                                <span>Team Timesheets & Attendance</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>

                        <!-- Conduct Team Appraisals -->
                        <a href="{{ route('manager-portal.team_performance') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs d-flex align-items-center justify-content-between rounded-2 hover:bg-body-secondary transition">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar-xs rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center p-2" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-star fs-8"></i>
                                </div>
                                <span>Team Performance & Appraisals</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>

                        <!-- Team Resignations -->
                        <a href="{{ route('my-portal.team_resignations') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs d-flex align-items-center justify-content-between rounded-2 hover:bg-body-secondary transition">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar-xs rounded-2 bg-danger-subtle text-danger d-flex align-items-center justify-content-center p-2" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-person-walking-arrow-right fs-8"></i>
                                </div>
                                <span>Team Resignations & Exits</span>
                            </div>
                            @if(($stats['pending_resignations'] ?? 0) > 0)
                                <span class="badge bg-danger text-white rounded-pill px-2 py-1 fs-9 fw-bold">
                                    {{ $stats['pending_resignations'] }}
                                </span>
                            @else
                                <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                            @endif
                        </a>

                        <!-- HR Profile Update Approvals (Permission Gated) -->
                        @can('edit.employees')
                        <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs d-flex align-items-center justify-content-between rounded-2 hover:bg-body-secondary transition">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="avatar-xs rounded-2 bg-info-subtle text-info d-flex align-items-center justify-content-center p-2" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-id-card-clip fs-8"></i>
                                </div>
                                <span>HR Profile Update Approvals</span>
                            </div>
                            @if(($stats['pending_profiles'] ?? 0) > 0)
                                <span class="badge bg-info text-dark rounded-pill px-2 py-1 fs-9 fw-bold">
                                    {{ $stats['pending_profiles'] }}
                                </span>
                            @else
                                <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                            @endif
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Recent Team Reviews Snippet -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
                <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0">
                        <i class="fa-solid fa-chart-simple me-2 text-success"></i> Recent Appraisals
                    </h5>
                    <a href="{{ route('manager-portal.team_performance') }}" class="fs-9 fw-semibold text-primary text-decoration-none">View All</a>
                </div>
                <div class="card-body p-3 pt-0">
                    @forelse($recentAppraisals as $appraisal)
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-subtle">
                            <div>
                                <div class="fw-bold text-body-emphasis fs-8">
                                    {{ $appraisal->employee ? $appraisal->employee->first_name . ' ' . $appraisal->employee->last_name : 'Employee #' . $appraisal->employee_id }}
                                </div>
                                <div class="fs-9 text-body-secondary">
                                    {{ $appraisal->title ?? 'Annual Review' }} • {{ $appraisal->appraisal_year ?? date('Y') }}
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold fs-9 px-2 py-1 rounded-2">
                                    <i class="fa-solid fa-star me-1"></i> {{ number_format((float) ($appraisal->overall_rating ?? 4.0), 1) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-body-secondary">
                            <i class="fa-solid fa-star-half-stroke fs-3 mb-2 text-body-tertiary"></i>
                            <p class="mb-0 fs-9">No recent team appraisals submitted yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
