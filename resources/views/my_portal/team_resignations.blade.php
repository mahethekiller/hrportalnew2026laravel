@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title -->
    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h4 class="mb-1 text-body-emphasis fw-bold">
                <i class="fa-solid fa-users-slash me-2 text-warning"></i> Team Resignations & Exit Approvals
            </h4>
            <p class="text-body-secondary fs-7 mb-0">Review resignation requests from direct reportees, confirm Last Working Day (LWD), and submit clearance decisions.</p>
        </div>
    </div>

    <!-- Alert Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header border-0 pt-4 bg-body-tertiary">
            <h5 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-list me-2 text-primary"></i> Team Resignation Requests
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="min-width: 170px;">Actions</th>
                            <th>Employee</th>
                            <th>Notice Date</th>
                            <th>Requested LWD</th>
                            <th>Notice Period</th>
                            <th class="pe-4">Manager Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamResignations as $res)
                        <tr>
                            <td class="ps-4">
                                <button type="button" class="btn btn-primary btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#reviewModal_{{ $res->resignation_id }}">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Review & Respond
                                </button>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        {{ strtoupper(substr($res->employee->first_name ?? 'E', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-body-emphasis">{{ $res->employee->first_name ?? '' }} {{ $res->employee->last_name ?? '' }}</div>
                                        <div class="fs-8 text-body-secondary">ID: {{ $res->employee->employee_id ?? 'N/A' }} | {{ $res->employee->designation->designation_name ?? 'Staff' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="fs-8 fw-semibold text-body-emphasis">{{ $res->notice_date }}</td>
                            <td class="fs-8 fw-bold text-danger">{{ $res->resignation_date }}</td>
                            <td class="fs-8 text-body-secondary">{{ $res->employee->notice_period_months ?? 1 }} Month(s)</td>
                            <td class="pe-4">
                                @php $mgrHelper = $res->getStageStatusHelper((int) $res->manager_status); @endphp
                                <span class="{{ $mgrHelper['class'] }} fs-8"><i class="fa-solid {{ $mgrHelper['icon'] }} me-1"></i> {{ $mgrHelper['label'] }}</span>
                            </td>
                        </tr>

                        <!-- Modal: Manager Review & Respond -->
                        <div class="modal fade" id="reviewModal_{{ $res->resignation_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('my-portal.team_resignations.respond', $res->resignation_id) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa-solid fa-user-tie text-primary me-2"></i> Review Resignation - {{ $res->employee->first_name ?? '' }} {{ $res->employee->last_name ?? '' }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="p-3 rounded bg-body-tertiary border mb-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="fs-8 text-body-secondary">Employee Name:</div>
                                                        <div class="fw-bold text-body-emphasis mb-2">{{ $res->employee->first_name ?? '' }} {{ $res->employee->last_name ?? '' }}</div>
                                                        <div class="fs-8 text-body-secondary">Notice Date:</div>
                                                        <div class="fw-bold text-body-emphasis">{{ $res->notice_date }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="fs-8 text-body-secondary">Configured Notice Period:</div>
                                                        <div class="fw-bold text-body-emphasis mb-2">{{ $res->employee->notice_period_months ?? 1 }} Month(s)</div>
                                                        <div class="fs-8 text-body-secondary">Requested LWD:</div>
                                                        <div class="fw-bold text-danger">{{ $res->resignation_date }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Employee Resignation Reason</label>
                                                <div class="p-3 rounded bg-body-tertiary fs-8 text-body-secondary border">
                                                    {!! $res->clean_reason !!}
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Manager Decision <span class="text-danger">*</span></label>
                                                    <select name="status" class="form-select form-select-sm" required>
                                                        <option value="1" {{ (int)$res->manager_status === 1 ? 'selected' : '' }}>Accept / Approve Resignation</option>
                                                        <option value="2" {{ (int)$res->manager_status === 2 ? 'selected' : '' }}>Reject / Retain Employee</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Confirmed Last Working Day (LWD) <span class="text-danger">*</span></label>
                                                    <input type="date" name="resignation_date" class="form-control form-control-sm" required value="{{ $res->resignation_date }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Manager Remarks & Handover Notes <span class="text-danger">*</span></label>
                                                <textarea name="manager_comment" rows="3" class="form-control form-control-sm" required placeholder="Enter approval remarks or reason for rejection...">{{ $res->plain_manager_comment }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm fw-bold">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> Submit Response
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-body-secondary">
                                <i class="fa-solid fa-folder-open fa-2x mb-2"></i>
                                <div>No team resignation requests found.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($teamResignations->hasPages())
                <div class="card-footer border-0 bg-body-tertiary px-4 py-3">
                    {{ $teamResignations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
