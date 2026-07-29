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
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createDesignationModal">
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
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->department_name ?? $dept->name }}
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
                        <th>Designation Title</th>
                        <th>Department</th>
                        <th>Company Entity</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $desig)
                        <tr>
                            <td>
                                <div class="fw-bold text-body-emphasis">{{ $desig->designation_name }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light-info">{{ $desig->department->department_name ?? $desig->department->name ?? 'General' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-primary">{{ $desig->company->name ?? 'Antigravity Corp' }}</span>
                            </td>
                            <td>
                                @if($desig->status ?? true)
                                    <span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                                @else
                                    <span class="badge badge-light-secondary">Disabled</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('designations.destroy', $desig->id) }}" class="d-inline" onsubmit="return confirm('Delete this designation?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light-danger btn-sm" title="Delete Designation"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No designations created yet.</td></tr>
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

<!-- Modal: Create Designation -->
<div class="modal fade" id="createDesignationModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('designations.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Create New Designation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="label-sm mb-1">Designation Title *</label>
                        <input type="text" name="designation_name" class="form-control" required placeholder="e.g. Senior Software Engineer">
                    </div>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Department</label>
                        <select name="department_id" class="form-select">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->department_name ?? $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Company Entity</label>
                        <select name="company_id" class="form-select">
                            @foreach($companies as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Designation</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
