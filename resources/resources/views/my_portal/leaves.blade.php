@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-calendar-check me-2 text-primary"></i> My Leave Applications</h4>
        <p class="text-muted fs-8 mb-0">Track your personal leave balances and submit time-off requests.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#leaveModal">
            <i class="fa-solid fa-plus me-1"></i> Apply for Time Off
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Leave ID</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">#{{ $leave->leave_id ?? $leave->id }}</td>
                            <td>{{ $leave->from_date }}</td>
                            <td>{{ $leave->to_date }}</td>
                            <td>{{ Str::limit($leave->reason, 50) }}</td>
                            <td>
                                @if($leave->status == 2 || $leave->status === 'Approved')
                                    <span class="badge bg-soft-success text-success">Approved</span>
                                @elseif($leave->status == 3 || $leave->status === 'Rejected')
                                    <span class="badge bg-soft-danger text-danger">Rejected</span>
                                @else
                                    <span class="badge bg-soft-warning text-warning">Pending Approval</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No leave records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Apply Leave Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-900"><i class="fa-solid fa-plane-departure text-primary me-2"></i> Apply for Time Off</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('my-portal.leaves.store') }}" method="POST">
                @csrf
                <div class="modal-body py-3 fs-8">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-gray-700">Leave Category</label>
                        <select name="leave_type_id" class="form-select fs-8" required>
                            <option value="1">Casual Leave</option>
                            <option value="2">Medical / Sick Leave</option>
                            <option value="3">Earned Leave</option>
                            <option value="4">Unpaid Leave</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-gray-700">From Date</label>
                            <input type="date" name="from_date" class="form-control fs-8" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-gray-700">To Date</label>
                            <input type="date" name="to_date" class="form-control fs-8" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-gray-700">Reason for Leave</label>
                        <textarea name="reason" class="form-control fs-8" rows="3" placeholder="Describe reason..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
