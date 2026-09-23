@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger-subtle text-danger fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-person-walking-arrow-right me-1"></i> Offboarding Hub
                </span>
                <span class="text-body-secondary fs-9">• Team Separations & Exit Transitions</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Team Resignations & Exit Approvals</h4>
            <p class="text-body-secondary fs-8 mb-0">Review resignation notices from direct reportees, confirm Last Working Days (LWD), and issue manager clearances.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('manager-portal.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Manager Workstation
            </a>
            <a href="{{ route('my-portal.resignation') }}" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-user me-1.5"></i> My Resignation
            </a>
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Resignations -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Notices</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $stats['total'] ?? $teamResignations->total() }}</h3>
                        <span class="fs-9 text-body-secondary">Cumulative separation notices</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-folder-open fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Pending Decision</span>
                        <h3 class="fw-bolder {{ ($stats['pending'] ?? 0) > 0 ? 'text-warning' : 'text-body-emphasis' }} mb-1 mt-1">
                            {{ $stats['pending'] ?? 0 }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Awaiting manager response</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-hourglass-half fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Accepted / In Progress -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Accepted Exits</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $stats['accepted'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Active clearance & handover</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Retained / Rejected -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Retained / Declined</span>
                        <h3 class="fw-bolder text-danger mb-1 mt-1">{{ $stats['rejected'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Resignation withdrawn or declined</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-xmark fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Main Resignations Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-users-slash me-2 text-danger"></i> Team Resignation Requests
            </h5>
            <span class="badge bg-body text-body-secondary border px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                {{ $teamResignations->total() }} Total Requests
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 120px;">Actions</th>
                            <th>Employee</th>
                            <th>Notice Submission</th>
                            <th>Requested LWD</th>
                            <th>Notice Policy & Shortfall</th>
                            <th class="pe-4 text-center" style="width: 160px;">Manager Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamResignations as $res)
                            @php
                                $mgrHelper = $res->getStageStatusHelper((int) $res->manager_status);
                                $noticeMonths = $res->employee->notice_period_months ?? 1;
                                $noticeDate = \Carbon\Carbon::parse($res->notice_date);
                                $reqLwd = \Carbon\Carbon::parse($res->resignation_date);
                                $policyExpectedLwd = (clone $noticeDate)->addMonths($noticeMonths);
                                $shortfallDays = $reqLwd->lt($policyExpectedLwd) ? $reqLwd->diffInDays($policyExpectedLwd) : 0;
                            @endphp
                            <tr>
                                <!-- Column 1: Actions (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- Review & Respond Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="Review Resignation & Submit Decision" data-bs-toggle="modal" data-bs-target="#reviewModal_{{ $res->resignation_id }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <!-- If approved, PDF certificate link -->
                                        @if((int) $res->manager_status === 1)
                                            <a href="{{ route('my-portal.resignation.relieving_letter', $res->resignation_id) }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="View Relieving Certificate" target="_blank">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        @if(!empty($res->employee?->email))
                                            <a href="mailto:{{ $res->employee->email }}?subject=Resignation Discussion" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Email Employee">
                                                <i class="fa-solid fa-envelope"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Employee Details -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-8" style="width: 36px; height: 36px; min-width: 36px;">
                                            {{ strtoupper(substr($res->employee->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($res->employee->last_name ?? '', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis leading-tight">
                                                {{ $res->employee->first_name ?? '' }} {{ $res->employee->last_name ?? '' }}
                                            </div>
                                            <div class="fs-9 text-body-secondary font-monospace">
                                                ID: {{ $res->employee->employee_id ?? 'N/A' }} • {{ $res->employee->designation->designation_name ?? 'Staff' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Notice Submission Date -->
                                <td>
                                    <span class="fw-semibold text-body-emphasis fs-8"><x-human-date :value="$res->notice_date" /></span>
                                </td>

                                <!-- Requested LWD -->
                                <td>
                                    <span class="fw-bold text-danger fs-8"><x-human-date :value="$res->resignation_date" /></span>
                                </td>

                                <!-- Policy & Shortfall -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-body text-body-emphasis border px-2 py-1 rounded-2 fs-9">
                                            {{ $noticeMonths }} {{ Str::plural('Month', $noticeMonths) }} Notice
                                        </span>
                                        @if($shortfallDays > 0)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-2 fs-9 fw-semibold" title="Early exit before standard policy">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i> -{{ $shortfallDays }}d Shortfall
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-2 fs-9 fw-semibold">
                                                <i class="fa-solid fa-check me-1"></i> Standard Notice
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Manager Status Badge -->
                                <td class="pe-4 text-center">
                                    <span class="{{ $mgrHelper['class'] }} border fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                        <i class="fa-solid {{ $mgrHelper['icon'] }} me-1"></i> {{ $mgrHelper['label'] }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Modal: Manager Review & Respond -->
                            <div class="modal fade" id="reviewModal_{{ $res->resignation_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-body border-0 shadow">
                                        <form method="POST" action="{{ route('my-portal.team_resignations.respond', $res->resignation_id) }}" onsubmit="submitWithLoader(this)">
                                            @csrf
                                            <div class="modal-header border-bottom">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="p-2 rounded-2 bg-danger-subtle text-danger fs-8">
                                                        <i class="fa-solid fa-user-tie"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">
                                                            Review Resignation — {{ $res->employee->first_name ?? '' }} {{ $res->employee->last_name ?? '' }}
                                                        </h5>
                                                        <span class="fs-9 text-body-secondary">Employee ID: {{ $res->employee->employee_id ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-4">
                                                <!-- Rule 10: In-Modal Validation Alerts -->
                                                @if(isset($errors) && $errors->any())
                                                    <div class="alert alert-danger border-0 shadow-xs mb-3 py-2 px-3 fs-8" role="alert">
                                                        <i class="fa-solid fa-circle-exclamation me-1"></i> Please resolve the input issues below.
                                                    </div>
                                                @endif

                                                <!-- Employee Resignation Overview Card -->
                                                <div class="p-3 rounded-3 bg-body-tertiary border mb-4">
                                                    <div class="row g-3">
                                                        <div class="col-sm-6">
                                                            <span class="text-body-secondary fs-9 fw-bold text-uppercase d-block mb-1">Notice Submission Date</span>
                                                            <div class="fw-bold text-body-emphasis fs-8"><x-human-date :value="$res->notice_date" /></div>
                                                            <div class="fs-9 text-body-secondary mt-1">Configured Policy: {{ $noticeMonths }} Month(s)</div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <span class="text-body-secondary fs-9 fw-bold text-uppercase d-block mb-1">Requested Last Working Day</span>
                                                            <div class="fw-bold text-danger fs-8"><x-human-date :value="$res->resignation_date" /></div>
                                                            <div class="fs-9 text-body-secondary mt-1">
                                                                @if($shortfallDays > 0)
                                                                    <span class="text-danger fw-semibold"><i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $shortfallDays }} days shortfall from policy</span>
                                                                @else
                                                                    <span class="text-success fw-semibold"><i class="fa-solid fa-check me-1"></i> Standard full notice served</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Clean Employee Reason -->
                                                <div class="mb-4">
                                                    <label class="form-label fs-9 fw-bold text-uppercase text-body-secondary">Employee Resignation Reason</label>
                                                    <div class="p-3 rounded-3 bg-body-tertiary border fs-8 text-body-emphasis leading-relaxed">
                                                        {!! $res->clean_reason !!}
                                                    </div>
                                                </div>

                                                <!-- Form Inputs: Decision & Confirmed LWD -->
                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">
                                                            Manager Decision <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="status" class="form-select form-select-sm bg-body text-body-emphasis select-search" required>
                                                            <option value="1" {{ old('status', (int)$res->manager_status) === 1 ? 'selected' : '' }}>
                                                                Accept & Approve Resignation
                                                            </option>
                                                            <option value="2" {{ old('status', (int)$res->manager_status) === 2 ? 'selected' : '' }}>
                                                                Reject / Retain Employee
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis">
                                                            Confirmed Last Working Day (LWD) <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="date" name="resignation_date" class="form-control form-control-sm bg-body text-body-emphasis" required value="{{ old('resignation_date', $res->resignation_date) }}">
                                                    </div>
                                                </div>

                                                <!-- Manager Remarks & Handover Notes -->
                                                <div class="mb-0">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">
                                                        Manager Remarks & Handover Notes <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea name="manager_comment" rows="3" class="form-control form-control-sm bg-body text-body-emphasis" required placeholder="Enter approval remarks, knowledge transfer instructions, or retention comments...">{{ old('manager_comment', $res->plain_manager_comment) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer border-top bg-body-tertiary">
                                                <button type="button" class="btn btn-sm btn-body border text-body-emphasis" data-bs-dismiss="modal">Cancel</button>
                                                <!-- Rule 12: Submit button with loader -->
                                                <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">
                                                    <i class="fa-solid fa-floppy-disk me-1"></i> Save Decision
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-users-slash" 
                                        title="No Team Resignation Requests" 
                                        description="There are currently no active resignation requests submitted by any of your direct team members."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($teamResignations->hasPages())
                <div class="card-footer border-top bg-transparent py-3 px-4">
                    {{ $teamResignations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
