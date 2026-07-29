@extends('layouts.app')

@section('title', 'Departments Management')
@section('page_title', 'Department Directory')

@section('content')
<!-- Page Header Banner -->
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Departments</h2>
        <p class="text-body-secondary small mb-0">Organize company teams, department heads, and functional divisions.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
            <i class="fa-solid fa-plus me-1"></i>Add Department
        </button>
    </div>
</div>

<!-- Filter Toolbar Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('departments.index') }}" class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-body-secondary"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by department name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="company_id" class="form-select">
                    <option value="">All Companies</option>
                    @foreach($companies as $comp)
                        <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-light-primary btn-sm flex-grow-1"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left me-1"></i>Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Departments Data Table -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Department Directory</h3>
        <span class="badge badge-light-primary">{{ $departments->total() }} Departments</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Company Entity</th>
                        <th>Department Head / Lead</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>
                                <div class="fw-bold text-body-emphasis">{{ $dept->department_name }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light-primary">{{ $dept->company->name ?? 'Antigravity Corp' }}</span>
                            </td>
                            <td>
                                @if($dept->employee)
                                    <span class="fw-medium text-body-emphasis"><i class="fa-solid fa-user-tie text-primary me-1"></i>{{ $dept->employee->first_name }} {{ $dept->employee->last_name }}</span>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @if($dept->status ?? true)
                                    <span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                                @else
                                    <span class="badge badge-light-secondary">Disabled</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('departments.destroy', $dept->id) }}" class="d-inline" onsubmit="return confirm('Delete this department?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light-danger btn-sm" title="Delete Department"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No departments created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($departments->hasPages())
        <div class="card-footer py-3">
            {{ $departments->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal: Create Department -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Create New Department</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="label-sm mb-1">Department Name *</label>
                        <input type="text" name="department_name" class="form-control" required placeholder="e.g. Human Resources / Engineering">
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
                    <button type="submit" class="btn btn-primary">Create Department</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
