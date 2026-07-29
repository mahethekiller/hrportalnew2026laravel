@extends('layouts.app')

@section('title', 'System Audit Trail Logs')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">System Audit Trail Logs</h1>
            <p class="text-muted fs-7 mb-0">Inspect historical employee profile updates, user activity logs, and system audit events.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-chart-line me-1"></i> Executive Hub
            </a>
        </div>
    </div>

    <!-- Audit Logs Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('reports.audit_logs') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-9">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search audit logs by employee name, email, or action detail..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('reports.audit_logs') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Target Employee</th>
                            <th>Email Address</th>
                            <th>Department</th>
                            <th>Audit Action / Update Details</th>
                            <th>Log Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $lg)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900"><i class="fa-solid fa-user-clock me-2 text-primary"></i>{{ $lg->full_name }}</div>
                                    <span class="fs-9 text-muted font-monospace">{{ $lg->employee_id ?? 'EMP-000' }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $lg->email }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-8">{{ $lg->department->department_name ?? 'General' }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-900">{{ $lg->updates ?? 'Employee profile modification record' }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $lg->updated_date ?? $lg->created_at ?? '--' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-clock-rotate-left fs-2 mb-2 d-block text-muted"></i>
                                    No audit log entries matching criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
