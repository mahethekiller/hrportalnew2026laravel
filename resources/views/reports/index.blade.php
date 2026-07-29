@extends('layouts.app')

@section('title', 'Executive HR Reporting Hub')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Executive HR Reporting Hub</h1>
            <p class="text-muted fs-7 mb-0">High-level portal analytics, organizational metrics, payroll disbursements, and audit logs.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.employees') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-users me-1"></i> Employee Headcount Report
            </a>
            <a href="{{ route('reports.payroll') }}" class="btn btn-light-success btn-sm">
                <i class="fa-solid fa-file-invoice-dollar me-1"></i> Payroll Disbursements
            </a>
            <a href="{{ route('reports.audit_logs') }}" class="btn btn-light-warning btn-sm">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Audit Trail Logs
            </a>
        </div>
    </div>

    <!-- Executive KPI Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-primary text-primary me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-user-group fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Headcount</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $overview['total_employees'] ?? 0 }} Staff</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-success text-success me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-wallet fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Payroll Disbursed</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $overview['total_payroll'] ?? '₹0.00' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-warning text-warning me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-calendar-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Approved Leave Days</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $overview['approved_leaves'] ?? 0 }} Approved</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-info text-info me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-laptop-code fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Allocated Assets</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $overview['total_assets'] ?? 0 }} Devices</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Headcount Breakdown Table Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 pt-4 bg-light bg-opacity-50">
            <h3 class="card-title fw-bold text-gray-900 fs-6">
                <i class="fa-solid fa-sitemap text-primary me-2"></i> Department Headcount Distribution Breakdown
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Department Name</th>
                            <th>Active Employee Count</th>
                            <th>Status Bar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overview['department_breakdown'] as $dept)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900"><i class="fa-solid fa-building me-2 text-primary"></i>{{ $dept->department_name }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-8 fw-bold">{{ $dept->employees_count ?? 0 }} Employees</span>
                                </td>
                                <td style="width:300px;">
                                    <div class="progress" style="height: 8px;">
                                        @php
                                            $totalEmp = max(1, $overview['total_employees']);
                                            $pct = round((($dept->employees_count ?? 0) / $totalEmp) * 100);
                                        @endphp
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fs-9 text-muted">{{ $pct }}% of total workforce</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No department statistics available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
