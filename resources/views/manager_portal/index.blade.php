@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-users-gear me-2 text-primary"></i> Manager Team Workstation</h4>
        <p class="text-body-secondary fs-8 mb-0">Monitor your direct reports, approve team leaves, and review team performance.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">Direct Team Members</span>
                    <h3 class="fw-bolder text-body-emphasis mb-0 mt-1">{{ count($teamMembers) }}</h3>
                </div>
                <div class="avatar-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center p-3" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">Pending Leave Approvals</span>
                    <h3 class="fw-bolder text-warning mb-0 mt-1">{{ count($pendingLeaves) }}</h3>
                </div>
                <div class="avatar-md rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center p-3" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-clock-rotate-left fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">Recent Team Reviews</span>
                    <h3 class="fw-bolder text-success mb-0 mt-1">{{ count($recentAppraisals) }}</h3>
                </div>
                <div class="avatar-md rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center p-3" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-chart-line fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
            <div class="card-header border-0 pt-3 bg-transparent d-flex align-items-center justify-content-between">
                <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-users me-2 text-primary"></i> Direct Reports</h5>
                <a href="{{ route('manager-portal.team_attendance') }}" class="btn btn-primary-subtle text-primary btn-sm fs-9 fw-bold">Team Timesheets</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 fs-8">
                        <thead class="bg-body-secondary">
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>Email</th>
                                <th>Designation</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teamMembers as $member)
                                <tr>
                                    <td class="ps-4 fw-bold text-body-emphasis">{{ $member->first_name }} {{ $member->last_name }}</td>
                                    <td class="text-body-secondary">{{ $member->email }}</td>
                                    <td class="text-body-secondary">{{ $member->designation_id }}</td>
                                    <td><span class="badge bg-success-subtle text-success fw-bold fs-9">Active</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <x-empty-state 
                                            icon="fa-solid fa-users-slash" 
                                            title="No Direct Reports" 
                                            description="There are currently no employee records assigned under your direct team management."
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary dashboard-card">
            <div class="card-header border-0 pt-3 bg-transparent">
                <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-wrench me-2 text-primary"></i> Manager Team Controls</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    @can('edit.employees')
                    <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-body text-start fs-8 fw-semibold py-2 border border-subtle text-body-emphasis shadow-xs">
                        <i class="fa-solid fa-user-check me-2 text-info"></i> HR Profile Update Approvals
                    </a>
                    @endcan
                    <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-body text-start fs-8 fw-semibold py-2 border border-subtle text-body-emphasis shadow-xs">
                        <i class="fa-solid fa-calendar-check me-2 text-warning"></i> Review Team Leave Requests
                    </a>
                    <a href="{{ route('manager-portal.team_attendance') }}" class="btn btn-body text-start fs-8 fw-semibold py-2 border border-subtle text-body-emphasis shadow-xs">
                        <i class="fa-solid fa-clock me-2 text-primary"></i> Team Daily Clock-In Timesheets
                    </a>
                    <a href="{{ route('manager-portal.team_performance') }}" class="btn btn-body text-start fs-8 fw-semibold py-2 border border-subtle text-body-emphasis shadow-xs">
                        <i class="fa-solid fa-star me-2 text-success"></i> Conduct Team Appraisals
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
