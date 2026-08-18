@extends('layouts.app')

@section('title', 'Employees Directory')
@section('page_title', 'Employee Directory Management')

@section('content')
<!-- Page Header Banner -->
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1"><i class="fa-solid fa-users-rectangle me-2 text-primary"></i> Employee Directory</h2>
        <p class="text-body-secondary small mb-0">Manage enterprise employee records, credentials, and organizational assignments.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        @can('edit.employees')
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm fw-bold shadow-sm">
                <i class="fa-solid fa-user-plus me-1"></i> Add New Employee
            </a>
        @endcan
    </div>
</div>

<!-- KPI Directory Summary Metrics -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <x-kpi-card title="Active Staff" :value="$totalActive" icon="fa-solid fa-user-check" variant="success" badgeText="Verified Active" />
    </div>
    <div class="col-xl-3 col-sm-6">
        <x-kpi-card title="Departments" :value="$totalDepartments" icon="fa-solid fa-sitemap" variant="primary" badgeText="Org Units" />
    </div>
    <div class="col-xl-3 col-sm-6">
        <x-kpi-card title="Inactive / Resigned" :value="$totalInactive" icon="fa-solid fa-user-clock" variant="warning" badgeText="Historical Records" />
    </div>
    <div class="col-xl-3 col-sm-6">
        <x-kpi-card title="Filtered Directory" :value="$employees->total()" icon="fa-solid fa-address-book" variant="info" badgeText="Current Results" />
    </div>
</div>

