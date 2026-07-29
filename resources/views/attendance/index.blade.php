@extends('layouts.app')

@section('title', 'Attendance & WFH Management')

@section('content')
@php
    $activeTab = request('tab', 'office');
@endphp

<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Attendance & WFH Dashboard</h1>
            <p class="text-muted fs-7 mb-0">Track real-time WFH Clock-In/Out sessions and Office Punch logs.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('office-shifts.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Office Shifts
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#manualPunchModal">
                <i class="fa-solid fa-plus me-1"></i> Manual Punch Entry
            </button>
        </div>
    </div>

    <!-- Live WFH Clock-In / Clock-Out Widget Hero Card -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient-dark text-white p-2">
        <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="symbol symbol-50px bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width:50px; height:50px;">
                    <i class="fa-solid fa-house-laptop fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-1 text-white fw-bold">Work From Home (WFH) Attendance</h4>
                    <p class="mb-0 fs-8 text-light opacity-75">
                        @if($activeWfh)
                            <span class="badge bg-warning text-dark me-2"><i class="fa-solid fa-spinner fa-spin me-1"></i>Active WFH Session</span>
                            Clocked In at: <strong>{{ $activeWfh->formatted_clock_in }}</strong> ({{ $activeWfh->clean_description }})
                        @else
                            <span class="badge bg-secondary text-white me-2">Currently Clocked Out</span>
                            Click Clock In to start your WFH work session today.
                        @endif
                    </p>
                </div>
            </div>

            <div>
                @if($activeWfh)
                    <form method="POST" action="{{ route('attendance.wfh-clock-out') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg px-4 fw-bold shadow">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Clock Out Now
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-success btn-lg px-4 fw-bold shadow" data-bs-toggle="modal" data-bs-target="#wfhClockInModal">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Clock In (WFH)
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light">
            <ul class="nav nav-tabs nav-line-tabs mb-0 border-0 fs-6 fw-bold">
                <li class="nav-item">
                    <a class="nav-link text-active-primary {{ $activeTab === 'office' ? 'active' : '' }} py-3" data-bs-toggle="tab" href="#tabOfficePunch" onclick="updateActiveTabParam('office')">
                        <i class="fa-solid fa-id-card me-2"></i> Office Punch Attendance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary {{ $activeTab === 'wfh' ? 'active' : '' }} py-3" data-bs-toggle="tab" href="#tabWfhClockings" onclick="updateActiveTabParam('wfh')">
                        <i class="fa-solid fa-laptop-house me-2"></i> WFH Clocking Sessions
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content" id="attendanceTabContent">
                
                <!-- TAB 1: Office Punch Attendance -->
                <div class="tab-pane fade {{ $activeTab === 'office' ? 'show active' : '' }}" id="tabOfficePunch" role="tabpanel">
                    <div class="p-3 border-bottom bg-light bg-opacity-50">
                        <form method="GET" action="{{ route('attendance.index') }}" class="row g-2 align-items-center">
                            <input type="hidden" name="tab" value="office">
                            <div class="col-md-5">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                    <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search employee name, card no, badge..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                                    <input type="date" name="date" class="form-control form-control-sm border-start-0" value="{{ request('date') }}" onchange="this.form.submit()">
                                </div>
                            </div>
                            <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                                <a href="{{ route('attendance.index', ['tab' => 'office']) }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                            <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                                <tr>
                                    <th class="ps-4">Employee</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($officeAttendances as $att)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-35px me-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width:35px; height:35px;">
                                                    {{ substr($att->employee->first_name ?? 'E', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-gray-900">{{ $att->employee->first_name ?? 'Staff' }} {{ $att->employee->last_name ?? '' }}</div>
                                                    <div class="fs-9 text-muted">Card / Badge: {{ $att->card_no ?? $att->badgenumber ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium text-gray-800">{{ $att->punch_date }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success font-monospace fs-8"><i class="fa-solid fa-arrow-down-to-line me-1"></i>{{ $att->formatted_check_in }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-danger font-monospace fs-8"><i class="fa-solid fa-arrow-up-from-line me-1"></i>{{ $att->formatted_check_out }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $att->status_badge_class }}">
                                                {{ $att->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-clock-rotate-left fs-2 mb-2 d-block text-muted"></i>
                                            No office punch attendance logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($officeAttendances->hasPages())
                        <div class="card-footer py-3 border-top">
                            {{ $officeAttendances->appends(array_merge(request()->query(), ['tab' => 'office']))->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                <!-- TAB 2: Work From Home (WFH) Logs -->
                <div class="tab-pane fade {{ $activeTab === 'wfh' ? 'show active' : '' }}" id="tabWfhClockings" role="tabpanel">
                    <div class="p-3 border-bottom bg-light bg-opacity-50">
                        <form method="GET" action="{{ route('attendance.index') }}" class="row g-2 align-items-center">
                            <input type="hidden" name="tab" value="wfh">
                            <div class="col-md-8">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                    <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search employee name, description notes..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4 text-end d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                                <a href="{{ route('attendance.index', ['tab' => 'wfh']) }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                            <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                                <tr>
                                    <th class="ps-4">Employee</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Duration</th>
                                    <th>Description / Notes</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wfhClockings as $wfh)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-gray-900">{{ $wfh->employee->first_name ?? 'Employee' }} {{ $wfh->employee->last_name ?? '' }}</div>
                                            <div class="fs-9 text-muted">ID: {{ $wfh->userid }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success font-monospace fs-8">{{ $wfh->formatted_clock_in }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-danger font-monospace fs-8">{{ $wfh->formatted_clock_out }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-gray-800">{{ $wfh->total_hours }}</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-700 fs-8">{{ $wfh->clean_description }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $wfh->status_badge_class }}">
                                                {{ $wfh->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            @if(empty($wfh->clock_out))
                                                <form method="POST" action="{{ route('attendance.wfh-clock-out') }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="clocking_id" value="{{ $wfh->id }}">
                                                    <button type="submit" class="btn btn-sm btn-light-danger py-1 px-2 fs-8 fw-bold">
                                                        <i class="fa-solid fa-right-from-bracket me-1"></i> Clock Out
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted fs-9"><i class="fa-solid fa-check me-1"></i>Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-laptop-code fs-2 mb-2 d-block text-muted"></i>
                                            No WFH clocking sessions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($wfhClockings->hasPages())
                        <div class="card-footer py-3 border-top">
                            {{ $wfhClockings->appends(array_merge(request()->query(), ['tab' => 'wfh']))->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function updateActiveTabParam(tabName) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }
</script>

<!-- Modal: WFH Clock In -->
<div class="modal fade" id="wfhClockInModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('attendance.wfh-clock-in') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-house-laptop me-2 text-success"></i> Start WFH Clock-In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">WFH Session Description / Notes</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Describe tasks/projects for today's WFH..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm fw-bold">Confirm Clock In</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Manual Punch Entry -->
<div class="modal fade" id="manualPunchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('attendance.manual') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Manual Office Punch Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Select Employee / Card No <span class="text-danger">*</span></label>
                        <select name="card_no" class="form-select form-select-sm" required>
                            <option value="">Choose Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->card_no ?? $emp->employee_id ?? $emp->user_id }}">
                                    {{ $emp->first_name }} {{ $emp->last_name }} (Card: {{ $emp->card_no ?? $emp->employee_id ?? $emp->user_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Punch Date <span class="text-danger">*</span></label>
                        <input type="date" name="punch_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Check In Time</label>
                            <input type="time" name="check_in_time" class="form-control form-control-sm" value="09:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Check Out Time</label>
                            <input type="time" name="check_out_time" class="form-control form-control-sm" value="18:00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Attendance Status</label>
                        <select name="show_status" class="form-select form-select-sm">
                            <option value="Present">Present</option>
                            <option value="Late">Late</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Manual Punch</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
