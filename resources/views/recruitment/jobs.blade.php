@extends('layouts.app')

@section('title', 'Job Openings & Requisitions')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Job Openings & Requisitions</h1>
            <p class="text-muted fs-7 mb-0">Publish job requisitions, track active vacancies, locations, and closing deadlines.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('recruitment-applications.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-users me-1"></i> Candidate Pipeline
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createJobPostModal">
                <i class="fa-solid fa-plus me-1"></i> Create Job Requisition
            </button>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-primary text-primary me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-briefcase fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Job Posts</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['total_posts'] ?? 0 }} Requisitions</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-success text-success me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Published Openings</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['active_posts'] ?? 0 }} Active</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-info text-info me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-user-plus fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Vacancies</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['total_vacancies'] ?? 0 }} Openings</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-danger text-danger me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-folder-closed fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Closed Requisitions</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['closed_posts'] ?? 0 }} Posts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Job Requisitions Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('recruitment-job-posts.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search job code, title, location, or department..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Requisition Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published / Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Closed / Draft</option>
                    </select>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('recruitment-job-posts.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Job Code</th>
                            <th>Position & Title</th>
                            <th>Job Type & Location</th>
                            <th>Vacancies</th>
                            <th>Experience</th>
                            <th>Closing Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $jb)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge badge-light-primary font-monospace fs-8">{{ $jb->job_code ?? 'JOB-000' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-gray-900">{{ $jb->job_title }}</div>
                                    <div class="fs-9 text-muted">{{ $jb->department ?? 'General Department' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-info text-uppercase fs-9">{{ $jb->job_type ?? 'Full Time' }}</span>
                                    <div class="fs-9 text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ $jb->job_location ?? 'On-Site' }}</div>
                                </td>
                                <td>
                                    <span class="font-monospace fw-bold text-primary">{{ $jb->job_vacancy }} Positions</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $jb->minimum_experience ?? '0' }} - {{ $jb->maximum_experience ?? '5' }} Yrs</span>
                                </td>
                                <td>
                                    <span class="fw-medium text-gray-800">{{ $jb->formatted_closing_date }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $jb->status_badge_class }}">
                                        {{ $jb->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-light-primary py-1 px-2 fs-8" data-bs-toggle="modal" data-bs-target="#editJobPostModal{{ $jb->job_id }}">
                                        <i class="fa-solid fa-pen me-1"></i> Edit
                                    </button>

                                    <!-- Modal: Edit Job Post Requisition -->
                                    <div class="modal fade text-start" id="editJobPostModal{{ $jb->job_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <form method="POST" action="{{ route('recruitment-job-posts.update', $jb->job_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa-solid fa-pen me-2 text-primary"></i> Edit Job Opening Requisition</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3 mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fs-8 fw-semibold">Job Title / Role <span class="text-danger">*</span></label>
                                                                <input type="text" name="job_title" class="form-control form-control-sm" required value="{{ $jb->job_title }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fs-8 fw-semibold">Job Code Tag</label>
                                                                <select name="job_code" class="form-select form-select-sm">
                                                                    <option value="">Select Job Code</option>
                                                                    @foreach($jobCodes as $jc)
                                                                        <option value="{{ $jc->job_code }}" {{ $jb->job_code === $jc->job_code ? 'selected' : '' }}>{{ $jc->job_code }} - {{ $jc->position }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row g-3 mb-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label fs-8 fw-semibold">Job Type</label>
                                                                <select name="job_type" class="form-select form-select-sm">
                                                                    <option value="Full Time" {{ $jb->job_type === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                                                    <option value="Part Time" {{ $jb->job_type === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                                                    <option value="Contract" {{ $jb->job_type === 'Contract' ? 'selected' : '' }}>Contract</option>
                                                                    <option value="Internship" {{ $jb->job_type === 'Internship' ? 'selected' : '' }}>Internship</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fs-8 fw-semibold">Vacancies <span class="text-danger">*</span></label>
                                                                <input type="number" name="job_vacancy" class="form-control form-control-sm" required value="{{ $jb->job_vacancy }}" min="1">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fs-8 fw-semibold">Department</label>
                                                                <select name="department" class="form-select form-select-sm">
                                                                    <option value="">Select Department</option>
                                                                    @foreach($departments as $dept)
                                                                        <option value="{{ $dept->department_name }}" {{ $jb->department === $dept->department_name ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row g-3 mb-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label fs-8 fw-semibold">Job Location</label>
                                                                <input type="text" name="job_location" class="form-control form-control-sm" value="{{ $jb->job_location }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fs-8 fw-semibold">Min Experience (Yrs)</label>
                                                                <input type="text" name="minimum_experience" class="form-control form-control-sm" value="{{ $jb->minimum_experience }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fs-8 fw-semibold">Max Experience (Yrs)</label>
                                                                <input type="text" name="maximum_experience" class="form-control form-control-sm" value="{{ $jb->maximum_experience }}">
                                                            </div>
                                                        </div>

                                                        <div class="row g-3 mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fs-8 fw-semibold">Application Closing Date</label>
                                                                <input type="date" name="date_of_closing" class="form-control form-control-sm" value="{{ $jb->date_of_closing }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fs-8 fw-semibold">Publish Status</label>
                                                                <select name="status" class="form-select form-select-sm">
                                                                    <option value="1" {{ $jb->status == 1 ? 'selected' : '' }}>Published / Active</option>
                                                                    <option value="0" {{ $jb->status == 0 ? 'selected' : '' }}>Draft / Closed</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Short Requisition Summary</label>
                                                            <textarea name="short_description" class="form-control form-control-sm" rows="2">{{ $jb->short_description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Update Requisition</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-briefcase fs-2 mb-2 d-block text-muted"></i>
                                    No job opening requisitions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jobs->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $jobs->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Create Job Requisition -->
<div class="modal fade" id="createJobPostModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('recruitment-job-posts.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-briefcase me-2 text-primary"></i> Create Job Opening Requisition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Job Title / Role <span class="text-danger">*</span></label>
                            <input type="text" name="job_title" class="form-control form-control-sm" required placeholder="e.g. Senior Laravel Developer / HR Manager">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Job Code Tag</label>
                            <select name="job_code" class="form-select form-select-sm">
                                <option value="">Select Job Code (Or auto generate)</option>
                                @foreach($jobCodes as $jc)
                                    <option value="{{ $jc->job_code }}">{{ $jc->job_code }} - {{ $jc->position }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Job Type</label>
                            <select name="job_type" class="form-select form-select-sm">
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Vacancies <span class="text-danger">*</span></label>
                            <input type="number" name="job_vacancy" class="form-control form-control-sm" required value="1" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Department</label>
                            <select name="department" class="form-select form-select-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_name }}">{{ $dept->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Job Location</label>
                            <input type="text" name="job_location" class="form-control form-control-sm" placeholder="e.g. Noida, India / Remote">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Min Experience (Yrs)</label>
                            <input type="text" name="minimum_experience" class="form-control form-control-sm" placeholder="e.g. 2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Max Experience (Yrs)</label>
                            <input type="text" name="maximum_experience" class="form-control form-control-sm" placeholder="e.g. 5">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Application Closing Date</label>
                            <input type="date" name="date_of_closing" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Publish Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="1" selected>Published / Active</option>
                                <option value="0">Draft / Closed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Short Requisition Summary</label>
                        <textarea name="short_description" class="form-control form-control-sm" rows="2" placeholder="Brief job opening summary and primary skills required..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Publish Requisition</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
