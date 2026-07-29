@extends('layouts.app')

@section('title', 'Employee Demographics Report')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Employee Demographics & Headcount Report</h1>
            <p class="text-muted fs-7 mb-0">Detailed breakdown of workforce headcount, department assignments, and employment status.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-chart-line me-1"></i> Executive Hub
            </a>
        </div>
    </div>

    <!-- Employee Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('reports.employees') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-5">
                    <select name="department_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive / Exited</option>
                    </select>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('reports.employees') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Employee ID & Name</th>
                            <th>Email Address</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                    <span class="fs-9 text-muted font-monospace">{{ $emp->employee_id ?? 'EMP-000' }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $emp->email }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-8">{{ $emp->department->department_name ?? 'General' }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $emp->designation->designation_name ?? 'Staff' }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $emp->date_of_joining ?? '--' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $emp->is_active == 1 ? 'badge-light-success' : 'badge-light-danger' }}">
                                        {{ $emp->is_active == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No employee report data matching criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($employees->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $employees->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
