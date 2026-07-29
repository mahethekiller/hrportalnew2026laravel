@extends('layouts.app')

@section('title', 'Salary Increment History')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Salary Increment & Revision History</h1>
            <p class="text-muted fs-7 mb-0">Track employee salary appraisals and compensation revision logs.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('payroll.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Payroll Directory
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#recordIncrementModal">
                <i class="fa-solid fa-plus me-1"></i> Record Salary Increment
            </button>
        </div>
    </div>

    <!-- Salary History Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('salary-history.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search employee name or ID..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('salary-history.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Old Salary</th>
                            <th>New Salary</th>
                            <th>Increment ($)</th>
                            <th>Appraisal Date</th>
                            <th>Recorded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryHistories as $sh)
                            @php
                                $diff = (float)$sh->new_salary - (float)$sh->old_salary;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width:35px; height:35px;">
                                            {{ substr($sh->employee->first_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-900">{{ $sh->employee->first_name ?? 'Staff' }} {{ $sh->employee->last_name ?? '' }}</div>
                                            <div class="fs-9 text-muted">ID: {{ $sh->employee->employee_id ?? $sh->employee_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="font-monospace text-muted">${{ number_format((float)$sh->old_salary, 2) }}</span></td>
                                <td><span class="fw-bold font-monospace text-gray-900">${{ number_format((float)$sh->new_salary, 2) }}</span></td>
                                <td>
                                    <span class="badge {{ $diff >= 0 ? 'badge-light-success' : 'badge-light-danger' }} font-monospace fs-8">
                                        {{ $diff >= 0 ? '+' : '' }}${{ number_format($diff, 2) }}
                                    </span>
                                </td>
                                <td><span class="fw-medium text-gray-800">{{ $sh->formatted_appraisal_date }}</span></td>
                                <td><span class="text-gray-700 fs-8">{{ $sh->added_by ?? 'System Admin' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-chart-line fs-2 mb-2 d-block text-muted"></i>
                                    No salary increment records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($salaryHistories->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $salaryHistories->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Record Salary Increment -->
<div class="modal fade" id="recordIncrementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('salary-history.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Record Salary Increment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Select Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select form-select-sm" required>
                            <option value="">Choose Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->user_id }}">
                                    {{ $emp->first_name }} {{ $emp->last_name }} (ID: {{ $emp->employee_id ?? $emp->user_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Old Salary ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="old_salary" class="form-control form-control-sm" required placeholder="5000.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">New Salary ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="new_salary" class="form-control form-control-sm" required placeholder="6000.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Appraisal Date <span class="text-danger">*</span></label>
                        <input type="date" name="appraisal_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Record Increment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
