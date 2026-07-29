@extends('layouts.app')

@section('title', 'Payroll & Compensation Report')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Payroll & Compensation Disbursements Report</h1>
            <p class="text-muted fs-7 mb-0">Monthly salary disbursement records, net pay totals, and payment status history.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-chart-line me-1"></i> Executive Hub
            </a>
        </div>
    </div>

    <!-- Payroll Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Payment Month</th>
                            <th>Basic Salary</th>
                            <th>Net Disbursed Salary</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $pm)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900">{{ $pm->employee ? ($pm->employee->first_name . ' ' . $pm->employee->last_name) : 'Staff' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-8">{{ $pm->payment_date ?? 'Monthly' }}</span>
                                </td>
                                <td>
                                    <span class="font-monospace text-gray-800">₹{{ number_format((float)($pm->basic_salary ?? 0), 2) }}</span>
                                </td>
                                <td>
                                    <span class="font-monospace text-success fw-bold">₹{{ number_format((float)($pm->net_salary ?? 0), 2) }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $pm->payment_date ?? '--' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-success">Paid</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No payroll disbursement records found.</td>
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
@endsection
