@extends('layouts.app')

@section('title', 'Job Code Tags')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Company Job Code Tags</h1>
            <p class="text-muted fs-7 mb-0">Manage job requisition code identifiers e.g. `JOB-DEV-001` paired across hiring requisitions.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('recruitment-job-posts.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-briefcase me-1"></i> Job Openings
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createJobCodeModal">
                <i class="fa-solid fa-plus me-1"></i> Create Job Code
            </button>
        </div>
    </div>

    <!-- Main Job Codes Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('recruitment-job-codes.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search job code or position..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Job Code Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Job Code Tag</th>
                            <th>Target Position / Role</th>
                            <th>Added By</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobCodes as $jc)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge badge-light-primary font-monospace fs-8">{{ $jc->job_code }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-gray-900">{{ $jc->position }}</div>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $jc->added_by ?? 'Recruiter' }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $jc->added_date ?? '--' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $jc->status_badge_class }}">
                                        {{ $jc->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-light-primary py-1 px-2 fs-8" data-bs-toggle="modal" data-bs-target="#editJobCodeModal{{ $jc->job_code_id }}">
                                        <i class="fa-solid fa-pen me-1"></i> Edit
                                    </button>

                                    <!-- Modal: Edit Job Code -->
                                    <div class="modal fade text-start" id="editJobCodeModal{{ $jc->job_code_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form method="POST" action="{{ route('recruitment-job-codes.update', $jc->job_code_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa-solid fa-pen me-2 text-primary"></i> Edit Job Code Tag</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Job Code Tag <span class="text-danger">*</span></label>
                                                            <input type="text" name="job_code" class="form-control form-control-sm" required value="{{ $jc->job_code }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Target Position / Role Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="position" class="form-control form-control-sm" required value="{{ $jc->position }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Status</label>
                                                            <select name="status" class="form-select form-select-sm">
                                                                <option value="active" {{ strtolower((string)$jc->status) === 'active' || $jc->status == 1 ? 'selected' : '' }}>Active</option>
                                                                <option value="inactive" {{ strtolower((string)$jc->status) === 'inactive' || $jc->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Update Job Code</button>
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
                                    <i class="fa-solid fa-tags fs-2 mb-2 d-block text-muted"></i>
                                    No job codes registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jobCodes->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $jobCodes->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Create Job Code -->
<div class="modal fade" id="createJobCodeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('recruitment-job-codes.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-tag me-2 text-primary"></i> Create Job Code Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Job Code Tag <span class="text-danger">*</span></label>
                        <input type="text" name="job_code" class="form-control form-control-sm" required placeholder="e.g. JOB-DEV-001 / SR-ACC-2026">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Target Position / Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="position" class="form-control form-control-sm" required placeholder="e.g. Senior Software Engineer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Save Job Code</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
