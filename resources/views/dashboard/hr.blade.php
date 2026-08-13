@extends('layouts.app')

@section('title', 'HR Workstation')
@section('page_title', 'HR & Operational Dashboard')

@section('content')

<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Welcome Back, {{ auth()->user()->first_name ?? auth()->user()->name ?? 'HR Officer' }} 👋</h2>
        <p class="text-body-secondary small mb-0">Operational workforce statistics, pending verifications, and daily attendance logs.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    </div>
@endif

<!-- Metric Stats Overview -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Active Headcount</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $activeEmployeesCount }}</h3>
                </div>
                <div class="bg-light-primary text-primary p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <a href="{{ route('manager-portal.profile_approvals.index') }}" class="text-decoration-none d-flex align-items-center justify-content-between w-100">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Pending Profile Verifications</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $pendingProfileUpdates }}</h3>
                </div>
                <div class="bg-light-warning text-warning p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-user-check fs-4"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <a href="{{ route('manager-portal.team_leaves') }}" class="text-decoration-none d-flex align-items-center justify-content-between w-100">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Pending Leave Approvals</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $pendingLeaves }}</h3>
                </div>
                <div class="bg-light-danger text-danger p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-calendar-check fs-4"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Total Checked In Today</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $todayOfficePunchCount + $todayActiveWfhCount }}</h3>
                </div>
                <div class="bg-light-success text-success p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-circle-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        
        <!-- Daily Work Distribution Chart/Stats -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-clock me-2 text-success"></i> Today's Workforce Logins</h5>
                <p class="text-muted fs-9 mb-0">Visual break up of where staff is logging in from today.</p>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="p-3 border rounded bg-light bg-opacity-50">
                            <span class="fs-9 text-muted d-block fw-bold text-uppercase">Work From Office (WFO)</span>
                            <span class="display-lg fw-bolder text-success mt-1 d-block">{{ $todayOfficePunchCount }}</span>
                            <span class="fs-9 text-muted">punched via biometric/manual</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-light bg-opacity-50">
                            <span class="fs-9 text-muted d-block fw-bold text-uppercase">Work From Home (WFH)</span>
                            <span class="display-lg fw-bolder text-warning mt-1 d-block">{{ $todayActiveWfhCount }}</span>
                            <span class="fs-9 text-muted">clocked via Employee Portal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HR Workflows -->
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-circle-nodes me-2 text-primary"></i> HR Operations Hub</h5>
                <p class="text-muted fs-9 mb-0">Direct routes to execute key employee transactions.</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-900 fs-8 mb-1">Onboard New Hire</h6>
                            <p class="text-muted fs-9 mb-2">Create employee skeleton record and copy passwordless link.</p>
                            <a href="{{ route('employees.index') }}" class="btn btn-primary btn-sm fs-9 fw-bold"><i class="fa-solid fa-link me-1"></i> Copy Link Directory</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-900 fs-8 mb-1">Leave Requests</h6>
                            <p class="text-muted fs-9 mb-2">Process pending team leaves and configure balances.</p>
                            <a href="{{ route('manager-portal.team_leaves') }}" class="btn btn-outline-primary btn-sm fs-9 fw-bold">Review Leave Queue</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column Sidebar -->
    <div class="col-lg-4">
        
        <!-- HR Actions Sidebar -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-gray-900 fs-7 mb-0"><i class="fa-solid fa-list-check text-warning me-2"></i> Queue Status</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-user-check me-2"></i> View Profile Approvals ({{ $pendingProfileUpdates }})
                    </a>
                    <a href="{{ route('employees.create') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-user-plus me-2"></i> Register New Employee
                    </a>
                </div>
            </div>
        </div>

        @include('dashboard.partials.sidebar_widgets')
    </div>
</div>

@endsection
