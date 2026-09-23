@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success-subtle text-success fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-chart-line me-1"></i> Talent & Growth
                </span>
                <span class="text-body-secondary fs-9">• Direct Team Scorecards & KRA Reviews</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">Team Performance & Appraisals</h4>
            <p class="text-body-secondary fs-8 mb-0">Track performance indicator scores, evaluate core competencies, and review historical employee evaluations.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('manager-portal.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Manager Workstation
            </a>
        </div>
    </div>

    <!-- 3 Telemetry KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Reviews -->
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Total Evaluations</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $stats['total_reviews'] ?? $appraisals->total() }}</h3>
                        <span class="fs-9 text-body-secondary">Cumulative appraisal records</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-clipboard-list fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- High Performers -->
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Top Performers (≥ 4.0)</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $stats['high_performers'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Consistently exceeding targets</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-medal fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Team Size -->
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Direct Team Size</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $stats['team_size'] ?? 0 }}</h3>
                        <span class="fs-9 text-body-secondary">Active direct reports</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('manager-portal.team_performance') }}" class="row g-2 align-items-center">
                <div class="col-sm-auto">
                    <span class="text-body-secondary fs-9 fw-bold text-uppercase me-2">Filter Appraisals:</span>
                </div>
                <div class="col-sm-auto">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body text-body-secondary border-end-0"><i class="fa-solid fa-calendar"></i></span>
                        <select name="year" class="form-select bg-body text-body-emphasis border-start-0" onchange="this.form.submit()">
                            <option value="">All Review Years</option>
                            @php $currentYear = (int) date('Y'); @endphp
                            @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Cycle {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                @if(request('year'))
                    <div class="col-sm-auto">
                        <a href="{{ route('manager-portal.team_performance') }}" class="btn btn-sm btn-outline-secondary fs-9 rounded-2">
                            <i class="fa-solid fa-rotate-left me-1"></i> Clear Filter
                        </a>
                    </div>
                @endif
                <div class="col-sm-auto ms-sm-auto">
                    <span class="badge bg-body text-body-secondary border px-2.5 py-1.5 rounded-pill fs-9">
                        {{ $appraisals->total() }} Records Found
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Appraisals Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8 border-top">
                    <thead class="bg-body-secondary text-body-secondary">
                        <tr>
                            <!-- Rule 8: Column 1 Action buttons -->
                            <th class="ps-4" style="width: 100px;">Actions</th>
                            <th>Team Member</th>
                            <th>Appraisal Cycle</th>
                            <th>Overall Score</th>
                            <th>Rating Classification</th>
                            <th class="pe-4">Manager Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appraisals as $appr)
                            @php
                                $score = (float) $appr->overall_rating;
                                $scoreClass = $score >= 4.0 ? 'text-success' : ($score >= 3.0 ? 'text-primary' : 'text-warning');
                                $badgeClass = $score >= 4.5 ? 'bg-success-subtle text-success border border-success-subtle' :
                                             ($score >= 3.8 ? 'bg-primary-subtle text-primary border border-primary-subtle' :
                                             ($score >= 3.0 ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle'));
                            @endphp
                            <tr>
                                <!-- Column 1: Action (Rule 8: Icon-only, 6px gap, rounded-2) -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <button type="button" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" title="View Detailed Scorecard Dossier" data-bs-toggle="modal" data-bs-target="#appraisalModal_{{ $appr->performance_appraisal_id ?? $appr->id }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        @if(!empty($appr->employee?->email))
                                            <a href="mailto:{{ $appr->employee->email }}?subject=Performance Appraisal Feedback" class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" title="Email Employee Feedback">
                                                <i class="fa-solid fa-envelope"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Team Member -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-8" style="width: 36px; height: 36px; min-width: 36px;">
                                            {{ strtoupper(substr($appr->employee->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($appr->employee->last_name ?? '', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body-emphasis leading-tight">
                                                {{ $appr->employee ? $appr->employee->first_name . ' ' . $appr->employee->last_name : 'Employee #' . $appr->employee_id }}
                                            </div>
                                            <div class="fs-9 text-body-secondary font-monospace">
                                                ID: {{ $appr->employee->employee_id ?? $appr->employee_id }} • {{ $appr->employee->designation->designation_name ?? 'Staff' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Period / Title -->
                                <td>
                                    <div class="fw-semibold text-body-emphasis">
                                        {{ $appr->formatted_month ?? ($appr->appraisal_year ?? date('Y')) }}
                                    </div>
                                    <div class="fs-9 text-body-secondary">
                                        {{ $appr->title ?? 'Annual Review' }}
                                    </div>
                                </td>

                                <!-- Overall Score -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold fs-7 {{ $scoreClass }}">
                                            <i class="fa-solid fa-star me-1 text-warning"></i> {{ number_format($score, 1) }}
                                        </span>
                                        <span class="text-body-secondary fs-9">/ 5.0</span>
                                    </div>
                                </td>

                                <!-- Classification -->
                                <td>
                                    <span class="badge {{ $badgeClass }} fw-semibold fs-9 px-2.5 py-1 rounded-pill">
                                        {{ $appr->rating_label }}
                                    </span>
                                </td>

                                <!-- Remarks Snippet -->
                                <td class="pe-4">
                                    <span class="text-body-secondary fs-8">
                                        {{ Str::limit($appr->remarks, 50, '...') ?: 'Standard evaluation completed.' }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Modal: Detailed Appraisal Dossier -->
                            <div class="modal fade" id="appraisalModal_{{ $appr->performance_appraisal_id ?? $appr->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-body border-0 shadow">
                                        <div class="modal-header border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                                                    <i class="fa-solid fa-award"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">
                                                        Performance Evaluation — {{ $appr->employee->first_name ?? 'Employee' }} {{ $appr->employee->last_name ?? '' }}
                                                    </h5>
                                                    <span class="fs-9 text-body-secondary">Cycle: {{ $appr->formatted_month ?? ($appr->appraisal_year ?? date('Y')) }}</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body p-4">
                                            <!-- Overall Score Banner -->
                                            <div class="p-3 rounded-3 bg-body-tertiary border mb-4 d-flex align-items-center justify-content-between">
                                                <div>
                                                    <span class="text-body-secondary fs-9 fw-bold text-uppercase d-block mb-1">Composite Rating</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <h3 class="fw-bolder text-body-emphasis mb-0">
                                                            <i class="fa-solid fa-star text-warning me-1"></i> {{ number_format($score, 1) }}
                                                        </h3>
                                                        <span class="badge {{ $badgeClass }} fs-8 fw-semibold px-2.5 py-1 rounded-pill">
                                                            {{ $appr->rating_label }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fs-9 text-body-secondary d-block">Employee ID</span>
                                                    <span class="fw-bold text-body-emphasis fs-8 font-monospace">{{ $appr->employee->employee_id ?? $appr->employee_id }}</span>
                                                </div>
                                            </div>

                                            <!-- Competency Breakdown -->
                                            <h6 class="fw-bold text-body-emphasis fs-7 mb-3">
                                                <i class="fa-solid fa-chart-simple me-2 text-primary"></i> Core Competency Scores
                                            </h6>
                                            <div class="row g-2 mb-4">
                                                @php
                                                    $competencies = [
                                                        'Quality of Work' => $appr->quality_of_work ?? 4,
                                                        'Efficiency' => $appr->efficiency ?? 4,
                                                        'Job Knowledge' => $appr->job_knowledge ?? 4,
                                                        'Teamwork' => $appr->team_work ?? $appr->teamwork ?? 4,
                                                        'Communication' => $appr->communication ?? 4,
                                                        'Problem Solving' => $appr->problem_solving ?? 4,
                                                        'Attendance & Punctuality' => $appr->attendance ?? 4,
                                                        'Integrity' => $appr->integrity ?? 5,
                                                        'Professionalism' => $appr->professionalism ?? 4,
                                                        'Meeting Deadlines' => $appr->ability_to_meet_deadline ?? 4,
                                                    ];
                                                @endphp
                                                @foreach($competencies as $compName => $compVal)
                                                    <div class="col-sm-6">
                                                        <div class="p-2.5 rounded-2 bg-body-tertiary border d-flex align-items-center justify-content-between">
                                                            <span class="fs-8 text-body-emphasis fw-medium">{{ $compName }}</span>
                                                            <span class="badge bg-body text-body-emphasis border fw-bold fs-9">
                                                                <i class="fa-solid fa-star text-warning me-1"></i> {{ $compVal }} / 5
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Strengths & Improvement -->
                                            <div class="row g-3 mb-3">
                                                @if(!empty($appr->area_strength))
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-9 fw-bold text-uppercase text-body-secondary">Key Strengths</label>
                                                        <div class="p-3 rounded-2 bg-success-subtle bg-opacity-20 border border-success-subtle text-body-emphasis fs-8">
                                                            {{ $appr->area_strength }}
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(!empty($appr->area_imp))
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-9 fw-bold text-uppercase text-body-secondary">Areas of Improvement</label>
                                                        <div class="p-3 rounded-2 bg-warning-subtle bg-opacity-20 border border-warning-subtle text-body-emphasis fs-8">
                                                            {{ $appr->area_imp }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Future Goals -->
                                            @if(!empty($appr->future_goals))
                                                <div class="mb-3">
                                                    <label class="form-label fs-9 fw-bold text-uppercase text-body-secondary">Future Goals & OKRs</label>
                                                    <div class="p-3 rounded-2 bg-body-tertiary border text-body-emphasis fs-8">
                                                        {{ $appr->future_goals }}
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- General Remarks -->
                                            @if(!empty($appr->remarks))
                                                <div class="mb-0">
                                                    <label class="form-label fs-9 fw-bold text-uppercase text-body-secondary">Evaluator Remarks</label>
                                                    <div class="p-3 rounded-2 bg-body-tertiary border text-body-emphasis fs-8">
                                                        {{ $appr->remarks }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="modal-footer border-top bg-body-tertiary">
                                            <button type="button" class="btn btn-sm btn-body border text-body-emphasis px-3" data-bs-dismiss="modal">Close Dossier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="p-0">
                                    <x-empty-state 
                                        icon="fa-solid fa-star-half-stroke" 
                                        title="No Appraisal Records Found" 
                                        description="There are currently no performance evaluations on file for your direct reportees in the selected cycle."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($appraisals->hasPages())
                <div class="card-footer border-top bg-transparent py-3 px-4">
                    {{ $appraisals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
