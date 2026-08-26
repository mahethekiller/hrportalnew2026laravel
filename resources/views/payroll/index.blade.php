@extends('layouts.app')

@section('title', 'Payroll & Compensation Management')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Payroll & Compensation Directory</h1>
            <p class="text-muted fs-7 mb-0">Manage monthly employee salary processing, allowances, deductions, and payslips.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('salary-history.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-chart-line me-1"></i> Salary History
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#processPayrollModal">
                <i class="fa-solid fa-plus me-1"></i> Process Salary Payment
            </button>
        </div>
    </div>

    <!-- Monthly Payroll Summary Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-success text-success me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-money-bill-wave fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Net Paid</span>
                        <div class="fs-4 fw-bold text-gray-900">${{ number_format($summary['total_paid'] ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-info text-info me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-hand-holding-dollar fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Allowances</span>
                        <div class="fs-4 fw-bold text-gray-900">${{ number_format($summary['total_allowances'] ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-danger text-danger me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-scissors fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Deductions</span>
                        <div class="fs-4 fw-bold text-gray-900">${{ number_format($summary['total_deductions'] ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-primary text-primary me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Paid Records</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['paid_count'] ?? 0 }} Records</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Payroll Payments Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('payroll.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search employee name, code, or payment method..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Payment Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Paid</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('payroll.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4" style="min-width: 100px;">Actions</th>
                            <th>Employee</th>
                            <th>Payment Date</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Method</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $pay)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('payroll.payslip', $pay->make_payment_id) }}" class="btn btn-light-primary btn-sm py-1 px-2 fs-8" target="_blank">
                                        <i class="fa-solid fa-file-invoice-dollar me-1"></i> Payslip
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width:35px; height:35px;">
                                            {{ substr($pay->employee->first_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-900">{{ $pay->employee->first_name ?? 'Staff' }} {{ $pay->employee->last_name ?? '' }}</div>
                                            <div class="fs-9 text-muted">ID: {{ $pay->employee->employee_id ?? $pay->employee_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-gray-800">{{ $pay->formatted_payment_date }}</span>
                                </td>
                                <td><span class="font-monospace text-gray-800">${{ number_format((float)$pay->basic_salary, 2) }}</span></td>
                                <td><span class="font-monospace text-success">+${{ number_format((float)$pay->total_allowances, 2) }}</span></td>
                                <td><span class="font-monospace text-danger">-${{ number_format((float)$pay->total_deductions, 2) }}</span></td>
                                <td>
                                    <span class="fw-bold font-monospace text-gray-900 fs-7">${{ number_format((float)$pay->net_salary, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-info text-uppercase fs-9">{{ $pay->payment_method ?? 'Direct Deposit' }}</span>
                                </td>
                                <td class="pe-4">
                                    <span class="badge {{ $pay->status_badge_class }}">
                                        {{ $pay->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-wallet fs-2 mb-2 d-block text-muted"></i>
                                    No payroll payment records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $payments->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Process Salary Payment -->
<div class="modal fade" id="processPayrollModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('payroll.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-money-check-dollar me-2 text-primary"></i> Process Monthly Salary Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <h6 class="fs-8 fw-bold text-uppercase text-gray-700 mb-2 border-bottom pb-1">Earnings & Allowances</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Basic Salary ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="basic_salary" class="form-control form-control-sm" required placeholder="5000.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">House Rent Allowance (HRA)</label>
                            <input type="number" step="0.01" name="house_rent_allowance" class="form-control form-control-sm" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Medical Allowance</label>
                            <input type="number" step="0.01" name="medical_allowance" class="form-control form-control-sm" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-9 fw-semibold text-muted">Travelling Allowance (TA)</label>
                            <input type="number" step="0.01" name="travelling_allowance" class="form-control form-control-sm" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-9 fw-semibold text-muted">Dearness Allowance (DA)</label>
                            <input type="number" step="0.01" name="dearness_allowance" class="form-control form-control-sm" value="0.00">
                        </div>
                    </div>

                    <h6 class="fs-8 fw-bold text-uppercase text-gray-700 mb-2 border-bottom pb-1">Deductions</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-9 fw-semibold text-muted">Provident Fund (PF)</label>
                            <input type="number" step="0.01" name="provident_fund" class="form-control form-control-sm" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-9 fw-semibold text-muted">Tax Deduction (TDS)</label>
                            <input type="number" step="0.01" name="tax_deduction" class="form-control form-control-sm" value="0.00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select form-select-sm" required>
                            <option value="Direct Deposit">Direct Deposit / Bank Transfer</option>
                            <option value="Check">Company Check</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Comments / Notes</label>
                        <textarea name="comments" class="form-control form-control-sm" rows="2" placeholder="Optional payment remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Process Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
