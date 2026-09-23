@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner & Date Selector -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-clock me-1"></i> Timesheet Hub
                </span>
                <span class="text-body-secondary fs-9">• Biometric Logs & Working Hours</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Team Daily Clock-In Timesheets</h4>
            <p class="text-body-secondary fs-8 mb-0">Monitor real-time clock-in stamps, shift durations, and daily attendance for your direct team.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('manager-portal.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Manager Workstation
            </a>
        </div>
    </div>

    <!-- Date Picker Bar -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('manager-portal.team_attendance') }}" class="row g-2 align-items-center">
                <div class="col-sm-auto">
                    <span class="text-body-secondary fs-9 fw-bold text-uppercase me-2">Selected Date:</span>
                </div>
                <div class="col-sm-auto">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body text-body-secondary border-end-0"><i class="fa-solid fa-calendar-day"></i></span>
                        <input type="date" name="date" class="form-control bg-body text-body-emphasis border-start-0" value="{{ $selectedDate ?? date('Y-m-d') }}" onchange="this.form.submit()">
                    </div>
                </div>
                <div class="col-sm-auto d-flex gap-1">
                    <a href="{{ route('manager-portal.team_attendance', ['date' => date('Y-m-d')]) }}" class="btn btn-sm {{ ($selectedDate ?? date('Y-m-d')) === date('Y-m-d') ? 'btn-primary' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1 fs-9 fw-semibold">
                        Today
                    </a>
                    <a href="{{ route('manager-portal.team_attendance', ['date' => date('Y-m-d', strtotime('-1 day'))]) }}" class="btn btn-sm {{ ($selectedDate ?? '') === date('Y-m-d', strtotime('-1 day')) ? 'btn-primary' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1 fs-9 fw-semibold">
                        Yesterday
                    </a>
                </div>
                <div class="col-sm-auto ms-sm-auto">
                    <span class="badge bg-body text-body-secondary border px-3 py-2 rounded-2 fs-9">
                        <i class="fa-solid fa-calendar-check me-1 text-primary"></i> <x-human-date :value="$selectedDate ?? date('Y-m-d')" />
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Telemetry Metric Cards -->
    <div class="row g-3 mb-4">
        <!-- Direct Team Members -->
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Team</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $stats['total_team'] ?? count($teamMembers) }}</h3>
                        <span class="fs-9 text-body-secondary">Assigned direct reports</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Clocked In / Present -->
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Present / Punched</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $stats['present'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Logged in on date</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Not Clocked In / Absent -->
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">No Punch Recorded</span>
                        <h3 class="fw-bolder {{ ($stats['absent'] ?? 0) > 0 ? 'text-secondary' : 'text-body-emphasis' }} mb-1 mt-1">
                            {{ $stats['absent'] ?? 0 }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Absent, on leave, or pending</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-secondary-subtle text-body-secondary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-clock fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-secondary" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    @php
        // Build an attendance lookup map for direct report matching
        $attMap = [];
        foreach($attendances as $att) {
            if (!empty($att->card_no)) {
                $attMap[$att->card_no] = $att;
            }
            if (!empty($att->badgenumber)) {
                $attMap[$att->badgenumber] = $att;
            }
        }
    @endphp

    <!-- Attendance Timesheet Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-fingerprint me-2 text-primary"></i> Team Punch Log & Timesheet
            </h5>
            <span class="text-body-secondary fs-9">
                Showing logs for <strong><x-human-date :value="$selectedDate ?? date('Y-m-d')" /></strong>
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 100px;">Actions</th>
                            <th>Team Member</th>
                            <th>Designation & Dept</th>
                            <th>First Clock In</th>
                            <th>Last Clock Out</th>
                            <th>Work Shift</th>
                            <th class="pe-4 text-center" style="width: 130px;">Punch Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamMembers as $mb)
                            @php
                                $empPunch = $attMap[$mb->employee_id] 
                                    ?? $attMap[$mb->user_id] 
                                    ?? $attMap[$mb->card_no ?? ''] 
                                    ?? null;

                                $checkIn = $empPunch?->check_in_time ?? ($empPunch?->check_in_datetime ? date('H:i', strtotime($empPunch->check_in_datetime)) : null);
                                $checkOut = $empPunch?->check_out_time ?? ($empPunch?->check_out_datetime ? date('H:i', strtotime($empPunch->check_out_datetime)) : null);
                            @endphp
                            <tr>
                                <!-- Column 1: Actions (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <a href="{{ route('manager-portal.team_leaves') }}?search={{ urlencode($mb->first_name) }}" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" title="Check Leave Applications">
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </a>
                                        @if(!empty($mb->email))
                                            <a href="mailto:{{ $mb->email }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Email Team Member">
                                                <i class="fa-solid fa-envelope"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Team Member -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-8" style="width: 36px; height: 36px; min-width: 36px;">
                                            {{ strtoupper(substr($mb->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($mb->last_name ?? '', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis leading-tight">
                                                {{ $mb->first_name }} {{ $mb->last_name }}
                                            </div>
                                            <div class="fs-9 text-body-secondary font-monospace">
                                                ID: {{ $mb->employee_id ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Designation & Dept -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis">
                                        {{ $mb->designation->designation_name ?? 'Staff Member' }}
                                    </div>
                                    <div class="fs-9 text-body-secondary">
                                        {{ $mb->department->department_name ?? 'General' }}
                                    </div>
                                </td>

                                <!-- First Clock In -->
                                <td>
                                    @if($checkIn)
                                        <div class="d-flex align-items-center gap-1.5 text-success font-monospace fw-semibold">
                                            <i class="fa-solid fa-arrow-right-to-bracket fs-9"></i>
                                            {{ $checkIn }}
                                        </div>
                                    @else
                                        <span class="text-body-secondary font-monospace">—</span>
                                    @endif
                                </td>

                                <!-- Last Clock Out -->
                                <td>
                                    @if($checkOut)
                                        <div class="d-flex align-items-center gap-1.5 text-danger font-monospace fw-semibold">
                                            <i class="fa-solid fa-arrow-right-from-bracket fs-9"></i>
                                            {{ $checkOut }}
                                        </div>
                                    @else
                                        <span class="text-body-secondary font-monospace">—</span>
                                    @endif
                                </td>

                                <!-- Shift -->
                                <td>
                                    <span class="badge bg-body text-body-secondary border px-2 py-1 rounded-2 fs-9">
                                        Standard Shift (09:00 - 18:00)
                                    </span>
                                </td>

                                <!-- Punch Status -->
                                <td class="pe-4 text-center">
                                    @if($empPunch)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            <i class="fa-solid fa-circle me-1 fs-10"></i> Clocked In
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-body-secondary border fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            No Punch Recorded
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-users-slash" 
                                        title="No Direct Reports" 
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
</div>
@endsection
