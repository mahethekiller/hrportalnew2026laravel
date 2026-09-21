@extends('layouts.app')

@section('title', 'My Attendance & Clock Logs')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-solid fa-clock-rotate-left me-1"></i> Self-Service Portal
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Time & Attendance</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">My Attendance & Clock Logs</h1>
            <p class="text-body-secondary fs-7 mb-0">Daily clock-in/out records, biometric punch timings, and monthly presence logs.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-2 bg-body border rounded-pill px-3 py-1.5 shadow-sm">
                <span class="status-dot bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                <span class="fs-9 text-body-secondary">Biometric Sync:</span>
                <span class="fs-9 fw-bold text-body-emphasis font-monospace">{{ $employee?->card_no ?? 'CARD-SYNCED' }}</span>
            </div>
        </div>
    </div>

    <!-- Metric KPI Cards (Airy & Elevated: DENSITY: 3, MOTION: 4, VARIANCE: 6) -->
    <div class="row g-3 mb-4">
        <!-- Total Logged Days -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Logged Days</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-calendar-check fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-emphasis mb-1">{{ $stats['total'] ?? $attendanceLogs->total() }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Total recorded punch days</p>
                </div>
            </div>
        </div>

        <!-- Present Days -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Days Present</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-user-check fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-success mb-1">{{ $stats['present'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Confirmed active work days</p>
                </div>
            </div>
        </div>

        <!-- Absent / Missed Days -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Absent / Missed</span>
                        <div class="stat-icon-wrapper rounded-2 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-user-xmark fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-secondary mb-1">{{ $stats['absent'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Days with no punches</p>
                </div>
            </div>
        </div>

        <!-- Presence Rate -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Presence Rate</span>
                        <div class="stat-icon-wrapper rounded-2 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-chart-pie fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-info mb-1">{{ $stats['rate'] ?? 100 }}%</h3>
                    <p class="fs-9 text-body-secondary mb-0">Attendance reliability</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Control Card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body">
        <div class="card-body p-3.5">
            <form method="GET" action="{{ route('my-portal.attendance') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">
                        <i class="fa-regular fa-calendar me-1 text-primary"></i> From Date
                    </label>
                    <input type="date" name="from_date" class="form-control form-control-sm fs-8 bg-body" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">
                        <i class="fa-regular fa-calendar me-1 text-primary"></i> To Date
                    </label>
                    <input type="date" name="to_date" class="form-control form-control-sm fs-8 bg-body" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">
                        <i class="fa-solid fa-filter me-1 text-primary"></i> Attendance Status
                    </label>
                    <select name="status" class="form-select form-select-sm fs-8 bg-body">
                        <option value="">All Statuses</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present Only</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent Only</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm fs-8 fw-bold flex-fill submit-loader" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-filter me-1"></i> Apply Filter
                    </button>
                    @if(request()->hasAny(['from_date', 'to_date', 'status']))
                        <a href="{{ route('my-portal.attendance') }}" class="btn btn-light btn-sm fs-8 fw-semibold border" title="Clear Filters">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Punch Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body">
        <!-- Card Header with Search Input -->
        <div class="card-header bg-transparent border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-fingerprint text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis">Biometric Punch History</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 fs-9 fw-normal ms-1">
                        {{ $attendanceLogs->total() }} Records
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <span class="input-group-text bg-body border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass fs-9"></i></span>
                        <input type="text" id="attendanceTableSearch" class="form-control bg-body border-start-0 fs-8" placeholder="Search punches...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8" id="attendanceTable">
                    <thead class="table-light border-bottom">
                        <tr>
                            <!-- Rule 8: First-Column Icon-Only Actions -->
                            <th class="ps-4" style="width: 80px;">Actions</th>
                            <th style="width: 170px;">Punch Date</th>
                            <th style="width: 140px;">Clock In</th>
                            <th style="width: 140px;">Clock Out</th>
                            <th style="width: 130px;">Total Work</th>
                            <th style="width: 130px;">Badge / Card</th>
                            <th class="pe-4 text-end" style="width: 120px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceLogs as $log)
                            @php
                                $punchDate = \Carbon\Carbon::parse($log->punch_date);
                                
                                $checkIn = null;
                                if (!empty($log->check_in_time)) {
                                    $checkIn = \Carbon\Carbon::parse($log->punch_date . ' ' . $log->check_in_time);
                                } elseif (!empty($log->check_in_datetime)) {
                                    $checkIn = \Carbon\Carbon::parse($log->check_in_datetime);
                                }

                                $checkOut = null;
                                if (!empty($log->check_out_time)) {
                                    $checkOut = \Carbon\Carbon::parse($log->punch_date . ' ' . $log->check_out_time);
                                } elseif (!empty($log->check_out_datetime)) {
                                    $checkOut = \Carbon\Carbon::parse($log->check_out_datetime);
                                }

                                $isPresent = ($checkIn !== null);

                                $workDuration = '-';
                                if ($checkIn && $checkOut && $checkOut->gt($checkIn)) {
                                    $mins = $checkIn->diffInMinutes($checkOut);
                                    $h = floor($mins / 60);
                                    $m = $mins % 60;
                                    $workDuration = $h . 'h ' . ($m > 0 ? $m . 'm' : '');
                                }
                            @endphp
                            <tr class="punch-row transition-colors">
                                <!-- Rule 8: First Column Action Buttons -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewPunchModal{{ $log->id }}" 
                                                title="View Punch Details">
                                            <i class="fa-solid fa-eye fs-8"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Punch Date -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-2 bg-body-tertiary border px-2 py-1 text-center" style="min-width: 44px;">
                                            <span class="d-block fs-9 fw-bold text-uppercase text-body-secondary">{{ $punchDate->format('D') }}</span>
                                            <span class="d-block fs-8 fw-bold text-body-emphasis leading-tight">{{ $punchDate->format('d') }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-body-emphasis d-block">{{ $punchDate->format('d M, Y') }}</span>
                                            <span class="fs-9 text-body-secondary">{{ $punchDate->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Check In -->
                                <td>
                                    @if($checkIn)
                                        <div class="d-flex align-items-center gap-1.5 text-success fw-semibold font-monospace fs-8">
                                            <i class="fa-solid fa-arrow-right-to-bracket fs-9 text-success"></i>
                                            <span>{{ $checkIn->format('h:i A') }}</span>
                                        </div>
                                    @else
                                        <span class="text-body-tertiary fs-9 font-monospace">--:-- --</span>
                                    @endif
                                </td>

                                <!-- Check Out -->
                                <td>
                                    @if($checkOut)
                                        <div class="d-flex align-items-center gap-1.5 text-danger fw-semibold font-monospace fs-8">
                                            <i class="fa-solid fa-arrow-right-from-bracket fs-9 text-danger"></i>
                                            <span>{{ $checkOut->format('h:i A') }}</span>
                                        </div>
                                    @else
                                        <span class="text-body-tertiary fs-9 font-monospace">--:-- --</span>
                                    @endif
                                </td>

                                <!-- Working Hours -->
                                <td>
                                    @if($workDuration !== '-')
                                        <span class="badge bg-body-tertiary text-body-emphasis border rounded-pill px-2.5 py-1 font-monospace fs-9 fw-semibold">
                                            <i class="fa-regular fa-clock me-1 text-primary"></i> {{ $workDuration }}
                                        </span>
                                    @else
                                        <span class="text-body-tertiary fs-9">-</span>
                                    @endif
                                </td>

                                <!-- Badge / Card Number -->
                                <td>
                                    <span class="badge bg-body border text-body font-monospace fs-9 px-2 py-1">
                                        {{ $log->badgenumber ?: ($log->card_no ?: 'N/A') }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="pe-4 text-end">
                                    @if($isPresent)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Present
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Absent
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Detail Modal for each punch record -->
                            <div class="modal fade" id="viewPunchModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-bottom py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="fa-solid fa-fingerprint fs-7"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis mb-0">Punch Details</h5>
                                                    <span class="fs-9 text-body-secondary">{{ $punchDate->format('l, d F Y') }}</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 fs-8">
                                            <!-- Status Banner inside Modal -->
                                            <div class="p-3 rounded-3 mb-3 d-flex align-items-center justify-content-between {{ $isPresent ? 'bg-success-subtle border border-success-subtle text-success' : 'bg-secondary-subtle border border-secondary-subtle text-secondary' }}">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid {{ $isPresent ? 'fa-circle-check' : 'fa-circle-xmark' }} fs-6"></i>
                                                    <span class="fw-bold">Attendance Status: {{ $isPresent ? 'Present' : 'Absent / No Punch' }}</span>
                                                </div>
                                                @if($workDuration !== '-')
                                                    <span class="badge bg-body text-body-emphasis border fs-9 font-monospace">
                                                        Total Duration: {{ $workDuration }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="row g-3 mb-3">
                                                <div class="col-6">
                                                    <div class="p-3 rounded-2 bg-body-tertiary border">
                                                        <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">
                                                            <i class="fa-solid fa-arrow-right-to-bracket text-success me-1"></i> Clock In Time
                                                        </label>
                                                        <div class="fw-bold fs-7 text-body-emphasis font-monospace">
                                                            {{ $checkIn ? $checkIn->format('h:i:s A') : 'Not Recorded' }}
                                                        </div>
                                                        @if($log->check_in_datetime)
                                                            <span class="fs-9 text-body-secondary d-block mt-0.5">{{ $log->check_in_datetime }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-3 rounded-2 bg-body-tertiary border">
                                                        <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">
                                                            <i class="fa-solid fa-arrow-right-from-bracket text-danger me-1"></i> Clock Out Time
                                                        </label>
                                                        <div class="fw-bold fs-7 text-body-emphasis font-monospace">
                                                            {{ $checkOut ? $checkOut->format('h:i:s A') : 'Not Recorded' }}
                                                        </div>
                                                        @if($log->check_out_datetime)
                                                            <span class="fs-9 text-body-secondary d-block mt-0.5">{{ $log->check_out_datetime }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 rounded-2 bg-body-secondary border mb-3">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <span class="text-body-secondary fs-9 d-block">Biometric Card No:</span>
                                                        <span class="fw-semibold text-body font-monospace">{{ $log->card_no ?: 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="text-body-secondary fs-9 d-block">Machine Badge No:</span>
                                                        <span class="fw-semibold text-body font-monospace">{{ $log->badgenumber ?: 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top fs-9 text-body-secondary">
                                                <span>Machine Sync Status:</span>
                                                <span class="fw-medium text-success">
                                                    <i class="fa-solid fa-circle-check me-1"></i> Biometric Device Synced
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
                                            <i class="fa-regular fa-clock fs-2"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No Attendance Logs Found</h6>
                                        <p class="fs-8 text-body-secondary mb-3">No biometric punch records match your selected filter criteria.</p>
                                        @if(request()->hasAny(['from_date', 'to_date', 'status']))
                                            <a href="{{ route('my-portal.attendance') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                                <i class="fa-solid fa-rotate-left me-1"></i> Clear Filters
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if(method_exists($attendanceLogs, 'links') && $attendanceLogs->hasPages())
                <div class="card-footer bg-transparent border-top py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="fs-9 text-body-secondary">
                        Showing {{ $attendanceLogs->firstItem() }} to {{ $attendanceLogs->lastItem() }} of {{ $attendanceLogs->total() }} logs
                    </span>
                    <div>
                        {{ $attendanceLogs->links() }}
                    </div>
                </div>
            @endif
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
    #attendanceTable th {
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
        // Instant search filtering on current page rows
        const searchInput = document.getElementById('attendanceTableSearch');
        const rows = document.querySelectorAll('.punch-row');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
