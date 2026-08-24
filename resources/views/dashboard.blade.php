@extends('layouts.app')

@section('title', 'HR Dashboard')
@section('page_title', 'Enterprise HR Overview')

@section('content')

<!-- Signature "My Day" Persona Card -->
<x-my-day-card 
    :roleName="Auth::user()?->roleRelation?->role_name ?? 'employee'"
    :pendingApprovalsCount="$pendingApprovalsCount ?? 3"
    :leaveBalance="$leaveBalance ?? 18"
    :candidateStallsCount="$candidateStallsCount ?? 2"
    :hrPendingQueueCount="$hrPendingQueueCount ?? 5"
/>

<!-- System HR Announcement Banner -->
<div class="announcement-banner mb-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <div class="btn btn-light-primary btn-sm rounded-circle p-2">
            <i class="fa-solid fa-bullhorn fs-5"></i>
        </div>
        <div>
            <div class="fw-bold text-body-emphasis">📢 Q3 Performance Review Cycle is Open</div>
            <div class="small text-body-secondary">All managers and employees are requested to submit self-evaluations before August 31st.</div>
        </div>
    </div>
    <button class="btn btn-light-primary btn-sm d-none d-md-block">Start Evaluation</button>
</div>

<!-- Compact Metric Stat Cards (Maintainable Blade Components) -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Total Employees -->
    <div class="col-xl-3 col-md-6">
        <x-kpi-card 
            title="Total Headcount" 
            value="1,596" 
            icon="fa-solid fa-users" 
            variant="primary" 
            badgeText="+8.4% YoY" 
            badgeTrend="up" 
        />
    </div>

    <!-- Stat 2: Active Leave Today -->
    <div class="col-xl-3 col-md-6">
        <x-kpi-card 
            title="Active Leave Today" 
            value="14" 
            icon="fa-solid fa-calendar-minus" 
            variant="warning" 
            badgeText="2 Pending" 
            badgeTrend="down" 
        />
    </div>

    <!-- Stat 3: Monthly Payroll -->
    <div class="col-xl-3 col-md-6">
        <x-kpi-card 
            title="Monthly Payroll" 
            value="$284.5k" 
            icon="fa-solid fa-wallet" 
            variant="success" 
            badgeText="Processed" 
            badgeTrend="up" 
        />
    </div>

    <!-- Stat 4: Shift Clock-In Tracker Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-primary shadow-xs">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="label-sm text-primary">Shift Clock-In</span>
                    <span class="badge bg-success-subtle text-success">On Time</span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold fs-5 font-mono text-body-emphasis mb-0">07:42:15</div>
                        <div class="small text-body-secondary">Logged today</div>
                    </div>
                    <button class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-right-from-bracket me-1"></i>Clock Out</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Charts Row -->
<div class="row g-3 mb-4">
    <!-- Area Chart: Workforce Hiring vs Turnover -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Workforce Growth & Turnover Trends</h3>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-light-primary active">2026</button>
                    <button type="button" class="btn btn-light-primary">2025</button>
                </div>
            </div>
            <div class="card-body">
                <div id="hiringGrowthChart" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <!-- Radial Gauge Chart: Team Performance Index -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Team Performance Index</h3>
                <span class="badge badge-light-success">Excellent</span>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div id="performanceGaugeChart" style="min-height: 240px; width: 100%;"></div>
                <div class="text-center mt-2">
                    <div class="fw-bold text-body-emphasis">88% Overall Efficiency Score</div>
                    <div class="small text-body-secondary">+4.2% higher than last quarter target</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Section: Birthdays, Pending Approvals & Activity Timeline -->
<div class="row g-3">
    <!-- Table: Pending Approvals -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Pending Leave & Document Approvals</h3>
                <a href="#" class="btn btn-light-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="btn btn-light-primary btn-sm rounded-circle p-1">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-body-emphasis">Sarah Connor</div>
                                            <div class="small text-body-secondary">Lead Architect</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Annual Leave</td>
                                <td>3 Days (Jul 28 - Jul 30)</td>
                                <td><span class="badge badge-light-warning">Pending Approval</span></td>
                                <td class="text-end">
                                    <button class="btn btn-light-success btn-sm me-1"><i class="fa-solid fa-check"></i></button>
                                    <button class="btn btn-light-danger btn-sm"><i class="fa-solid fa-xmark"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="btn btn-light-warning btn-sm rounded-circle p-1">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-body-emphasis">Michael Scott</div>
                                            <div class="small text-body-secondary">HR Manager</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Sick Leave</td>
                                <td>1 Day (Jul 27)</td>
                                <td><span class="badge badge-light-warning">Pending Approval</span></td>
                                <td class="text-end">
                                    <button class="btn btn-light-success btn-sm me-1"><i class="fa-solid fa-check"></i></button>
                                    <button class="btn btn-light-danger btn-sm"><i class="fa-solid fa-xmark"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Birthdays & Anniversaries Widget -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Birthdays & Anniversaries</h3>
                <span class="label-sm">This Week</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn btn-light-warning btn-sm rounded-circle p-2">
                            <i class="fa-solid fa-cake-candles"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-body-emphasis">Elena Rostova</div>
                            <div class="small text-body-secondary">Birthday Today 🎉</div>
                        </div>
                    </div>
                    <button class="btn btn-light-primary btn-sm"><i class="fa-solid fa-paper-plane me-1"></i>Wish</button>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn btn-light-success btn-sm rounded-circle p-2">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-body-emphasis">Alexander Wright</div>
                            <div class="small text-body-secondary">3rd Work Anniversary (Tomorrow)</div>
                        </div>
                    </div>
                    <button class="btn btn-light-primary btn-sm"><i class="fa-solid fa-paper-plane me-1"></i>Wish</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialog Component -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addEmployeeModalLabel">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Full Name</label>
                        <input type="text" class="form-control" placeholder="Enter employee full name">
                    </div>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Email Address</label>
                        <input type="email" class="form-control" placeholder="name@company.com">
                    </div>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Department</label>
                        <select class="form-select">
                            <option selected>Select Department...</option>
                            <option value="eng">Engineering</option>
                            <option value="hr">Human Resources</option>
                            <option value="fin">Finance</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm">Save Employee</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ApexCharts 1: Area Growth Chart
        const hiringOptions = {
            series: [{
                name: 'New Hires',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'Turnover',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 280,
                type: 'area',
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#1B84FF', '#F8285A'],
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"]
            }
        };
        new ApexCharts(document.querySelector("#hiringGrowthChart"), hiringOptions).render();

        // ApexCharts 2: Radial Performance Gauge Chart
        const gaugeOptions = {
            series: [88],
            chart: {
                height: 240,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    hollow: { size: '65%' },
                    dataLabels: {
                        name: { show: false },
                        value: {
                            fontSize: '24px',
                            fontWeight: 700,
                            offsetY: 8
                        }
                    }
                }
            },
            colors: ['#17C653'],
            labels: ['Efficiency']
        };
        new ApexCharts(document.querySelector("#performanceGaugeChart"), gaugeOptions).render();
    });
</script>
@endpush
