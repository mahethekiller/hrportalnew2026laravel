@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-calendar-check me-2 text-warning"></i> Team Leave Approvals</h4>
        <p class="text-muted fs-8 mb-0">Review, approve, or reject time-off requests submitted by your team members.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Manager Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">
                                {{ $leave->employee ? $leave->employee->first_name . ' ' . $leave->employee->last_name : 'Employee #' . $leave->employee_id }}
                            </td>
                            <td>{{ $leave->from_date }} to {{ $leave->to_date }}</td>
                            <td>{{ Str::limit($leave->reason, 40) }}</td>
                            <td>
                                @if($leave->status == 2)
                                    <span class="badge bg-soft-success text-success">Approved</span>
                                @elseif($leave->status == 3)
                                    <span class="badge bg-soft-danger text-danger">Rejected</span>
                                @else
                                    <span class="badge bg-soft-warning text-warning">Pending Approval</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($leave->status == 1)
                                    <form method="POST" action="{{ route('manager-portal.team_leaves.status', $leave->leave_id) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="2">
                                        <button type="submit" class="btn btn-success btn-sm py-1 px-2 fs-9 me-1 fw-bold">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('manager-portal.team_leaves.status', $leave->leave_id) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="3">
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2 fs-9 fw-bold">Reject</button>
                                    </form>
                                @else
                                    <span class="text-muted fs-9">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No team leave applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
