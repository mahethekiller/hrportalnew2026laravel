@extends('layouts.app')

@section('title', 'Assets & Inventory Management')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Assets & Inventory Management</h1>
            <p class="text-muted fs-7 mb-0">Track company hardware, software licenses, serial numbers, and employee allocations.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAssetModal">
                <i class="fa-solid fa-plus me-1"></i> Register New Asset
            </button>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-primary text-primary me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-laptop fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Assets</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['total_assets'] ?? 0 }} Items</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-success text-success me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-user-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Allocated Staff</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['allocated_count'] ?? 0 }} Assets</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-info text-info me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-boxes-stacked fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">In Stock (Unassigned)</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['in_stock_count'] ?? 0 }} Items</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-warning text-warning me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-wrench fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Under Maintenance</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['maintenance_count'] ?? 0 }} Items</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Assets Directory Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('assets.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search asset tag, name, serial no, or staff..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Working / In Use</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('assets.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Asset Code / Tag</th>
                            <th>Item Name & Make</th>
                            <th>Serial Number</th>
                            <th>Allocated Employee</th>
                            <th>Warranty Expiry</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $ast)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light-primary text-primary font-monospace fs-8">{{ $ast->company_asset_code ?? 'AST-000' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-gray-900">{{ $ast->name }}</div>
                                    <div class="fs-9 text-muted">{{ $ast->manufacturer ?? 'Generic' }}</div>
                                </td>
                                <td>
                                    <span class="font-monospace text-gray-800">{{ $ast->serial_number ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($ast->employee)
                                        <div class="fw-medium text-gray-900">{{ $ast->employee->first_name }} {{ $ast->employee->last_name }}</div>
                                        <div class="fs-9 text-muted">ID: {{ $ast->employee->employee_id ?? $ast->employee_id }}</div>
                                    @else
                                        <span class="badge badge-light-secondary fs-9"><i class="fa-solid fa-box me-1"></i>In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-medium text-gray-800">{{ $ast->formatted_warranty_date }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $ast->status_badge_class }}">
                                        {{ $ast->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-laptop-code fs-2 mb-2 d-block text-muted"></i>
                                    No company assets found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($assets->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $assets->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Register New Asset -->
<div class="modal fade" id="createAssetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('assets.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-laptop me-2 text-primary"></i> Register New Company Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Asset Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. MacBook Pro 16 M2 / Dell Monitor 27">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Company Asset Code Tag</label>
                            <input type="text" name="company_asset_code" class="form-control form-control-sm" placeholder="e.g. AST-2026-001 (Auto generated if blank)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Manufacturer / Brand</label>
                            <input type="text" name="manufacturer" class="form-control form-control-sm" placeholder="e.g. Apple, Dell, HP">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control form-control-sm" placeholder="e.g. C02FX12345">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control form-control-sm" placeholder="e.g. INV-9921">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Allocate to Employee</label>
                            <select name="employee_id" class="form-select form-select-sm">
                                <option value="">Unassigned (Keep In Stock)</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->user_id }}">
                                        {{ $emp->first_name }} {{ $emp->last_name }} (ID: {{ $emp->employee_id ?? $emp->user_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Maintenance Status</label>
                            <select name="is_working" class="form-select form-select-sm">
                                <option value="1" selected>Working / In Use</option>
                                <option value="0">Under Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Warranty Expiration Date</label>
                            <input type="date" name="warranty_end_date" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Asset Notes / Specs</label>
                        <textarea name="asset_note" class="form-control form-control-sm" rows="2" placeholder="Optional hardware specifications or warranty notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Register Asset</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
