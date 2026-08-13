@extends('layouts.app')

@section('title', 'Employees Directory')
@section('page_title', 'Employee Directory Management')

@section('content')
<!-- Page Header Banner -->
<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Employee Directory</h2>
        <p class="text-body-secondary small mb-0">Manage enterprise employee records, credentials, and organizational assignments.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-user-plus me-1"></i>Add New Employee
        </a>
    </div>
</div>

<!-- Filter Toolbar Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-body-secondary"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, Employee ID, email, or username..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->department_name ?? $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>1: Active</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>2: Terminated</option>
                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>3: Left</option>
                    <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>4: Abscond</option>
                    <option value="5" {{ request('status') === '5' ? 'selected' : '' }}>5: Disable</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>0: Resigned</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-light-primary btn-sm flex-grow-1"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left me-1"></i>Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Employee Data Table Card -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Employee List Directory</h3>
        <span class="badge badge-light-primary">{{ $employees->total() }} Total Records</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Full Name</th>
                        <th>Department & Designation</th>
                        <th>Contact Email</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr>
                            <td>
                                <span class="fw-bold text-primary">{{ (!empty($emp->employee_id) && $emp->employee_id !== '0') ? $emp->employee_id : 'EMP-' . sprintf('%04d', $emp->id) }}</span>
                                <div class="small text-body-secondary"><i class="fa-solid fa-id-card me-1"></i>Card: {{ $emp->card_no ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($emp->profile_picture && file_exists(public_path('uploads/profile/' . $emp->profile_picture)))
                                        <img src="{{ asset('uploads/profile/' . $emp->profile_picture) }}" alt="{{ $emp->first_name }}" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="btn btn-light-primary btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('employees.show', $emp->id) }}" class="fw-semibold text-body-emphasis text-decoration-none">
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </a>
                                        <div class="small text-body-secondary">{{ $emp->username ? '@' . $emp->username : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-body-emphasis">{{ $emp->department->department_name ?? $emp->department->name ?? 'General' }}</div>
                                <div class="small text-body-secondary">{{ $emp->designation->designation_name ?? $emp->designation->name ?? 'Staff Member' }}</div>
                            </td>
                            <td>
                                <span class="text-body-secondary">{{ $emp->email }}</span>
                            </td>
                            <td>
                                @switch((int)$emp->is_active)
                                    @case(1)
                                        <span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                                        @break
                                    @case(2)
                                        <span class="badge badge-light-danger"><i class="fa-solid fa-ban me-1"></i>Terminated</span>
                                        @break
                                    @case(3)
                                        <span class="badge badge-light-warning"><i class="fa-solid fa-person-walking-arrow-right me-1"></i>Left</span>
                                        @break
                                    @case(4)
                                        <span class="badge bg-dark text-white"><i class="fa-solid fa-user-ninja me-1"></i>Abscond</span>
                                        @break
                                    @case(5)
                                        <span class="badge badge-light-secondary"><i class="fa-solid fa-user-slash me-1"></i>Disable</span>
                                        @break
                                    @case(0)
                                        <span class="badge badge-light-info"><i class="fa-solid fa-file-signature me-1"></i>Resigned</span>
                                        @break
                                    @default
                                        <span class="badge badge-light-secondary">Unknown</span>
                                @endswitch
                            </td>
                            <td class="text-end">
                                <button class="btn btn-light-info btn-sm me-1" onclick="navigator.clipboard.writeText('{{ route('onboarding', md5((string)$emp->user_id)) }}'); toastr.success('Onboarding link copied to clipboard!');" title="Copy Onboarding Link">
                                    <i class="fa-solid fa-link"></i>
                                </button>
                                <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-light-primary btn-sm me-1" title="View Profile">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-light-warning btn-sm me-1" title="Edit Record">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('employees.destroy', $emp->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light-danger btn-sm" title="Delete Record">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-body-secondary">
                                <i class="fa-solid fa-user-slash fs-2 mb-2 d-block text-muted"></i>
                                No employee records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($employees->hasPages())
        <div class="card-footer bg-transparent border-top p-3">
            {{ $employees->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
