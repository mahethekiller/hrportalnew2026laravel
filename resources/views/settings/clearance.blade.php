@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title -->
    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h4 class="mb-1 text-body-emphasis fw-bold">
                <i class="fa-solid fa-building-circle-check me-2 text-primary"></i> Departmental No-Dues Clearance Hub
            </h4>
            <p class="text-body-secondary fs-7 mb-0">Assign clearance officers, trigger No-Dues action emails, update stage statuses, and review exit audit trails.</p>
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

    <!-- Executive Exit Analytics KPI Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-right-from-bracket fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-8 text-body-secondary fw-semibold">Total Resignation Notices</div>
                        <div class="fs-4 fw-bold text-body-emphasis">{{ $totalResignations }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-8 text-body-secondary fw-semibold">Fully Cleared / Relieved</div>
                        <div class="fs-4 fw-bold text-success">{{ $completedClearances }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning-subtle text-warning p-3 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-clock fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-8 text-body-secondary fw-semibold">Pending Stage Clearances</div>
                        <div class="fs-4 fw-bold text-warning">{{ $pendingClearances }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Clearance Queue Table Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header border-0 pt-4 bg-body-tertiary d-flex flex-wrap justify-content-between align-items-center gap-2">
            <!-- Stage Filter Tabs -->
            <ul class="nav nav-pills card-header-pills fs-8">
                <li class="nav-item">
                    <a class="nav-link {{ $stageFilter === 'all' ? 'active fw-bold' : '' }}" href="{{ route('clearance.index', ['stage' => 'all', 'search' => $search]) }}">All Clearances</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $stageFilter === 'manager' ? 'active fw-bold' : '' }}" href="{{ route('clearance.index', ['stage' => 'manager', 'search' => $search]) }}">Manager Stage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $stageFilter === 'it' ? 'active fw-bold' : '' }}" href="{{ route('clearance.index', ['stage' => 'it', 'search' => $search]) }}">IT Stage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $stageFilter === 'accounts' ? 'active fw-bold' : '' }}" href="{{ route('clearance.index', ['stage' => 'accounts', 'search' => $search]) }}">Accounts Stage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $stageFilter === 'hr' ? 'active fw-bold' : '' }}" href="{{ route('clearance.index', ['stage' => 'hr', 'search' => $search]) }}">HR Stage</a>
                </li>
            </ul>

            <!-- Search Input -->
            <form method="GET" action="{{ route('clearance.index') }}" class="d-flex align-items-center gap-2">
                <input type="hidden" name="stage" value="{{ $stageFilter }}">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search employee name or ID...">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Target LWD</th>
                            <th>Assigned Officers</th>
                            <th>Clearance Statuses</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resignations as $res)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        {{ strtoupper(substr($res->employee->first_name ?? 'E', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-body-emphasis">{{ $res->employee->first_name ?? '' }} {{ $res->employee->last_name ?? '' }}</div>
                                        <div class="fs-8 text-body-secondary">ID: {{ $res->employee->employee_id ?? 'N/A' }} | {{ $res->employee->department->department_name ?? 'General' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fs-8 fw-bold text-danger">{{ $res->resignation_date }}</div>
                                @if($res->shortfall_days > 0)
                                    <span class="badge bg-warning-subtle text-warning fs-9">{{ $res->shortfall_days }} Shortfall Day(s)</span>
                                @endif
                            </td>
                            <td>
                                <div class="fs-8 text-body-secondary">
                                    <strong>IT:</strong> {{ $res->itPerson ? ($res->itPerson->first_name . ' ' . $res->itPerson->last_name) : 'Unassigned' }}<br>
                                    <strong>Accounts:</strong> {{ $res->accountPerson ? ($res->accountPerson->first_name . ' ' . $res->accountPerson->last_name) : 'Unassigned' }}<br>
                                    <strong>HR:</strong> {{ $res->hrPerson ? ($res->hrPerson->first_name . ' ' . $res->hrPerson->last_name) : 'Unassigned' }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $mH = $res->getStageStatusHelper((int)$res->manager_status);
                                    $iH = $res->getStageStatusHelper((int)$res->it_status);
                                    $aH = $res->getStageStatusHelper((int)$res->account_status);
                                    $hH = $res->getStageStatusHelper((int)$res->hr_status);
                                @endphp
                                <div class="d-flex flex-column gap-1 fs-9">
                                    <span>Manager: <span class="{{ $mH['class'] }}">{{ $mH['label'] }}</span></span>
                                    <span>IT: <span class="{{ $iH['class'] }}">{{ $iH['label'] }}</span></span>
                                    <span>Accounts: <span class="{{ $aH['class'] }}">{{ $aH['label'] }}</span></span>
                                    <span>HR: <span class="{{ $hH['class'] }}">{{ $hH['label'] }}</span></span>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#assignModal_{{ $res->resignation_id }}">
                                        <i class="fa-solid fa-user-gear me-1"></i> Assign Officers
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#clearanceModal_{{ $res->resignation_id }}">
                                        <i class="fa-solid fa-square-check me-1"></i> Update Clearance
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal: Assign Clearance Officers -->
                        <div class="modal fade" id="assignModal_{{ $res->resignation_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-start">
                                    <form method="POST" action="{{ route('clearance.assign', $res->resignation_id) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-gear text-primary me-2"></i> Assign Clearance Officers</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fs-8 fw-semibold">IT Department Clearance Officer</label>
                                                <select name="it_person" class="form-select form-select-sm">
                                                    <option value="">-- Select IT Officer --</option>
                                                    @foreach($officers as $off)
                                                        <option value="{{ $off->user_id }}" {{ (int)$res->it_person === (int)$off->user_id ? 'selected' : '' }}>
                                                            {{ $off->first_name }} {{ $off->last_name }} ({{ $off->employee_id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fs-8 fw-semibold">Accounts Department Clearance Officer</label>
                                                <select name="account_per" class="form-select form-select-sm">
                                                    <option value="">-- Select Accounts Officer --</option>
                                                    @foreach($officers as $off)
                                                        <option value="{{ $off->user_id }}" {{ (int)$res->account_per === (int)$off->user_id ? 'selected' : '' }}>
                                                            {{ $off->first_name }} {{ $off->last_name }} ({{ $off->employee_id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fs-8 fw-semibold">HR Department Clearance Officer</label>
                                                <select name="hr_person" class="form-select form-select-sm">
                                                    <option value="">-- Select HR Officer --</option>
                                                    @foreach($officers as $off)
                                                        <option value="{{ $off->user_id }}" {{ (int)$res->hr_person === (int)$off->user_id ? 'selected' : '' }}>
                                                            {{ $off->first_name }} {{ $off->last_name }} ({{ $off->employee_id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i> Save Officer Assignments</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal: Update Clearance Status & Send Email -->
                        <div class="modal fade" id="clearanceModal_{{ $res->resignation_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content text-start">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-check-double text-success me-2"></i> Update Stage Clearance - {{ $res->employee->first_name ?? '' }} {{ $res->employee->last_name ?? '' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Resend Email Triggers -->
                                        <div class="p-3 rounded bg-body-tertiary border mb-4">
                                            <div class="fw-bold fs-8 text-body-emphasis mb-2"><i class="fa-solid fa-paper-plane text-primary me-1"></i> Send / Resend Clearance Action Notification Emails</div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <form method="POST" action="{{ route('clearance.notify', $res->resignation_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="stage" value="it">
                                                    <button type="submit" class="btn btn-outline-info btn-sm fw-bold"><i class="fa-solid fa-paper-plane me-1"></i> Notify IT Officer</button>
                                                </form>
                                                <form method="POST" action="{{ route('clearance.notify', $res->resignation_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="stage" value="accounts">
                                                    <button type="submit" class="btn btn-outline-success btn-sm fw-bold"><i class="fa-solid fa-paper-plane me-1"></i> Notify Accounts Officer</button>
                                                </form>
                                                <form method="POST" action="{{ route('clearance.notify', $res->resignation_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="stage" value="hr">
                                                    <button type="submit" class="btn btn-outline-purple btn-sm fw-bold"><i class="fa-solid fa-paper-plane me-1"></i> Notify HR Officer</button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Update Stage Form -->
                                        <form method="POST" action="{{ route('clearance.update', $res->resignation_id) }}">
                                            @csrf
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Select Department Stage <span class="text-danger">*</span></label>
                                                    <select name="stage" class="form-select form-select-sm" required>
                                                        <option value="manager">Stage 1: Reporting Manager</option>
                                                        <option value="it">Stage 2: IT Department</option>
                                                        <option value="accounts">Stage 3: Accounts Department</option>
                                                        <option value="hr">Stage 4: HR Department</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis">Clearance Decision <span class="text-danger">*</span></label>
                                                    <select name="status" class="form-select form-select-sm" required>
                                                        <option value="1">Cleared / No Dues</option>
                                                        <option value="2">Pending / Dues Pending</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Clearance Remarks & Recovery Details <span class="text-danger">*</span></label>
                                                <textarea name="comment" rows="3" class="form-control form-control-sm" required placeholder="Enter clearance remarks, asset return condition, or pending recovery details..."></textarea>
                                            </div>

                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success btn-sm fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i> Save Clearance Decision</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-body-secondary">
                                <i class="fa-solid fa-folder-open fa-2x mb-2"></i>
                                <div>No resignation records match the selected filter.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($resignations->hasPages())
                <div class="card-footer border-0 bg-body-tertiary px-4 py-3">
                    {{ $resignations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
