@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-users-gear me-2 text-primary"></i> Manager Team Workstation</h4>
        <p class="text-muted fs-8 mb-0">Monitor your direct reports, approve team leaves, and review team performance.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Direct Team Members</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ count($teamMembers) }}</h3>
                </div>
                <div class="bg-light-primary text-primary p-3 rounded-circle">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Pending Leave Approvals</span>
                    <h3 class="fw-bolder text-warning mb-0 mt-1">{{ count($pendingLeaves) }}</h3>
                </div>
                <div class="bg-light-warning text-warning p-3 rounded-circle">
                    <i class="fa-solid fa-clock-rotate-left fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Recent Team Reviews</span>
                    <h3 class="fw-bolder text-success mb-0 mt-1">{{ count($recentAppraisals) }}</h3>
                </div>
                <div class="bg-light-success text-success p-3 rounded-circle">
                    <i class="fa-solid fa-chart-line fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-0 pt-3 bg-white d-flex align-items-center justify-content-between">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-users me-2 text-primary"></i> Direct Reports</h5>
                <a href="{{ route('manager-portal.team_attendance') }}" class="btn btn-light-primary btn-sm fs-9 fw-bold">Team Timesheets</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 fs-8">
                        <thead class="bg-light">
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
                                    <td class="ps-4 fw-bold text-gray-900">{{ $member->first_name }} {{ $member->last_name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->designation_id }}</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No direct reports found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header border-0 pt-3 bg-white">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-wrench me-2 text-primary"></i> Manager Team Controls</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-light-warning text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-calendar-check me-2"></i> Review Team Leave Requests
                    </a>
                    <a href="{{ route('manager-portal.team_attendance') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-clock me-2"></i> Team Daily Clock-In Timesheets
                    </a>
                    <a href="{{ route('manager-portal.team_performance') }}" class="btn btn-light-success text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-star me-2"></i> Conduct Team Appraisals
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
