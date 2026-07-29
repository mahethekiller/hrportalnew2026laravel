@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Leave Management</h1>
            <p class="text-muted fs-7 mb-0">Track and manage employee leave applications and status approvals.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('leave-types.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-gear me-1"></i> Leave Types & Quotas
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#applyLeaveModal">
                <i class="fa-solid fa-plus me-1"></i> Apply for Leave
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px me-3 bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-calendar-minus fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-gray-900">{{ $counts['total'] ?? 0 }}</div>
                        <div class="fs-8 text-muted fw-semibold">Total Applications</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px me-3 bg-light-warning rounded-circle d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-clock fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-gray-900">{{ $counts['pending'] ?? 0 }}</div>
                        <div class="fs-8 text-muted fw-semibold">Pending Approvals</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px me-3 bg-light-success rounded-circle d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-circle-check fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-gray-900">{{ $counts['approved'] ?? 0 }}</div>
                        <div class="fs-8 text-muted fw-semibold">Approved Leaves</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px me-3 bg-light-danger rounded-circle d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-circle-xmark fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-gray-900">{{ $counts['rejected'] ?? 0 }}</div>
                        <div class="fs-8 text-muted fw-semibold">Rejected Applications</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm">
        <!-- Card Header / Filters -->
        <div class="card-header border-0 pt-3">
            <form method="GET" action="{{ route('leaves.index') }}" class="row g-2 w-100 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search employee name or reason..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Pending</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Approved</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="leave_type_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Leave Types</option>
                        @foreach($leaveTypes as $lt)
                            <option value="{{ $lt->leave_type_id }}" {{ request('leave_type_id') == $lt->leave_type_id ? 'selected' : '' }}>
                                {{ $lt->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('leaves.index') }}" class="btn btn-light-secondary btn-sm"><i class="fa-solid fa-arrows-rotate me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>Days</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveApplications as $leave)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width:35px; height:35px;">
                                            {{ substr($leave->employee->first_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-900">{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? 'N/A' }}</div>
                                            <div class="fs-9 text-muted">{{ $leave->employee->employee_id ?? 'No Code' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fw-semibold">{{ $leave->leaveType->type_name ?? 'General' }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium text-gray-900">{{ $leave->from_date }}</div>
                                    <div class="fs-9 text-muted">to {{ $leave->to_date }}</div>
                                </td>
                                <td>
                                    <span class="fw-bold text-gray-800">{{ $leave->total_days }} day(s)</span>
                                </td>
                                <td>
                                    <span class="text-muted fs-8">{{ $leave->applied_on ? date('M d, Y', strtotime($leave->applied_on)) : 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $leave->status_badge_class }}">
                                        {{ $leave->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm btn-icon" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 fs-7">
                                            <li>
                                                <button class="dropdown-item text-success py-2" data-bs-toggle="modal" data-bs-target="#approveLeaveModal{{ $leave->leave_id }}">
                                                    <i class="fa-solid fa-circle-check me-2"></i> Approve
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger py-2" data-bs-toggle="modal" data-bs-target="#rejectLeaveModal{{ $leave->leave_id }}">
                                                    <i class="fa-solid fa-circle-xmark me-2"></i> Reject
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('leaves.destroy', $leave->leave_id) }}" onsubmit="return confirm('Delete this leave application record?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-muted py-2">
                                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveLeaveModal{{ $leave->leave_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form method="POST" action="{{ route('leaves.update-status', $leave->leave_id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="2">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-success"><i class="fa-solid fa-circle-check me-2"></i> Approve Leave Application</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p class="fs-7 text-gray-700">Approve leave for <strong>{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</strong> from {{ $leave->from_date }} to {{ $leave->to_date }} ({{ $leave->total_days }} days)?</p>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Approval Remarks (Optional)</label>
                                                            <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="Leave approved..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success btn-sm">Confirm Approval</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectLeaveModal{{ $leave->leave_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form method="POST" action="{{ route('leaves.update-status', $leave->leave_id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="3">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-danger"><i class="fa-solid fa-circle-xmark me-2"></i> Reject Leave Application</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p class="fs-7 text-gray-700">Reject leave for <strong>{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</strong>?</p>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Rejection Reason / Remarks</label>
                                                            <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="State rejection reason..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Confirm Rejection</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-calendar-xmark fs-2 mb-2 d-block text-muted"></i>
                                    No leave applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($leaveApplications->hasPages())
            <div class="card-footer py-3">
                {{ $leaveApplications->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Apply Leave -->
<div class="modal fade" id="applyLeaveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('leaves.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Apply for Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @php
                        $currentUserEmpId = Auth::user()->employee->user_id ?? Auth::user()->id;
                    @endphp
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select form-select-sm" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->user_id }}" {{ (request('employee_id') == $emp->user_id || $currentUserEmpId == $emp->user_id) ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'ID:'.$emp->user_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" class="form-select form-select-sm" required>
                            <option value="">Select Leave Type</option>
                            @foreach($leaveTypes as $lt)
                                <option value="{{ $lt->leave_type_id }}">{{ $lt->type_name }} ({{ $lt->days_per_year }} days/yr)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">From Date <span class="text-danger">*</span></label>
                            <input type="date" name="from_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">To Date <span class="text-danger">*</span></label>
                            <input type="date" name="to_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Reason for Leave <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control form-control-sm" rows="3" required placeholder="Describe reason for leave..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit Application</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
