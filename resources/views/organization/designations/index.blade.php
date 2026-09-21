@extends('layouts.app')

@section('title', 'Designations Management')
@section('page_title', 'Designations & Titles Directory')

@section('content')
<!-- Page Header Banner -->
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Job Designations</h2>
        <p class="text-body-secondary small mb-0">Define job titles, roles, and functional hierarchy across departments.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('createDesignationModal').showModal()">
            <i class="fa-solid fa-plus me-1"></i>Add Designation
        </button>
    </div>
</div>

<!-- Filter Toolbar Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('designations.index') }}" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-body-secondary"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by designation title..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-5">
                <select name="department_id" class="form-select select-search">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name_with_company }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-light-primary btn-sm flex-grow-1"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                <a href="{{ route('designations.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left me-1"></i>Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Designations Data Table -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Designations List</h3>
        <span class="badge badge-light-primary">{{ $designations->total() }} Total Designations</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 110px;">Actions</th>
                        <th>Designation Title</th>
                        <th>Department</th>
                        <th>Company Entity</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $desig)
                        <tr>
                            <td class="ps-4">
                                <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="Edit Designation" onclick="document.getElementById('editDesignationModal{{ $desig->id }}').showModal()">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form method="POST" action="{{ route('designations.destroy', $desig->id) }}" class="d-inline" onsubmit="return confirm('Delete this designation?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Designation">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Designation Modal (Rule 11) -->
                                <x-form-modal id="editDesignationModal{{ $desig->id }}" title="Edit Designation: {{ $desig->designation_name }}" :action="route('designations.update', $desig->id)" method="PUT" submitText="Save Changes" submitVariant="primary">
                                    <div class="mb-3">
                                        <label class="form-label fs-8 fw-semibold">Designation Title <span class="text-danger">*</span></label>
                                        <input type="text" name="designation_name" class="form-control form-control-sm @error('designation_name') is-invalid @enderror" required value="{{ old('designation_name', $desig->designation_name) }}">
                                        @error('designation_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fs-8 fw-semibold">Department</label>
                                        <select name="department_id" class="form-select form-select-sm select-search">
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}" {{ old('department_id', $desig->department_id) == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name_with_company }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fs-8 fw-semibold">Company Entity</label>
                                        <select name="company_id" class="form-select form-select-sm select-search">
                                            @foreach($companies as $comp)
                                                <option value="{{ $comp->id }}" {{ old('company_id', $desig->company_id) == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fs-8 fw-semibold">Status</label>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="1" {{ old('status', $desig->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $desig->status ?? 1) == 0 ? 'selected' : '' }}>Disabled</option>
                                        </select>
                                    </div>
                                </x-form-modal>
                            </td>
                            <td>
                                <div class="fw-bold text-body-emphasis">{{ $desig->designation_name }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light-info">{{ $desig->department->department_name ?? $desig->department->name ?? 'General' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-primary">{{ $desig->company->name ?? 'Antigravity Corp' }}</span>
                            </td>
                            <td class="pe-4">
                                @if($desig->status ?? true)
                                    <span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                                @else
                                    <span class="badge badge-light-secondary">Disabled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-body-secondary">No designations created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($designations->hasPages())
        <div class="card-footer py-3">
            {{ $designations->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal: Create Designation (Rule 9, 10 & 11) -->
<x-form-modal id="createDesignationModal" title="Create New Designation" :action="route('designations.store')" submitText="Create Designation" submitVariant="primary">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Designation Title <span class="text-danger">*</span></label>
        <input type="text" name="designation_name" class="form-control form-control-sm @error('designation_name') is-invalid @enderror" required placeholder="e.g. Senior Software Engineer" value="{{ old('designation_name') }}">
        @error('designation_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Department</label>
        <select name="department_id" class="form-select form-select-sm select-search">
            <option value="">Select Department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name_with_company }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Company Entity</label>
        <select name="company_id" class="form-select form-select-sm select-search">
            @foreach($companies as $comp)
                <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
            @endforeach
        </select>
    </div>
</x-form-modal>
@endsection
