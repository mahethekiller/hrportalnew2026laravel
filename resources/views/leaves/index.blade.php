@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Leave Management</h1>
            <p class="text-body-secondary fs-7 mb-0">Track and manage employee leave applications and status approvals.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('leave-types.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-gear me-1"></i> Leave Types & Quotas
            </a>
            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('applyLeaveModal').showModal()">
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
                        <div class="fs-4 fw-bold text-body-emphasis">{{ $counts['total'] ?? 0 }}</div>
                        <div class="fs-8 text-body-secondary fw-semibold">Total Applications</div>
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
                        <div class="fs-4 fw-bold text-body-emphasis">{{ $counts['pending'] ?? 0 }}</div>
                        <div class="fs-8 text-body-secondary fw-semibold">Pending Approvals</div>
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
                        <div class="fs-4 fw-bold text-body-emphasis">{{ $counts['approved'] ?? 0 }}</div>
                        <div class="fs-8 text-body-secondary fw-semibold">Approved Leaves</div>
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
                        <div class="fs-4 fw-bold text-body-emphasis">{{ $counts['rejected'] ?? 0 }}</div>
                        <div class="fs-8 text-body-secondary fw-semibold">Rejected Applications</div>
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
                        <span class="input-group-text bg-transparent"><i class="fa-solid fa-magnifying-glass text-body-secondary"></i></span>
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
                    <thead class="table-light text-body-secondary fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4" style="min-width: 120px;">Actions</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>Days</th>
                            <th>Applied On</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveApplications as $leave)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        @if((int)$leave->status !== 2)
                                            <button type="button" class="btn btn-sm btn-outline-success px-2.5 rounded-2" title="Approve Leave" onclick="document.getElementById('approveLeaveModal{{ $leave->leave_id }}').showModal()">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </button>
                                        @endif
                                        @if((int)$leave->status !== 3)
                                            <button type="button" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Reject Leave" onclick="document.getElementById('rejectLeaveModal{{ $leave->leave_id }}').showModal()">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            </button>
                                        @endif
                                        <form method="POST" action="{{ route('leaves.destroy', $leave->leave_id) }}" class="d-inline" onsubmit="return confirm('Delete this leave application record?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Delete Record">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Approve Modal (Rule 11) -->
                                    <x-form-modal id="approveLeaveModal{{ $leave->leave_id }}" title="Approve Leave Application" :action="route('leaves.update-status', $leave->leave_id)" submitText="Confirm Approval" submitVariant="success">
                                        <input type="hidden" name="status" value="2">
                                        <p class="fs-7 text-body mb-3">Approve leave for <strong class="text-body-emphasis">{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</strong> from {{ \App\Helpers\DateHelper::format($leave->from_date) }} to {{ \App\Helpers\DateHelper::format($leave->to_date) }} ({{ $leave->total_days }} days)?</p>
                                        <div class="mb-3">
                                            <label class="form-label fs-8 fw-semibold">Approval Remarks (Optional)</label>
                                            <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="Leave approved..."></textarea>
                                        </div>
                                    </x-form-modal>

                                    <!-- Reject Modal (Rule 11) -->
                                    <x-form-modal id="rejectLeaveModal{{ $leave->leave_id }}" title="Reject Leave Application" :action="route('leaves.update-status', $leave->leave_id)" submitText="Confirm Rejection" submitVariant="error">
                                        <input type="hidden" name="status" value="3">
                                        <p class="fs-7 text-body mb-3">Reject leave for <strong class="text-body-emphasis">{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</strong>?</p>
                                        <div class="mb-3">
                                            <label class="form-label fs-8 fw-semibold">Rejection Reason / Remarks <span class="text-danger">*</span></label>
                                            <textarea name="remarks" class="form-control form-control-sm" rows="2" required placeholder="State rejection reason..."></textarea>
                                        </div>
                                    </x-form-modal>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width:35px; height:35px;">
                                            {{ substr($leave->employee->first_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis">{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? 'N/A' }}</div>
                                            <div class="fs-9 text-body-secondary">{{ $leave->employee->employee_id ?? 'No Code' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fw-semibold">{{ $leave->leave_type_name }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium text-body-emphasis"><x-human-date :value="$leave->from_date" /></div>
                                    <div class="fs-9 text-body-secondary">to <x-human-date :value="$leave->to_date" /></div>
                                </td>
                                <td>
                                    <span class="fw-bold text-body-emphasis">{{ $leave->total_days }} day(s)</span>
                                </td>
                                <td>
                                    <span class="text-body-secondary fs-8"><x-human-date :value="$leave->applied_on" /></span>
                                </td>
                                <td class="pe-4">
                                    <x-status-badge :status="$leave->status_label" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-body-secondary">
                                    <i class="fa-solid fa-calendar-xmark fs-2 mb-2 d-block text-body-secondary"></i>
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

<!-- Modal: Apply Leave (Rule 9, 10 & 11) -->
<x-form-modal id="applyLeaveModal" title="Apply for Leave" :action="route('leaves.store')" submitText="Submit Application" submitVariant="primary">
    @php
        $currentUserEmpId = Auth::user()->employee->user_id ?? Auth::user()->id;
    @endphp
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Employee <span class="text-danger">*</span></label>
        <select name="employee_id" class="form-select form-select-sm select-search" required>
            @foreach($employees as $emp)
                <option value="{{ $emp->user_id }}" {{ (old('employee_id', request('employee_id')) == $emp->user_id || $currentUserEmpId == $emp->user_id) ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'ID:'.$emp->user_id }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Leave Type <span class="text-danger">*</span></label>
        <select name="leave_type_id" class="form-select form-select-sm select-search" required>
            <option value="">Select Leave Type</option>
            @foreach($leaveTypes as $lt)
                <option value="{{ $lt->leave_type_id }}" {{ old('leave_type_id') == $lt->leave_type_id ? 'selected' : '' }}>
                    {{ $lt->type_name }} ({{ $lt->days_per_year }} days/yr)
                </option>
            @endforeach
        </select>
    </div>
    <div class="row g-2 mb-3">
        <div class="col-6">
            <label class="form-label fs-8 fw-semibold">From Date <span class="text-danger">*</span></label>
            <input type="date" name="from_date" class="form-control form-control-sm @error('from_date') is-invalid @enderror" required value="{{ old('from_date', date('Y-m-d')) }}">
            @error('from_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label class="form-label fs-8 fw-semibold">To Date <span class="text-danger">*</span></label>
            <input type="date" name="to_date" class="form-control form-control-sm @error('to_date') is-invalid @enderror" required value="{{ old('to_date', date('Y-m-d')) }}">
            @error('to_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Reason for Leave <span class="text-danger">*</span></label>
        <textarea name="reason" class="form-control form-control-sm @error('reason') is-invalid @enderror" rows="3" required placeholder="Describe reason for leave...">{{ old('reason') }}</textarea>
        @error('reason')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</x-form-modal>
@endsection
