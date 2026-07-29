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
                        <th class="ps-4">Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">{{ $leave->leave_type_id }}</td>
                            <td>{{ $leave->from_date }}</td>
                            <td>{{ $leave->to_date }}</td>
                            <td>{{ Str::limit($leave->reason, 50) }}</td>
                            <td>
                                @if($leave->status == 2)
                                    <span class="badge bg-soft-success text-success">Approved</span>
                                @elseif($leave->status == 3)
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
@endsection
