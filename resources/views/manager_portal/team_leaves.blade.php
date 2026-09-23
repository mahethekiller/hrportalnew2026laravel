@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title & Action Controls -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-warning-subtle text-warning fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-calendar-check me-1"></i> Approvals Hub
                </span>
                <span class="text-body-secondary fs-9">• Team Absences & Leave Workflow</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Team Leave Approvals</h4>
            <p class="text-body-secondary fs-8 mb-0">Review, approve, or decline time-off applications submitted by your direct reports.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('manager-portal.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Manager Workstation
            </a>
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Requests -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Applications</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $stats['total'] ?? $leaves->total() }}</h3>
                        <span class="fs-9 text-body-secondary">All team submissions</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-file-lines fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Pending Decision</span>
                        <h3 class="fw-bolder {{ ($stats['pending'] ?? 0) > 0 ? 'text-warning' : 'text-body-emphasis' }} mb-1 mt-1">
                            {{ $stats['pending'] ?? 0 }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Requires manager review</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-hourglass-half fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Approved Requests</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $stats['approved'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Processed & scheduled</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Rejected Requests</span>
                        <h3 class="fw-bolder text-danger mb-1 mt-1">{{ $stats['rejected'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Declined applications</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-xmark fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Filter Pills & Search Form -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('manager-portal.team_leaves') }}" class="row g-2 align-items-center">
                <!-- Status Tabs -->
                <div class="col-lg-7 d-flex flex-wrap gap-1.5">
                    @php $currStatus = request('status', 'all'); @endphp
                    <a href="{{ route('manager-portal.team_leaves', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                       class="btn btn-sm {{ $currStatus === 'all' ? 'btn-primary shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                        All Applications ({{ $stats['total'] ?? 0 }})
                    </a>
                    <a href="{{ route('manager-portal.team_leaves', array_merge(request()->except('status', 'page'), ['status' => '1'])) }}"
                       class="btn btn-sm {{ $currStatus === '1' ? 'btn-warning text-dark shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                        Pending ({{ $stats['pending'] ?? 0 }})
                    </a>
                    <a href="{{ route('manager-portal.team_leaves', array_merge(request()->except('status', 'page'), ['status' => '2'])) }}"
                       class="btn btn-sm {{ $currStatus === '2' ? 'btn-success shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                        Approved ({{ $stats['approved'] ?? 0 }})
                    </a>
                    <a href="{{ route('manager-portal.team_leaves', array_merge(request()->except('status', 'page'), ['status' => '3'])) }}"
                       class="btn btn-sm {{ $currStatus === '3' ? 'btn-danger shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                        Rejected ({{ $stats['rejected'] ?? 0 }})
                    </a>
                </div>

                <!-- Live Search Box -->
                <div class="col-lg-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body border-end-0 text-body-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-body border-start-0 text-body-emphasis" placeholder="Search by employee name or reason..." value="{{ request('search') }}">
                        @if(request('status') && request('status') !== 'all')
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <button type="submit" class="btn btn-primary px-3 fw-semibold">Filter</button>
                        @if(request()->filled('search') || (request()->filled('status') && request('status') !== 'all'))
                            <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Requests Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 140px;">Actions</th>
                            <th>Employee</th>
                            <th>Leave Period</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th class="pe-4 text-center" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <!-- Column 1: Actions (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        @if($leave->status == 1)
                                            <!-- Quick Approve -->
                                            <form method="POST" action="{{ route('manager-portal.team_leaves.status', $leave->leave_id) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-sm btn-outline-success px-2.5 rounded-2" title="Approve Leave Application" onclick="return confirm('Approve leave request for {{ $leave->employee->first_name ?? 'Employee' }}?');">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                            <!-- Reject Modal Trigger -->
                                            <button type="button" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Reject with Remarks" data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $leave->leave_id }}">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        @endif
                                        <!-- View Details Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="View Full Leave Dossier" data-bs-toggle="modal" data-bs-target="#viewModal_{{ $leave->leave_id }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Employee Details -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-8" style="width: 36px; height: 36px; min-width: 36px;">
                                            {{ strtoupper(substr($leave->employee->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($leave->employee->last_name ?? '', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis leading-tight">
                                                {{ $leave->employee ? $leave->employee->first_name . ' ' . $leave->employee->last_name : 'Employee #' . $leave->employee_id }}
                                            </div>
                                            <div class="fs-9 text-body-secondary font-monospace">
                                                ID: {{ $leave->employee->employee_id ?? $leave->employee_id }} • {{ $leave->employee->designation->designation_name ?? 'Staff' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Leave Period -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis">
                                        <x-human-date :value="$leave->from_date" /> <span class="text-body-secondary">to</span> <x-human-date :value="$leave->to_date" />
                                    </div>
                                    <div class="fs-9 text-body-secondary">
                                        Applied on: <x-human-date :value="$leave->created_at ?? $leave->applied_on ?? $leave->from_date" />
                                    </div>
                                </td>

                                <!-- Duration Days -->
                                <td>
                                    @php
                                        $from = \Carbon\Carbon::parse($leave->from_date);
                                        $to = \Carbon\Carbon::parse($leave->to_date);
                                        $days = $from->diffInDays($to) + 1;
                                    @endphp
                                    <span class="badge bg-body text-body-emphasis border px-2 py-1 rounded-2 fw-semibold">
                                        {{ $days }} {{ Str::plural('Day', $days) }}
                                    </span>
                                </td>

                                <!-- Reason Snippet -->
                                <td>
                                    <span class="text-body-emphasis fs-8">
                                        {{ Str::limit($leave->reason, 45) }}
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="pe-4 text-center">
                                    @if($leave->status == 2)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            <i class="fa-solid fa-check me-1"></i> Approved
                                        </span>
                                    @elseif($leave->status == 3)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            <i class="fa-solid fa-xmark me-1"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            <i class="fa-solid fa-clock me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal: Reject Leave Application -->
                            <div class="modal fade" id="rejectModal_{{ $leave->leave_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-body border-0 shadow">
                                        <form method="POST" action="{{ route('manager-portal.team_leaves.status', $leave->leave_id) }}" onsubmit="submitWithLoader(this)">
                                            @csrf
                                            <input type="hidden" name="status" value="3">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-body-emphasis fs-6">
                                                    <i class="fa-solid fa-circle-xmark text-danger me-2"></i> Reject Leave Application
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="p-3 bg-body-tertiary rounded-3 border mb-3">
                                                    <div class="fw-bold text-body-emphasis fs-8 mb-1">
                                                        {{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}
                                                    </div>
                                                    <div class="fs-9 text-body-secondary">
                                                        Period: <x-human-date :value="$leave->from_date" /> to <x-human-date :value="$leave->to_date" /> ({{ $days }} days)
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">
                                                        Reason for Rejection <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea name="remarks" rows="3" class="form-control form-control-sm text-body-emphasis bg-body" required placeholder="Please provide specific reasoning or scheduling conflict details..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-danger fw-semibold px-3">
                                                    <i class="fa-solid fa-circle-xmark me-1"></i> Confirm Rejection
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal: Full Leave Dossier -->
                            <div class="modal fade" id="viewModal_{{ $leave->leave_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-body border-0 shadow">
                                        <div class="modal-header border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                                                    <i class="fa-solid fa-calendar-day"></i>
                                                </div>
                                                <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">
                                                    Leave Dossier — {{ $leave->employee->first_name ?? 'Employee' }} {{ $leave->employee->last_name ?? '' }}
                                                </h5>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3 mb-4">
                                                <div class="col-md-6">
                                                    <div class="p-3 rounded-3 bg-body-tertiary border h-100">
                                                        <span class="text-body-secondary fs-9 fw-bold text-uppercase d-block mb-1">Employee Details</span>
                                                        <div class="fw-bold text-body-emphasis fs-7">{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</div>
                                                        <div class="fs-8 text-body-secondary font-monospace mt-1">ID: {{ $leave->employee->employee_id ?? 'N/A' }}</div>
                                                        <div class="fs-8 text-body-secondary mt-1">
                                                            {{ $leave->employee->designation->designation_name ?? 'Staff' }} • {{ $leave->employee->department->department_name ?? 'Department' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-3 rounded-3 bg-body-tertiary border h-100">
                                                        <span class="text-body-secondary fs-9 fw-bold text-uppercase d-block mb-1">Leave Timeline</span>
                                                        <div class="fw-bold text-body-emphasis fs-7">
                                                            <x-human-date :value="$leave->from_date" /> <span class="text-body-secondary fw-normal">to</span> <x-human-date :value="$leave->to_date" />
                                                        </div>
                                                        <div class="mt-2">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-2 fs-9 fw-semibold">
                                                                Duration: {{ $days }} {{ Str::plural('Day', $days) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label fs-9 fw-bold text-uppercase text-body-secondary">Reason Submitted</label>
                                                <div class="p-3 rounded-3 bg-body-tertiary border text-body-emphasis fs-8 leading-relaxed">
                                                    {{ $leave->reason ?? 'No detailed reason specified.' }}
                                                </div>
                                            </div>

                                            @if(!empty($leave->remarks))
                                                <div class="mb-3">
                                                    <label class="form-label fs-9 fw-bold text-uppercase text-body-secondary">Manager Decision Remarks</label>
                                                    <div class="p-3 rounded-3 bg-body-secondary border text-body-emphasis fs-8 leading-relaxed">
                                                        {{ $leave->remarks }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-top bg-body-tertiary">
                                            @if($leave->status == 1)
                                                <form method="POST" action="{{ route('manager-portal.team_leaves.status', $leave->leave_id) }}" class="d-inline" onsubmit="submitWithLoader(this)">
                                                    @csrf
                                                    <input type="hidden" name="status" value="2">
                                                    <button type="submit" class="btn btn-sm btn-success fw-semibold px-3">
                                                        <i class="fa-solid fa-check me-1"></i> Approve Application
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger fw-semibold px-3" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $leave->leave_id }}">
                                                    <i class="fa-solid fa-xmark me-1"></i> Decline Application
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-calendar-xmark" 
                                        title="No Leave Applications Found" 
                                        description="There are currently no leave applications matching your selected status filter or search parameters."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaves->hasPages())
                <div class="card-footer border-top bg-transparent py-3 px-4">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
