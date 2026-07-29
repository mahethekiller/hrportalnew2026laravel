@extends('layouts.app')

@section('title', 'HR Dashboard')
@section('page_title', 'Enterprise HR Overview')

@section('content')
<!-- System HR Announcement Banner -->
<div class="announcement-banner mb-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <div class="btn btn-light-primary btn-sm rounded-circle p-2">
            <i class="fa-solid fa-bullhorn fs-5"></i>
        </div>
        <div>
            <div class="fw-bold text-body-emphasis">📢 Q3 Performance Review Cycle is Now Open</div>
            <div class="small text-body-secondary">All managers and employees are requested to submit self-evaluations before August 15th.</div>
        </div>
    </div>
    <button class="btn btn-light-primary btn-sm d-none d-md-block">Start Evaluation</button>
</div>

<!-- Overview Header Banner -->
<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Welcome Back, {{ Auth::user()->first_name ?? 'Administrator' }} 👋</h2>
        <p class="text-body-secondary small mb-0">Enterprise workforce metrics and operational dashboard overview.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <button class="btn btn-light-primary btn-sm me-2">
            <i class="fa-solid fa-file-export me-1"></i>Export Report
        </button>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fa-solid fa-user-plus me-1"></i>Add Employee
        </button>
    </div>
</div>

<!-- Quick Action Grid Tiles -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="#" class="quick-action-tile">
            <i class="fa-solid fa-calendar-plus text-primary fs-4 mb-2 d-block"></i>
            <div class="fw-semibold small">Apply Leave</div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="#" class="quick-action-tile">
            <i class="fa-solid fa-receipt text-success fs-4 mb-2 d-block"></i>
            <div class="fw-semibold small">Submit Claim</div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="#" class="quick-action-tile">
            <i class="fa-solid fa-file-invoice-dollar text-info fs-4 mb-2 d-block"></i>
            <div class="fw-semibold small">View Payslip</div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="#" class="quick-action-tile">
            <i class="fa-solid fa-sitemap text-warning fs-4 mb-2 d-block"></i>
            <div class="fw-semibold small">Org Directory</div>
        </a>
    </div>
</div>

<!-- Compact Metric Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Stat 1 -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="label-sm">Total Employees</span>
                    <span class="badge badge-light-primary"><i class="fa-solid fa-users me-1"></i>Active</span>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <h3 class="display-lg text-body-emphasis mb-0">1,596</h3>
                    <span class="badge badge-light-success"><i class="fa-solid fa-arrow-trend-up me-1"></i>+8.4%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="label-sm">Active Leave Today</span>
                    <span class="badge badge-light-warning"><i class="fa-solid fa-clock me-1"></i>2 Pending</span>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <h3 class="display-lg text-body-emphasis mb-0">14</h3>
                    <span class="text-body-secondary small">0.8% of staff</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="label-sm">Monthly Payroll</span>
                    <span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Synced</span>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <h3 class="display-lg text-body-emphasis mb-0">$284.5k</h3>
                    <span class="badge badge-light-success">+3.2%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Shift Clock-In Tracker Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="label-sm text-primary">Shift Clock-In</span>
                    <span class="badge badge-light-success">On Time</span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold fs-5 text-body-emphasis mb-0">07:42:15</div>
                        <div class="small text-body-secondary">Logged today</div>
                    </div>
                    <button class="btn btn-light-danger btn-sm"><i class="fa-solid fa-right-from-bracket me-1"></i>Clock Out</button>
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
