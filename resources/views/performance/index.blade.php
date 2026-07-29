@extends('layouts.app')

@section('title', 'Performance Appraisals & KPIs')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Performance Appraisals & Ratings</h1>
            <p class="text-muted fs-7 mb-0">Evaluate employee core competencies, track KPIs, and review feedback cards.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('performance-indicators.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-bullseye me-1"></i> Benchmark Indicators
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAppraisalModal">
                <i class="fa-solid fa-plus me-1"></i> New Performance Review
            </button>
        </div>
    </div>

    <!-- Monthly Summary Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-warning text-warning me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-star fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Portal Avg Rating</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ number_format($summary['average_score'] ?? 0, 1) }} / 5.0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-success text-success me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-trophy fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Top Performers</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['outstanding_count'] ?? 0 }} Staff</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-info text-info me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-thumbs-up fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Meets Expectations</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['meets_count'] ?? 0 }} Staff</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="symbol symbol-45px bg-light-primary text-primary me-3 rounded d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="fa-solid fa-clipboard-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-8 fw-semibold text-uppercase">Total Reviews</span>
                        <div class="fs-4 fw-bold text-gray-900">{{ $summary['total_appraisals'] ?? 0 }} Appraisals</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Performance Appraisal Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('performance-appraisals.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search employee name or ID..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('performance-appraisals.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Review Period</th>
                            <th>Overall Rating</th>
                            <th>Performance Level</th>
                            <th>Evaluator</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appraisals as $app)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width:35px; height:35px;">
                                            {{ substr($app->employee->first_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-900">{{ $app->employee->first_name ?? 'Staff' }} {{ $app->employee->last_name ?? '' }}</div>
                                            <div class="fs-9 text-muted">ID: {{ $app->employee->employee_id ?? $app->employee_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-gray-800">{{ $app->formatted_month }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="fw-bold text-gray-900 fs-7 me-1">{{ $app->overall_rating }}</span>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star fs-9 {{ $i <= round($app->overall_rating) ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $app->rating_badge_class }}">
                                        {{ $app->rating_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-700 fs-8">{{ $app->manager ? ($app->manager->first_name . ' ' . $app->manager->last_name) : ($app->added_by ?? 'Manager') }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('performance-appraisals.show', $app->performance_appraisal_id) }}" class="btn btn-light-primary btn-sm py-1 px-2 fs-8">
                                        <i class="fa-solid fa-eye me-1"></i> Report Card
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-chart-pie fs-2 mb-2 d-block text-muted"></i>
                                    No performance appraisal reviews found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($appraisals->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $appraisals->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: New Performance Review -->
<div class="modal fade" id="createAppraisalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('performance-appraisals.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-star-half-stroke me-2 text-primary"></i> Create Performance Appraisal Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Select Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select form-select-sm" required>
                                <option value="">Choose Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->user_id }}">
                                        {{ $emp->first_name }} {{ $emp->last_name }} (ID: {{ $emp->employee_id ?? $emp->user_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Appraisal Period (YYYY-MM) <span class="text-danger">*</span></label>
                            <input type="month" name="appraisal_year_month" class="form-control form-control-sm" required value="{{ date('Y-m') }}">
                        </div>
                    </div>

                    <h6 class="fs-8 fw-bold text-uppercase text-gray-700 mb-2 border-bottom pb-1">Core Competency Ratings (1 to 5 Stars)</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Quality of Work (1-5) <span class="text-danger">*</span></label>
                            <select name="quality_of_work" class="form-select form-select-sm" required>
                                <option value="5">5 - Outstanding</option>
                                <option value="4" selected>4 - Exceeds Expectations</option>
                                <option value="3">3 - Meets Expectations</option>
                                <option value="2">2 - Needs Improvement</option>
                                <option value="1">1 - Unsatisfactory</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Efficiency (1-5) <span class="text-danger">*</span></label>
                            <select name="efficiency" class="form-select form-select-sm" required>
                                <option value="5">5 - Outstanding</option>
                                <option value="4" selected>4 - Exceeds Expectations</option>
                                <option value="3">3 - Meets Expectations</option>
                                <option value="2">2 - Needs Improvement</option>
                                <option value="1">1 - Unsatisfactory</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Job Knowledge (1-5) <span class="text-danger">*</span></label>
                            <select name="job_knowledge" class="form-select form-select-sm" required>
                                <option value="5">5 - Outstanding</option>
                                <option value="4" selected>4 - Exceeds Expectations</option>
                                <option value="3">3 - Meets Expectations</option>
                                <option value="2">2 - Needs Improvement</option>
                                <option value="1">1 - Unsatisfactory</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Teamwork (1-5) <span class="text-danger">*</span></label>
                            <select name="teamwork" class="form-select form-select-sm" required>
                                <option value="5">5 - Outstanding</option>
                                <option value="4" selected>4 - Exceeds Expectations</option>
                                <option value="3">3 - Meets Expectations</option>
                                <option value="2">2 - Needs Improvement</option>
                                <option value="1">1 - Unsatisfactory</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Communication (1-5) <span class="text-danger">*</span></label>
                            <select name="communication" class="form-select form-select-sm" required>
                                <option value="5">5 - Outstanding</option>
                                <option value="4" selected>4 - Exceeds Expectations</option>
                                <option value="3">3 - Meets Expectations</option>
                                <option value="2">2 - Needs Improvement</option>
                                <option value="1">1 - Unsatisfactory</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-muted">Problem Solving (1-5) <span class="text-danger">*</span></label>
                            <select name="problem_solving" class="form-select form-select-sm" required>
                                <option value="5">5 - Outstanding</option>
                                <option value="4" selected>4 - Exceeds Expectations</option>
                                <option value="3">3 - Meets Expectations</option>
                                <option value="2">2 - Needs Improvement</option>
                                <option value="1">1 - Unsatisfactory</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="fs-8 fw-bold text-uppercase text-gray-700 mb-2 border-bottom pb-1">Qualitative Feedback & Goals</h6>
                    <div class="mb-2">
                        <label class="form-label fs-9 fw-semibold text-muted">Areas of Strength</label>
                        <textarea name="area_strength" class="form-control form-control-sm" rows="2" placeholder="Key accomplishments and major strengths..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fs-9 fw-semibold text-muted">Areas for Improvement</label>
                        <textarea name="area_imp" class="form-control form-control-sm" rows="2" placeholder="Skill gaps or improvement targets..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fs-9 fw-semibold text-muted">Future Goals & Objectives</label>
                        <textarea name="future_goals" class="form-control form-control-sm" rows="2" placeholder="Objectives for next quarter..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Save Performance Review</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
