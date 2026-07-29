@extends('layouts.app')

@section('title', 'Leave Types & Quotas')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Leave Types & Quotas</h1>
            <p class="text-muted fs-7 mb-0">Configure annual leave policies and days allocation per year.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('leaves.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Leave Applications
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createLeaveTypeModal">
                <i class="fa-solid fa-plus me-1"></i> Create Leave Type
            </button>
        </div>
    </div>

    <!-- Alert Messages -->

    <!-- Main Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3">
            <form method="GET" action="{{ route('leave-types.index') }}" class="row g-2 w-100 align-items-center">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search leave type name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('leave-types.index') }}" class="btn btn-light-secondary btn-sm"><i class="fa-solid fa-arrows-rotate me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4"># ID</th>
                            <th>Leave Type Name</th>
                            <th>Annual Quota</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveTypes as $type)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#{{ $type->leave_type_id }}</td>
                                <td class="fw-bold text-gray-900">{{ $type->type_name }}</td>
                                <td>
                                    <span class="badge badge-light-primary fw-bold">{{ $type->days_per_year }} days / year</span>
                                </td>
                                <td>
                                    @if((int)$type->status === 1)
                                        <span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                                    @else
                                        <span class="badge badge-light-secondary">Disabled</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted fs-8">{{ $type->created_at ? date('M d, Y', strtotime($type->created_at)) : 'N/A' }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-light-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editLeaveTypeModal{{ $type->leave_type_id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form method="POST" action="{{ route('leave-types.destroy', $type->leave_type_id) }}" class="d-inline" onsubmit="return confirm('Delete leave type {{ $type->type_name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-light-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editLeaveTypeModal{{ $type->leave_type_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <form method="POST" action="{{ route('leave-types.update', $type->leave_type_id) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Edit Leave Type</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Leave Type Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="type_name" class="form-control form-control-sm" required value="{{ $type->type_name }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Days Per Year <span class="text-danger">*</span></label>
                                                            <input type="number" name="days_per_year" class="form-control form-control-sm" required min="1" max="365" value="{{ $type->days_per_year }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Status</label>
                                                            <select name="status" class="form-select form-select-sm">
                                                                <option value="1" {{ (int)$type->status === 1 ? 'selected' : '' }}>Active</option>
                                                                <option value="0" {{ (int)$type->status === 0 ? 'selected' : '' }}>Disabled</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning btn-sm">Update Leave Type</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fs-2 mb-2 d-block text-muted"></i>
                                    No leave types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($leaveTypes->hasPages())
            <div class="card-footer py-3">
                {{ $leaveTypes->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Create Leave Type -->
<div class="modal fade" id="createLeaveTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('leave-types.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Create Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Leave Type Name <span class="text-danger">*</span></label>
                        <input type="text" name="type_name" class="form-control form-control-sm" required placeholder="e.g. Annual Leave, Sick Leave, Earned Leave">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Days Per Year <span class="text-danger">*</span></label>
                        <input type="number" name="days_per_year" class="form-control form-control-sm" required min="1" max="365" value="12">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Leave Type</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
