@extends('layouts.app')

@section('title', 'Employee Directory')
@section('page_title', 'Employee Directory Management')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner (Matching /recruitment-applications benchmark) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-users me-1"></i> Human Resources
                </span>
                <span class="text-body-secondary fs-9">• Employee Directory</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Employee Directory</h4>
            <p class="text-body-secondary fs-8 mb-0">Manage enterprise employee records, credentials, organizational assignments, and onboarding.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('departments.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-sitemap me-1.5 text-primary"></i> Departments
            </a>
            <a href="{{ route('designations.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-id-badge me-1.5 text-info"></i> Designations
            </a>
            @can('edit.employees')
                <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs">
                    <i class="fa-solid fa-user-plus me-1.5"></i> Add New Employee
                </a>
            @endcan
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards (Rule 13 exact pattern) -->
    <div class="row g-3 mb-4">
        <!-- Active Staff -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Active Staff</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $totalActive }}</h3>
                        <span class="fs-9 text-body-secondary">Verified active employees</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Departments -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Departments</span>
                        <h3 class="fw-bolder text-primary mb-1 mt-1">{{ $totalDepartments }}</h3>
                        <span class="fs-9 text-body-secondary">Operational business units</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-sitemap fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Inactive / Resigned -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Inactive / Resigned</span>
                        <h3 class="fw-bolder text-warning mb-1 mt-1">{{ $totalInactive }}</h3>
                        <span class="fs-9 text-body-secondary">Historical exited profiles</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-clock fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Total Records -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Records</span>
                        <h3 class="fw-bolder text-info mb-1 mt-1">{{ $employees->total() }}</h3>
                        <span class="fs-9 text-body-secondary">Matching directory profiles</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-address-book fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar & Search Bar (Matching Rule 13 pattern) -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('employees.index') }}" class="row g-2 align-items-center">
                <input type="hidden" name="view" value="{{ request('view', 'table') }}">
                
                <!-- Search Input -->
                <div class="col-lg-3 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body text-body-secondary border-end-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-body text-body-emphasis border-start-0" placeholder="Search name, ID, email..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Company Filter -->
                <div class="col-lg-3 col-md-6">
                    <select name="company_id" class="form-select form-select-sm bg-body text-body-emphasis select-search">
                        <option value="">All Companies</option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>
                                {{ $comp->name ?? $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Department Filter -->
                <div class="col-lg-2 col-md-6">
                    <select name="department_id" class="form-select form-select-sm bg-body text-body-emphasis select-search">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->department_name ?? $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-lg-2 col-md-6">
                    <select name="status" class="form-select form-select-sm bg-body text-body-emphasis">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Terminated</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Left</option>
                        <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Abscond</option>
                        <option value="5" {{ request('status') === '5' ? 'selected' : '' }}>Disabled</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Resigned</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="col-lg-2 col-md-12 d-flex gap-1.5 justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold rounded-2 w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('company_id') || request()->filled('department_id') || request()->filled('status'))
                        <a href="{{ route('employees.index', ['view' => request('view', 'table')]) }}" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Directory Container Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-body-tertiary mb-4">
        <!-- Card Header with Title & View Switcher -->
        <div class="card-header border-0 bg-transparent py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center">
                <div class="avatar-xs rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-users fs-7"></i>
                </div>
                <h6 class="card-title mb-0 fw-bold text-body-emphasis">Employee Records Roster</h6>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-2 fs-9">{{ $employees->total() }} total</span>
            </div>

            <!-- View Switcher -->
            <div class="btn-group btn-group-sm" role="group" aria-label="Directory View Selector">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}" class="btn btn-sm {{ request('view', 'table') === 'table' ? 'btn-primary' : 'btn-body border text-body-emphasis' }} fw-semibold rounded-start-2">
                    <i class="fa-solid fa-list me-1"></i> Table View
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="btn btn-sm {{ request('view') === 'grid' ? 'btn-primary' : 'btn-body border text-body-emphasis' }} fw-semibold rounded-end-2">
                    <i class="fa-solid fa-grip me-1"></i> Grid View
                </a>
            </div>
        </div>

        @php
            $colorPalette = ['info', 'primary', 'success', 'warning', 'danger', 'secondary'];
        @endphp

        @if(request('view') === 'grid')
            <!-- Grid Cards View Mode -->
            <div class="card-body p-3">
                <div class="row g-3">
                    @forelse($employees as $emp)
                        @php
                            $companyName = $emp->company->name ?? $emp->company->company_name ?? 'Default Corp';
                            $companyId = $emp->company_id ?? 1;
                            $compColor = $colorPalette[$companyId % count($colorPalette)];
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100 p-3 d-flex flex-column text-center position-relative">
                                <div class="position-absolute top-0 end-0 me-3 mt-3">
                                    <x-status-badge :status="$emp->is_active" pulse="true" />
                                </div>
                                
                                <div class="mb-3 mt-2">
                                    @if($emp->profile_picture && file_exists(public_path('uploads/profile/' . $emp->profile_picture)))
                                        <img src="{{ asset('uploads/profile/' . $emp->profile_picture) }}" alt="{{ $emp->first_name }}" class="rounded-circle border border-2 border-primary shadow-xs" style="width: 68px; height: 68px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary-subtle text-primary mx-auto d-flex align-items-center justify-content-center fw-bold fs-4 shadow-xs" style="width: 68px; height: 68px;">
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
                                    <span class="badge bg-{{ $compColor }}-subtle text-{{ $compColor }} border border-{{ $compColor }}-subtle rounded-pill fs-9 fw-semibold px-2 py-0.5">
                                        <i class="fa-solid fa-building me-1"></i>{{ $companyName }}
                                    </span>
                                    <span class="badge bg-secondary-subtle text-body-secondary border border-secondary-subtle rounded-pill fs-9 fw-semibold px-2 py-0.5">
                                        <i class="fa-solid fa-sitemap me-1"></i>{{ $emp->department->department_name ?? $emp->department->name ?? 'General' }}
                                    </span>
                                </div>

                                <div class="bg-body-tertiary border border-subtle rounded-3 p-2 mb-3 fs-9 text-start">
                                    <div class="text-truncate text-body-secondary mb-1">
                                        <i class="fa-solid fa-envelope me-1 text-primary"></i> <a href="mailto:{{ $emp->email }}" class="text-body-secondary text-decoration-none">{{ $emp->email }}</a>
                                    </div>
                                    <div class="text-body-secondary mb-1">
                                        <i class="fa-solid fa-id-card me-1 text-info"></i> ID: <span class="fw-bold font-monospace text-body-emphasis">{{ (!empty($emp->employee_id) && $emp->employee_id !== '0') ? $emp->employee_id : 'EMP-' . sprintf('%04d', $emp->id) }}</span>
                                    </div>
                                    <div class="text-truncate text-body-secondary">
                                        <i class="fa-solid fa-user-tie me-1 text-secondary"></i> Mgr: 
                                        @if($emp->manager)
                                            <a href="{{ route('employees.show', $emp->manager->id) }}" class="text-body-emphasis fw-medium text-decoration-none">{{ $emp->manager->first_name }} {{ $emp->manager->last_name }}</a>
                                        @else
                                            <span class="text-body-tertiary">Top Executive / None</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-auto d-inline-flex align-items-center justify-content-center" style="gap: 6px;">
                                    <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="View Profile">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @can('edit.employees')
                                        <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" title="Edit Record">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    @endcan
                                    <button type="button" class="btn btn-sm btn-outline-info px-2.5 rounded-2" onclick="navigator.clipboard.writeText('{{ route('onboarding', md5((string)$emp->user_id)) }}'); toastr.success('Onboarding link copied to clipboard!');" title="Copy Onboarding Link">
                                        <i class="fa-solid fa-link"></i>
                                    </button>
                                    @can('delete.employees')
                                        <form method="POST" action="{{ route('employees.destroy', $emp->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Record">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-body-secondary">
                            <i class="fa-solid fa-users-slash fs-2 mb-2 d-block text-body-tertiary"></i>
                            No employee records found matching criteria.
                            <div class="mt-3">
                                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary px-3">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Directory Filters
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <!-- Table List View Mode -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8">
                    <thead class="bg-body-secondary text-body-secondary border-bottom text-uppercase fs-9 fw-bold tracking-wider">
                        <tr>
                            <th class="ps-3 text-nowrap" style="width: 140px;">Actions</th>
                            <th class="text-nowrap">ID</th>
                            <th class="text-nowrap">Employee</th>
                            <th class="text-nowrap">Company</th>
                            <th class="text-nowrap">Dept / Role</th>
                            <th class="text-nowrap">Manager</th>
                            <th class="text-nowrap">Email</th>
                            <th class="pe-3 text-end text-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($employees as $emp)
                            @php
                                $companyName = $emp->company->name ?? $emp->company->company_name ?? 'Default Corp';
                                $companyId = $emp->company_id ?? 1;
                                $compColor = $colorPalette[$companyId % count($colorPalette)];
                            @endphp
                            <tr>
                                <td class="ps-3 text-nowrap">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- View Profile (Rule 8: Blue outline) -->
                                        <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="View Profile">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <!-- Edit Record (Rule 8: Amber outline) -->
                                        @can('edit.employees')
                                            <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" title="Edit Record">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endcan

                                        <!-- Copy Onboarding Link (Rule 8: Cyan outline) -->
                                        <button type="button" class="btn btn-sm btn-outline-info px-2.5 rounded-2" onclick="navigator.clipboard.writeText('{{ route('onboarding', md5((string)$emp->user_id)) }}'); toastr.success('Onboarding link copied to clipboard!');" title="Copy Onboarding Link">
                                            <i class="fa-solid fa-link"></i>
                                        </button>

                                        <!-- Delete Record (Rule 8: Danger outline) -->
                                        @can('delete.employees')
                                            <form method="POST" action="{{ route('employees.destroy', $emp->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Record">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold font-monospace text-primary">{{ (!empty($emp->employee_id) && $emp->employee_id !== '0') ? $emp->employee_id : 'EMP-' . sprintf('%04d', $emp->id) }}</span>
                                    <div class="fs-9 text-body-secondary"><i class="fa-solid fa-id-card me-1"></i>Card: {{ $emp->card_no ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        @if($emp->profile_picture && file_exists(public_path('uploads/profile/' . $emp->profile_picture)))
                                            <img src="{{ asset('uploads/profile/' . $emp->profile_picture) }}" alt="{{ $emp->first_name }}" class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 36px; height: 36px;">
                                                {{ substr($emp->first_name ?? 'E', 0, 1) }}{{ substr($emp->last_name ?? '', 0, 1) }}
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
                                    <span class="badge bg-{{ $compColor }}-subtle text-{{ $compColor }} border border-{{ $compColor }}-subtle rounded-pill fw-semibold fs-9 px-2.5 py-1">
                                        <i class="fa-solid fa-building me-1"></i>{{ $companyName }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-body-emphasis">{{ $emp->department->department_name ?? $emp->department->name ?? 'General' }}</div>
                                    <div class="fs-9 text-body-secondary">{{ $emp->designation->designation_name ?? $emp->designation->name ?? 'Staff Member' }}</div>
                                </td>
                                <td>
                                    @if($emp->manager)
                                        <div class="d-flex align-items-center gap-2">
                                            @if($emp->manager->profile_picture && file_exists(public_path('uploads/profile/' . $emp->manager->profile_picture)))
                                                <img src="{{ asset('uploads/profile/' . $emp->manager->profile_picture) }}" alt="{{ $emp->manager->first_name }}" class="rounded-circle border" style="width: 28px; height: 28px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 28px; height: 28px;">
                                                    {{ substr($emp->manager->first_name ?? 'M', 0, 1) }}{{ substr($emp->manager->last_name ?? '', 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('employees.show', $emp->manager->id) }}" class="fw-semibold text-body-emphasis text-decoration-none fs-8 text-truncate d-block" style="max-width: 140px;">
                                                    {{ $emp->manager->first_name }} {{ $emp->manager->last_name }}
                                                </a>
                                                <div class="fs-9 text-body-secondary font-monospace">
                                                    {{ (!empty($emp->manager->employee_id) && $emp->manager->employee_id !== '0') ? $emp->manager->employee_id : 'EMP-' . sprintf('%04d', $emp->manager->id) }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary-subtle text-body-secondary border border-secondary-subtle rounded-pill font-monospace fs-9">
                                            <i class="fa-solid fa-crown me-1 text-warning"></i> Level 1 / None
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="mailto:{{ $emp->email }}" class="text-body-secondary text-decoration-none fs-8">
                                        <i class="fa-regular fa-envelope me-1 text-primary"></i>{{ $emp->email }}
                                    </a>
                                </td>
                                <td class="pe-3 text-end">
                                    <x-status-badge :status="$emp->is_active" pulse="true" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-body-secondary">
                                    <i class="fa-solid fa-users-slash fs-2 mb-2 d-block text-body-tertiary"></i>
                                    No employee records found matching criteria.
                                    <div class="mt-3">
                                        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary px-3">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Reset Directory Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($employees->hasPages())
            <div class="card-footer py-3 border-top border-subtle bg-body">
                {{ $employees->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select-search').select2({
                width: '100%',
                allowClear: true
            });
        }
    });
</script>
@endpush