<!-- Filter Toolbar Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-3 align-items-center">
            <input type="hidden" name="view" value="{{ request('view', 'table') }}">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-body border-subtle"><i class="fa-solid fa-magnifying-glass text-body-secondary"></i></span>
                    <input type="text" name="search" class="form-control bg-body text-body-emphasis border-subtle" placeholder="Search name, ID, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="company_id" class="form-select bg-body text-body-emphasis border-subtle">
                    <option value="">All Companies</option>
                    @foreach($companies as $comp)
                        <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>
                            {{ $comp->name ?? $comp->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="department_id" class="form-select bg-body text-body-emphasis border-subtle">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->department_name ?? $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select bg-body text-body-emphasis border-subtle">
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
                <button type="submit" class="btn btn-primary-subtle text-primary btn-sm flex-grow-1 fw-bold"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left me-1"></i>Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Header Toolbar & View Toggle Switcher -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center gap-2">
        <h5 class="fw-bold text-body-emphasis mb-0 fs-6">Employee Records</h5>
        <span class="badge bg-primary-subtle text-primary fw-bold fs-9">{{ $employees->total() }} Total</span>
    </div>
    
    <div class="btn-group btn-group-sm" role="group" aria-label="Directory View Selector">
        <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}" class="btn {{ request('view', 'table') === 'table' ? 'btn-primary' : 'btn-outline-secondary' }} fw-bold">
            <i class="fa-solid fa-list me-1"></i> Table View
        </a>
        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="btn {{ request('view') === 'grid' ? 'btn-primary' : 'btn-outline-secondary' }} fw-bold">
            <i class="fa-solid fa-grip me-1"></i> Grid Cards View
        </a>
    </div>
</div>

@php
    // Distinct Palette Mapping for Companies
    $colorPalette = ['info', 'primary', 'success', 'warning', 'danger', 'secondary'];
@endphp

@if(request('view') === 'grid')
    <!-- Grid Cards View Mode -->
    <div class="row g-4 mb-4">
        @forelse($employees as $emp)
            @php
                $companyName = $emp->company->name ?? $emp->company->company_name ?? 'Default Corp';
                $companyId = $emp->company_id ?? 1;
                $compColor = $colorPalette[$companyId % count($colorPalette)];
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary dashboard-card h-100 p-3 d-flex flex-column text-center">
                    <div class="position-absolute top-0 end-0 me-3 mt-3">
                        <x-status-badge :status="$emp->is_active" pulse="true" />
                    </div>
                    
                    <div class="mb-3 mt-2">
                        @if($emp->profile_picture && file_exists(public_path('uploads/profile/' . $emp->profile_picture)))
                            <img src="{{ asset('uploads/profile/' . $emp->profile_picture) }}" alt="{{ $emp->first_name }}" class="rounded-circle border border-2 border-primary shadow-xs" style="width: 72px; height: 72px; object-fit: cover;">
                        @else
                            <div class="avatar-lg rounded-circle bg-primary-subtle text-primary mx-auto d-flex align-items-center justify-content-center fw-bold fs-3 shadow-xs" style="width: 72px; height: 72px;">
                                {{ substr($emp->first_name ?? 'E', 0, 1) }}{{ substr($emp->last_name ?? '', 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h6 class="fw-bold text-body-emphasis mb-0">
                        <a href="{{ route('employees.show', $emp->id) }}" class="text-body-emphasis text-decoration-none">
                            {{ $emp->first_name }} {{ $emp->last_name }}
                        </a>
                    </h6>
                    <span class="fs-9 text-body-secondary mb-2">{{ $emp->designation->designation_name ?? $emp->designation->name ?? 'Staff Member' }}</span>

                    <!-- Company & Department Badges -->
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-1 mb-3">
                        <span class="badge bg-{{ $compColor }}-subtle text-{{ $compColor }} fs-9 fw-bold">
                            <i class="fa-solid fa-building me-1"></i>{{ $companyName }}
                        </span>
                        <span class="badge bg-secondary-subtle text-secondary fs-9 fw-bold">
                            <i class="fa-solid fa-sitemap me-1"></i>{{ $emp->department->department_name ?? $emp->department->name ?? 'General' }}
                        </span>
                    </div>

                    <div class="bg-body border border-subtle rounded-3 p-2 mb-3 fs-9 text-start">
                        <div class="text-truncate text-body-secondary mb-1">
                            <i class="fa-solid fa-envelope me-1 text-primary"></i> <a href="mailto:{{ $emp->email }}" class="text-body-secondary text-decoration-none">{{ $emp->email }}</a>
                        </div>
                        <div class="text-body-secondary">
                            <i class="fa-solid fa-id-card me-1 text-info"></i> ID: <span class="fw-bold text-body-emphasis">{{ (!empty($emp->employee_id) && $emp->employee_id !== '0') ? $emp->employee_id : 'EMP-' . sprintf('%04d', $emp->id) }}</span>
                        </div>
                    </div>

                    <div class="mt-auto d-flex align-items-center justify-content-center gap-1">
                        <button class="btn btn-outline-info btn-sm p-1 px-2 fs-9" onclick="navigator.clipboard.writeText('{{ route('onboarding', md5((string)$emp->user_id)) }}'); toastr.success('Onboarding link copied to clipboard!');" title="Copy Onboarding Link">
                            <i class="fa-solid fa-link me-1"></i> Link
                        </button>
                        <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-primary-subtle text-primary btn-sm p-1 px-2 fs-9 fw-bold" title="View Profile">
                            <i class="fa-solid fa-eye me-1"></i> View
                        </a>
                        @can('edit.employees')
                            <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-warning-subtle text-warning btn-sm p-1 px-2 fs-9" title="Edit Record">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        @endcan
                        @can('delete.employees')
                            <form method="POST" action="{{ route('employees.destroy', $emp->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-subtle text-danger btn-sm p-1 px-2 fs-9" title="Delete Record">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <x-empty-state 
                    icon="fa-solid fa-users-slash" 
                    title="No Employee Records Found" 
                    description="No employees matched your current search filters. Try clearing your search parameters."
                    actionUrl="{{ route('employees.index') }}"
                    actionText="Reset Directory Filters"
                />
            </div>
        @endforelse
    </div>
@else
    <!-- Table List View Mode -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8">
                    <thead class="bg-body-secondary">
                        <tr>
                            <th class="ps-4 text-body-secondary fs-9 text-uppercase tracking-wider">Employee ID</th>
                            <th class="text-body-secondary fs-9 text-uppercase tracking-wider">Full Name</th>
                            <th class="text-body-secondary fs-9 text-uppercase tracking-wider">Company</th>
                            <th class="text-body-secondary fs-9 text-uppercase tracking-wider">Department & Designation</th>
                            <th class="text-body-secondary fs-9 text-uppercase tracking-wider">Contact Email</th>
                            <th class="text-body-secondary fs-9 text-uppercase tracking-wider">Status</th>
                            <th class="text-end pe-4 text-body-secondary fs-9 text-uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            @php
                                $companyName = $emp->company->name ?? $emp->company->company_name ?? 'Default Corp';
                                $companyId = $emp->company_id ?? 1;
                                $compColor = $colorPalette[$companyId % count($colorPalette)];
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-primary">{{ (!empty($emp->employee_id) && $emp->employee_id !== '0') ? $emp->employee_id : 'EMP-' . sprintf('%04d', $emp->id) }}</span>
                                    <div class="fs-9 text-body-secondary"><i class="fa-solid fa-id-card me-1"></i>Card: {{ $emp->card_no ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($emp->profile_picture && file_exists(public_path('uploads/profile/' . $emp->profile_picture)))
                                            <img src="{{ asset('uploads/profile/' . $emp->profile_picture) }}" alt="{{ $emp->first_name }}" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar-sm rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-7" style="width: 40px; height: 40px;">
                                                {{ substr($emp->first_name ?? 'E', 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('employees.show', $emp->id) }}" class="fw-semibold text-body-emphasis text-decoration-none">
                                                {{ $emp->first_name }} {{ $emp->last_name }}
                                            </a>
                                            <div class="fs-9 text-body-secondary">{{ $emp->username ? '@' . $emp->username : '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $compColor }}-subtle text-{{ $compColor }} fw-bold fs-9">
                                        <i class="fa-solid fa-building me-1"></i>{{ $companyName }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-body-emphasis">{{ $emp->department->department_name ?? $emp->department->name ?? 'General' }}</div>
                                    <div class="fs-9 text-body-secondary">{{ $emp->designation->designation_name ?? $emp->designation->name ?? 'Staff Member' }}</div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $emp->email }}" class="text-body-secondary text-decoration-none">{{ $emp->email }}</a>
                                </td>
                                <td>
                                    <x-status-badge :status="$emp->is_active" pulse="true" />
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-outline-info btn-sm me-1 p-1 px-2" onclick="navigator.clipboard.writeText('{{ route('onboarding', md5((string)$emp->user_id)) }}'); toastr.success('Onboarding link copied to clipboard!');" title="Copy Onboarding Link">
                                        <i class="fa-solid fa-link"></i>
                                    </button>
                                    <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-primary-subtle text-primary btn-sm me-1 p-1 px-2" title="View Profile">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @can('edit.employees')
                                        <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-warning-subtle text-warning btn-sm me-1 p-1 px-2" title="Edit Record">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    @endcan
                                    @can('delete.employees')
                                        <form method="POST" action="{{ route('employees.destroy', $emp->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger-subtle text-danger btn-sm p-1 px-2" title="Delete Record">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-users-slash" 
                                        title="No Employee Records Found" 
                                        description="No employees matched your current search filters. Try clearing your search parameters."
                                        actionUrl="{{ route('employees.index') }}"
                                        actionText="Reset Directory Filters"
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@if($employees->hasPages())
    <div class="d-flex justify-content-center mb-4">
        {{ $employees->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
