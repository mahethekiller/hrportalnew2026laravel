@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-user-check me-2 text-primary"></i> Profile Update Approvals</h4>
        <p class="text-muted fs-8 mb-0">Manage pending profile and demographic data changes submitted by employees.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3 bg-white">
    <div class="card-header border-0 bg-transparent pt-4">
        <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Pending Verification Queue</h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Proposed Email</th>
                        <th>Proposed Mobile</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($updates as $update)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light-primary text-primary p-2 rounded-circle fw-bold fs-9" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        {{ strtoupper(substr($update->first_name ?? $update->user->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($update->last_name ?? $update->user->last_name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-gray-900">{{ $update->first_name ?? $update->user->first_name }} {{ $update->last_name ?? $update->user->last_name }}</span>
                                        <span class="fs-9 text-muted">Employee ID: {{ $update->user->employee_id ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $update->email_personal ?? 'N/A' }}</td>
                            <td>{{ $update->contact_no ?? 'N/A' }}</td>
                            <td>{{ $update->added_date }}</td>
                            <td><span class="badge bg-soft-warning text-warning">Pending Review</span></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('manager-portal.profile_approvals.show', $update->id) }}" class="btn btn-light-primary btn-sm fw-bold fs-9">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i> Review Changes
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-circle-info fs-3 mb-2 text-secondary"></i>
                                <p class="mb-0 fs-8">No pending profile update requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($updates->hasPages())
        <div class="card-footer border-0 bg-transparent py-3 border-top">
            {{ $updates->links() }}
        </div>
    @endif
</div>
@endsection
