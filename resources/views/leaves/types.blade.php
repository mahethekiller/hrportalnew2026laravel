@extends('layouts.app')

@section('title', 'Leave Types & Quotas')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Leave Types & Quotas</h1>
            <p class="text-body-secondary fs-7 mb-0">Configure annual leave policies and days allocation per year.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('leaves.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Leave Applications
            </a>
            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('createLeaveTypeModal').showModal()">
                <i class="fa-solid fa-plus me-1"></i> Create Leave Type
            </button>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3">
            <form method="GET" action="{{ route('leave-types.index') }}" class="row g-2 w-100 align-items-center">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent"><i class="fa-solid fa-magnifying-glass text-body-secondary"></i></span>
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
                    <thead class="table-light text-body-secondary fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4" style="width: 110px;">Actions</th>
                            <th># ID</th>
                            <th>Leave Type Name</th>
                            <th>Annual Quota</th>
                            <th>Status</th>
                            <th class="pe-4">Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveTypes as $type)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <button class="btn btn-sm btn-outline-warning px-2.5 rounded-2" title="Edit Leave Type" onclick="document.getElementById('editLeaveTypeModal{{ $type->leave_type_id }}').showModal()">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('leave-types.destroy', $type->leave_type_id) }}" class="d-inline" onsubmit="return confirm('Delete leave type {{ $type->type_name }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Leave Type">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal (Rule 11) -->
                                    <x-form-modal id="editLeaveTypeModal{{ $type->leave_type_id }}" title="Edit Leave Type: {{ $type->type_name }}" :action="route('leave-types.update', $type->leave_type_id)" method="PUT" submitText="Update Leave Type" submitVariant="warning">
                                        <div class="mb-3">
                                            <label class="form-label fs-8 fw-semibold">Leave Type Name <span class="text-danger">*</span></label>
                                            <input type="text" name="type_name" class="form-control form-control-sm @error('type_name') is-invalid @enderror" required value="{{ old('type_name', $type->type_name) }}">
                                            @error('type_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fs-8 fw-semibold">Days Per Year <span class="text-danger">*</span></label>
                                            <input type="number" name="days_per_year" class="form-control form-control-sm @error('days_per_year') is-invalid @enderror" required min="1" max="365" value="{{ old('days_per_year', $type->days_per_year) }}">
                                            @error('days_per_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fs-8 fw-semibold">Status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="1" {{ old('status', (int)$type->status) === 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('status', (int)$type->status) === 0 ? 'selected' : '' }}>Disabled</option>
                                            </select>
                                        </div>
                                    </x-form-modal>
                                </td>
                                <td class="fw-bold text-body-secondary">#{{ $type->leave_type_id }}</td>
                                <td class="fw-bold text-body-emphasis">{{ $type->type_name }}</td>
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
                                <td class="pe-4">
                                    <span class="text-body-secondary fs-8"><x-human-date :value="$type->created_at" /></span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    <i class="fa-solid fa-folder-open fs-2 mb-2 d-block text-body-secondary"></i>
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

<!-- Modal: Create Leave Type (Rule 10 & 11) -->
<x-form-modal id="createLeaveTypeModal" title="Create Leave Type" :action="route('leave-types.store')" submitText="Save Leave Type" submitVariant="primary">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Leave Type Name <span class="text-danger">*</span></label>
        <input type="text" name="type_name" class="form-control form-control-sm @error('type_name') is-invalid @enderror" required placeholder="e.g. Annual Leave, Sick Leave, Earned Leave" value="{{ old('type_name') }}">
        @error('type_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Days Per Year <span class="text-danger">*</span></label>
        <input type="number" name="days_per_year" class="form-control form-control-sm @error('days_per_year') is-invalid @enderror" required min="1" max="365" value="{{ old('days_per_year', 12) }}">
        @error('days_per_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</x-form-modal>
@endsection
