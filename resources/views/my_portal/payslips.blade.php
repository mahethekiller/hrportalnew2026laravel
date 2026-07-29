@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-wallet me-2 text-primary"></i> My Payslips</h4>
        <p class="text-muted fs-8 mb-0">View monthly compensation registers and download PDF payslips.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Pay Period</th>
                        <th>Net Salary</th>
                        <th>Payment Status</th>
                        <th class="text-end pe-4">Payslip</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payslips as $pay)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">{{ $pay->payment_date }}</td>
                            <td class="fw-bold text-success">${{ number_format((float) $pay->net_salary, 2) }}</td>
                            <td><span class="badge bg-soft-success text-success">Paid</span></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('payroll.payslip', $pay->make_payment_id) }}" class="btn btn-light-primary btn-sm py-1 px-3 fs-9 fw-bold">
                                    <i class="fa-solid fa-download me-1"></i> PDF Payslip
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No payslip records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
