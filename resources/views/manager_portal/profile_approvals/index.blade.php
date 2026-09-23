@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-info-subtle text-info fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-user-check me-1"></i> HR Verification Hub
                </span>
                <span class="text-body-secondary fs-9">• Employee Staged Profile Updates</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Profile Update Approvals</h4>
            <p class="text-body-secondary fs-8 mb-0">Review, compare, and authorize demographic and personal profile modifications submitted by employees.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('manager-portal.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Manager Workstation
            </a>
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Updates -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Submissions</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $stats['total'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Cumulative update requests</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-id-card-clip fs-5"></i>
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
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Pending Review</span>
                        <h3 class="fw-bolder {{ ($stats['pending'] ?? 0) > 0 ? 'text-warning' : 'text-body-emphasis' }} mb-1 mt-1">
                            {{ $stats['pending'] ?? 0 }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Awaiting HR verification</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
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
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Approved Updates</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $stats['approved'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Merged into core profiles</span>
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
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Rejected Updates</span>
                        <h3 class="fw-bolder text-danger mb-1 mt-1">{{ $stats['rejected'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Declined modifications</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-xmark fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Status Tabs Filter -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            @php $currStatus = request('status', '0'); @endphp
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="text-body-secondary fs-9 fw-bold text-uppercase me-2">Filter Queue:</span>
                <a href="{{ route('manager-portal.profile_approvals.index', ['status' => '0']) }}"
                   class="btn btn-sm {{ $currStatus === '0' ? 'btn-warning text-dark shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                    Pending Verification ({{ $stats['pending'] ?? 0 }})
                </a>
                <a href="{{ route('manager-portal.profile_approvals.index', ['status' => '1']) }}"
                   class="btn btn-sm {{ $currStatus === '1' ? 'btn-success shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                    Approved ({{ $stats['approved'] ?? 0 }})
                </a>
                <a href="{{ route('manager-portal.profile_approvals.index', ['status' => '2']) }}"
                   class="btn btn-sm {{ $currStatus === '2' ? 'btn-danger shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                    Rejected ({{ $stats['rejected'] ?? 0 }})
                </a>
                <a href="{{ route('manager-portal.profile_approvals.index', ['status' => 'all']) }}"
                   class="btn btn-sm {{ $currStatus === 'all' ? 'btn-primary shadow-xs' : 'btn-body border text-body-emphasis' }} rounded-2 px-3 py-1.5 fs-9 fw-semibold">
                    All Records ({{ $stats['total'] ?? 0 }})
                </a>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-header border-0 pt-3 pb-2 bg-transparent d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-list-check me-2 text-primary"></i> Profile Update Requests
            </h5>
            <span class="badge bg-body text-body-secondary border px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                {{ $updates->total() }} Total Found
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 100px;">Actions</th>
                            <th>Employee</th>
                            <th>Proposed Email</th>
                            <th>Proposed Mobile</th>
                            <th>Submission Date</th>
                            <th class="pe-4 text-center" style="width: 140px;">Verification Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($updates as $update)
                            <tr>
                                <!-- Column 1: Action (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <a href="{{ route('manager-portal.profile_approvals.show', $update->id) }}" 
                                           class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                           title="Compare Diff & Review Changes">
                                            <i class="fa-solid fa-code-compare"></i>
                                        </a>
                                    </div>
                                </td>

                                <!-- Employee -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-8" style="width: 36px; height: 36px; min-width: 36px;">
                                            {{ strtoupper(substr($update->first_name ?? $update->user->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($update->last_name ?? $update->user->last_name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis leading-tight">
                                                {{ $update->first_name ?? $update->user->first_name }} {{ $update->last_name ?? $update->user->last_name }}
                                            </div>
                                            <div class="fs-9 text-body-secondary font-monospace">
                                                ID: {{ $update->user->employee_id ?? 'N/A' }} • {{ $update->user->designation->designation_name ?? 'Staff' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Proposed Email -->
                                <td>
                                    <span class="text-body-emphasis fs-8">{{ $update->email_personal ?? '—' }}</span>
                                </td>

                                <!-- Proposed Mobile -->
                                <td>
                                    <span class="text-body-emphasis fs-8">{{ $update->contact_no ?? '—' }}</span>
                                </td>

                                <!-- Date -->
                                <td>
                                    <span class="text-body-secondary fs-8"><x-human-date :value="$update->added_date" /></span>
                                </td>

                                <!-- Status -->
                                <td class="pe-4 text-center">
                                    @if($update->acceptance == 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            <i class="fa-solid fa-circle-check me-1"></i> Approved
                                        </span>
                                    @elseif($update->acceptance == 2)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                            <i class="fa-solid fa-clock-rotate-left me-1"></i> Pending Review
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-id-card-clip" 
                                        title="No Profile Update Requests Found" 
                                        description="There are currently no employee demographic update requests matching the selected queue filter."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($updates->hasPages())
                <div class="card-footer border-top bg-transparent py-3 px-4">
                    {{ $updates->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
