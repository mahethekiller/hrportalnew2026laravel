@extends('layouts.app')

@section('title', 'Training & Development Sessions')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Training & Development Sessions</h1>
            <p class="text-muted fs-7 mb-0">Manage employee skill development courses, instructor assignments, investments, and progress.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('trainers.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-chalkboard-user me-1"></i> Instructors & Trainers
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTrainingModal">
                <i class="fa-solid fa-plus me-1"></i> Schedule Training Session
            </button>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-primary text-primary me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-graduation-cap fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Sessions</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['total_sessions'] ?? 0 }} Courses</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-warning text-warning me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-clock fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Pending Sessions</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['pending_sessions'] ?? 0 }} Upcoming</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-success text-success me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Completed Courses</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['completed_sessions'] ?? 0 }} Passed</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-info text-info me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-sack-dollar fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Investment</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['total_investment'] ?? '₹0.00' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Training Sessions Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('training-sessions.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search employee, training course, or instructor..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Session Statuses</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>In Progress</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Completed</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('training-sessions.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Enrolled Employee</th>
                            <th>Course / Skill Topic</th>
                            <th>Instructor Panel</th>
                            <th>Schedule Dates</th>
                            <th>Training Cost</th>
                            <th>Status & Grade</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $sn)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900">{{ $sn->employee ? ($sn->employee->first_name . ' ' . $sn->employee->last_name) : 'Staff' }}</div>
                                    <div class="fs-9 text-muted">{{ $sn->employee->email ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-8 fw-semibold">{{ $sn->trainingType->type ?? 'Skill Training' }}</span>
                                    @if($sn->description)
                                        <div class="fs-9 text-muted">{{ Str::limit($sn->description, 35) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-gray-800 fs-8"><i class="fa-solid fa-user-tie me-1 text-muted"></i>{{ $sn->trainer ? $sn->trainer->full_name : 'External Trainer' }}</span>
                                </td>
                                <td>
                                    <span class="fw-medium text-gray-800 fs-8">{{ $sn->start_date }}</span>
                                    <div class="fs-9 text-muted">to {{ $sn->finish_date }}</div>
                                </td>
                                <td>
                                    <span class="font-monospace text-success fw-bold">{{ $sn->formatted_cost }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $sn->status_badge_class }}">
                                        {{ $sn->status_label }}
                                    </span>
                                    @if($sn->performance)
                                        <div class="fs-9 text-muted mt-1"><i class="fa-solid fa-star me-1 text-warning"></i>{{ $sn->performance }}</div>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-sm btn-light-secondary py-1 px-2 fs-8 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Update Status
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end fs-8">
                                            <li>
                                                <form method="POST" action="{{ route('training-sessions.status', $sn->training_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="training_status" value="1">
                                                    <button type="submit" class="dropdown-item text-primary"><i class="fa-solid fa-spinner me-2"></i> Mark In Progress</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('training-sessions.status', $sn->training_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="training_status" value="2">
                                                    <input type="hidden" name="performance" value="Passed / Excellent">
                                                    <button type="submit" class="dropdown-item text-success"><i class="fa-solid fa-check-circle me-2"></i> Mark Completed (Passed)</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('training-sessions.status', $sn->training_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="training_status" value="3">
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-ban me-2"></i> Terminate Session</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-graduation-cap fs-2 mb-2 d-block text-muted"></i>
                                    No training sessions scheduled.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($sessions->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $sessions->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Schedule Training Session -->
<div class="modal fade" id="createTrainingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('training-sessions.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-graduation-cap me-2 text-primary"></i> Schedule Employee Training Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Enrolled Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select form-select-sm" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->user_id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Training Course Type <span class="text-danger">*</span></label>
                            <select name="training_type_id" class="form-select form-select-sm" required>
                                <option value="">Select Course Type</option>
                                @foreach($trainingTypes as $tt)
                                    <option value="{{ $tt->training_type_id }}">{{ $tt->type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Assigned Trainer / Instructor <span class="text-danger">*</span></label>
                            <select name="trainer_id" class="form-select form-select-sm" required>
                                <option value="">Select Instructor</option>
                                @foreach($trainers as $tr)
                                    <option value="{{ $tr->trainer_id }}">{{ $tr->full_name }} ({{ $tr->expertise ?? 'Trainer' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Training Cost (INR ₹)</label>
                            <input type="number" step="0.01" name="training_cost" class="form-control form-control-sm" value="0.00" placeholder="0.00">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Completion Date <span class="text-danger">*</span></label>
                            <input type="date" name="finish_date" class="form-control form-control-sm" required value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Training Objective & Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Specify course agenda, learning outcome, and objectives..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Schedule Training</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
