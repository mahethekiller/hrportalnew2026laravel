<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip #{{ $payment->make_payment_id }} - {{ $payment->employee->first_name ?? 'Employee' }}</title>
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .payslip-card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 850px;">
        
        <!-- Print Header Action Bar -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('payroll.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Payroll Directory
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold">
                <i class="fa-solid fa-print me-1"></i> Print Payslip
            </button>
        </div>

        <!-- Executive Payslip Card -->
        <div class="card border-0 shadow-sm payslip-card bg-white p-4">
            <div class="card-body p-4">
                
                <!-- Company & Document Header -->
                <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1"><i class="fa-solid fa-building me-2"></i>{{ $payment->company->name ?? 'i2u2 Systems' }}</h2>
                        <p class="text-muted fs-7 mb-0">Human Resources & Payroll Department</p>
                    </div>
                    <div class="text-end">
                        <h4 class="fw-bold text-gray-900 text-uppercase mb-1">Salary Payslip</h4>
                        <span class="badge bg-light-primary text-primary font-monospace fs-8">Ref #PAY-{{ sprintf('%06d', $payment->make_payment_id) }}</span>
                    </div>
                </div>

                <!-- Employee & Payment Info Grid -->
                <div class="row g-3 mb-4 p-3 bg-light rounded">
                    <div class="col-6 col-md-3">
                        <span class="fs-9 text-muted text-uppercase fw-semibold d-block">Employee Name</span>
                        <span class="fw-bold text-gray-900 fs-7">{{ $payment->employee->first_name ?? 'N/A' }} {{ $payment->employee->last_name ?? '' }}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="fs-9 text-muted text-uppercase fw-semibold d-block">Employee ID</span>
                        <span class="fw-bold text-gray-800 fs-7">{{ $payment->employee->employee_id ?? $payment->employee_id }}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="fs-9 text-muted text-uppercase fw-semibold d-block">Department</span>
                        <span class="fw-medium text-gray-800 fs-7">{{ $payment->department->department_name ?? 'General' }}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="fs-9 text-muted text-uppercase fw-semibold d-block">Payment Date</span>
                        <span class="fw-medium text-gray-800 fs-7">{{ $payment->formatted_payment_date }}</span>
                    </div>
                </div>

                <!-- Earnings vs Deductions Breakdown Table -->
                <div class="row g-4 mb-4">
                    <!-- Earnings Column -->
                    <div class="col-md-6">
                        <div class="card border border-success border-opacity-25 h-100">
                            <div class="card-header bg-light-success border-0 py-2">
                                <h6 class="card-title fw-bold text-success mb-0 fs-8 text-uppercase"><i class="fa-solid fa-plus-circle me-1"></i> Earnings & Allowances</h6>
                            </div>
                            <div class="card-body p-3">
                                <table class="table table-sm table-borderless mb-0 fs-7">
                                    <tbody>
                                        <tr>
                                            <td class="text-gray-700">Basic Salary</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->basic_salary, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-700">House Rent Allowance (HRA)</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->house_rent_allowance, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-700">Medical Allowance</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->medical_allowance, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-700">Travelling Allowance (TA)</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->travelling_allowance, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-700">Dearness Allowance (DA)</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->dearness_allowance, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr class="fw-bold">
                                            <td class="text-gray-900 pt-2">Gross Earnings</td>
                                            <td class="text-end font-monospace text-success pt-2">${{ number_format((float)$payment->gross_salary, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions Column -->
                    <div class="col-md-6">
                        <div class="card border border-danger border-opacity-25 h-100">
                            <div class="card-header bg-light-danger border-0 py-2">
                                <h6 class="card-title fw-bold text-danger mb-0 fs-8 text-uppercase"><i class="fa-solid fa-minus-circle me-1"></i> Deductions</h6>
                            </div>
                            <div class="card-body p-3">
                                <table class="table table-sm table-borderless mb-0 fs-7">
                                    <tbody>
                                        <tr>
                                            <td class="text-gray-700">Provident Fund (PF)</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->provident_fund, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-700">Tax Deduction (TDS)</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->tax_deduction, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-700">Security Deposit</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->security_deposit, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-700">Advance Salary Deduct</td>
                                            <td class="text-end font-monospace text-gray-900">${{ number_format((float)$payment->advance_salary_amount, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr class="fw-bold">
                                            <td class="text-gray-900 pt-2">Total Deductions</td>
                                            <td class="text-end font-monospace text-danger pt-2">-${{ number_format((float)$payment->total_deductions, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Net Salary Summary Banner Card -->
                <div class="card bg-primary text-white border-0 shadow-sm p-3 mb-4">
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase fs-9 opacity-75 fw-semibold d-block">Total Net Payable Salary</span>
                            <h3 class="mb-0 text-white fw-bold font-monospace">${{ number_format((float)$payment->net_salary, 2) }}</h3>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-primary text-uppercase px-3 py-2 fw-bold fs-8">{{ $payment->payment_method ?? 'Direct Deposit' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Authorization Signatures -->
                <div class="row pt-5 mt-4 text-center">
                    <div class="col-6">
                        <div class="border-bottom mx-auto w-75 mb-2"></div>
                        <span class="fs-8 text-muted fw-semibold">Employee Signature</span>
                    </div>
                    <div class="col-6">
                        <div class="border-bottom mx-auto w-75 mb-2"></div>
                        <span class="fs-8 text-muted fw-semibold">Authorized HR Manager</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
