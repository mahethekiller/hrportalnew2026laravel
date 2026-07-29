@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-car me-2 text-primary"></i> Conveyance & Travel Claims</h4>
        <p class="text-muted fs-8 mb-0">Submit travel expenses, client visit conveyance, and track reimbursement status.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#conveyanceModal">
            <i class="fa-solid fa-plus me-1"></i> Submit Travel Claim
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header border-0 pt-3 bg-white">
        <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-receipt me-2 text-primary"></i> Reimbursement Claim History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Travel Purpose / Place</th>
                        <th>Claim Type</th>
                        <th>Dates</th>
                        <th>Claim Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $clm)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">{{ $clm->visit_place }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $clm->travel_type }}</span></td>
                            <td>{{ $clm->start_date }} to {{ $clm->end_date }}</td>
                            <td class="fw-bold text-success">${{ number_format((float) $clm->expected_budget, 2) }}</td>
                            <td><span class="badge bg-soft-warning text-warning">{{ $clm->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No conveyance claims submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="conveyanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('my-portal.conveyance.store') }}">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-gray-900">Submit Travel / Conveyance Claim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Destination / Visit Purpose <span class="text-danger">*</span></label>
                        <input type="text" name="visit_place" class="form-control form-control-sm" required placeholder="e.g. Client Onsite Visit - Downtown Office">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Claim Type <span class="text-danger">*</span></label>
                        <select name="travel_type" class="form-select form-select-sm" required>
                            <option value="Local Conveyance">Local Conveyance (Cab / Fuel)</option>
                            <option value="Outstation Business Travel">Outstation Business Travel</option>
                            <option value="Client Expense">Client Meal / Entertainment</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Claim Amount ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="expected_budget" class="form-control form-control-sm" required placeholder="150.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Description & Receipts Summary <span class="text-danger">*</span></label>
                        <textarea name="description" rows="2" class="form-control form-control-sm" required placeholder="Details of travel, mileage, or Uber/cab receipt numbers..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Submit Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
