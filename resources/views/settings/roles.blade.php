@extends('layouts.app')

@section('title', 'User Roles & Access Control')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">User Roles & Access Control</h1>
            <p class="text-muted fs-7 mb-0">Define security roles and configure dynamic sidebar module resource access permissions.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('system-settings.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-sliders me-1"></i> System Settings
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                <i class="fa-solid fa-plus me-1"></i> Create New Security Role
            </button>
        </div>
    </div>

    <!-- User Roles Table Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4" style="min-width: 100px;">Actions</th>
                            <th>Role ID & Name</th>
                            <th>Access Scope</th>
                            <th>Authorized Module Resources</th>
                            <th class="pe-4">Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $rl)
                            <tr>
                                <td class="ps-4">
                                    <button type="button" class="btn btn-sm btn-light-primary py-1 px-2 fs-8" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $rl->id }}">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Permissions
                                    </button>
                                </td>
                                <td>
                                    <div class="fw-bold text-gray-900"><i class="fa-solid fa-user-shield me-2 text-primary"></i>{{ $rl->role_name }}</div>
                                    <span class="fs-9 text-muted font-monospace">ROLE-#{{ $rl->id }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary text-uppercase fs-8">{{ $rl->role_access ?? 'Custom Access' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1" style="max-width:400px;">
                                        @forelse($rl->resource_list as $res)
                                            <span class="badge badge-light-info fs-9">{{ $res }}</span>
                                        @empty
                                            <span class="fs-9 text-muted">Full Access</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="pe-4">
                                    <span class="fs-8 text-gray-800">{{ $rl->created_at ?? '--' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-user-shield fs-2 mb-2 d-block text-muted"></i>
                                    No security roles created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialogs: Rendered at Root Level for Proper Styling -->

@foreach($roles as $rl)
    <!-- Modal: Edit Role -->
    <div class="modal fade" id="editRoleModal{{ $rl->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 900px; width: 100%; margin: 1.75rem auto !important;">
            <form method="POST" action="{{ route('user-roles.update', $rl->id) }}" class="w-100">
                @csrf
                @method('PUT')
                <div class="modal-content text-start">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-user-shield me-2 text-primary"></i> Edit Security Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold">Role Name <span class="text-danger">*</span></label>
                                <input type="text" name="role_name" class="form-control form-control-sm" required value="{{ $rl->role_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold">Access Scope</label>
                                <select name="role_access" class="form-select form-select-sm">
                                    <option value="all" {{ $rl->role_access === 'all' ? 'selected' : '' }}>All Modules Access</option>
                                    <option value="custom" {{ $rl->role_access === 'custom' ? 'selected' : '' }}>Custom Specific Modules</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold mb-2">Granular Module Permissions Matrix</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle fs-8">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Module</th>
                                            <th class="text-center">View</th>
                                            <th class="text-center">Create</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $availableResources = ['employees', 'organization', 'leave', 'attendance', 'payroll', 'performance', 'assets', 'recruitment', 'training', 'support_tickets', 'hr_tickets', 'admin_tickets', 'announcements', 'settings', 'api_control', 'reports'];
                                            $currentRes = $rl->resource_list;
                                        @endphp
                                        @foreach($availableResources as $resKey)
                                            <tr>
                                                <td class="fw-bold">{{ ucfirst(str_replace('_', ' ', $resKey)) }}</td>
                                                @foreach(['view', 'create', 'edit', 'delete'] as $act)
                                                    @php $val = "{$act}.{$resKey}"; @endphp
                                                    <td class="text-center">
                                                        <input class="form-check-input" type="checkbox" name="role_resources[]" value="{{ $val }}" {{ in_array($val, $currentRes) ? 'checked' : '' }}>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Update Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<!-- Modal: Create New Role -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 900px; width: 100%; margin: 1.75rem auto !important;">
        <form method="POST" action="{{ route('user-roles.store') }}" class="w-100">
            @csrf
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-user-shield me-2 text-primary"></i> Create New Security Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="role_name" class="form-control form-control-sm" required placeholder="e.g. HR Recruiter / Department Manager">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Access Scope</label>
                            <select name="role_access" class="form-select form-select-sm">
                                <option value="custom">Custom Specific Modules</option>
                                <option value="all">All Modules Access</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold mb-2">Granular Module Permissions Matrix</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle fs-8">
                                <thead class="table-light">
                                    <tr>
                                        <th>Module</th>
                                        <th class="text-center">View</th>
                                        <th class="text-center">Create</th>
                                        <th class="text-center">Edit</th>
                                        <th class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $availableResources = ['employees', 'organization', 'leave', 'attendance', 'payroll', 'performance', 'assets', 'recruitment', 'training', 'support_tickets', 'hr_tickets', 'admin_tickets', 'announcements', 'settings', 'api_control', 'reports'];
                                    @endphp
                                    @foreach($availableResources as $resKey)
                                        <tr>
                                            <td class="fw-bold">{{ ucfirst(str_replace('_', ' ', $resKey)) }}</td>
                                            @foreach(['view', 'create', 'edit', 'delete'] as $act)
                                                @php $val = "{$act}.{$resKey}"; @endphp
                                                <td class="text-center">
                                                    <input class="form-check-input" type="checkbox" name="role_resources[]" value="{{ $val }}" checked>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Create Security Role</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
