@extends('layouts.app')

@section('title', 'Performance Appraisal Report Card')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Performance Review Report Card</h1>
            <p class="text-muted fs-7 mb-0">Detailed competency breakdown and evaluator assessment.</p>
        </div>
        <div>
            <a href="{{ route('performance-appraisals.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Appraisals
            </a>
        </div>
    </div>

    <!-- Employee Profile Summary Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="symbol symbol-60px bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3" style="width:60px; height:60px;">
                    {{ substr($appraisal->employee->first_name ?? 'E', 0, 1) }}
                </div>
                <div>
                    <h3 class="mb-1 text-gray-900 fw-bold">{{ $appraisal->employee->first_name ?? 'Employee' }} {{ $appraisal->employee->last_name ?? '' }}</h3>
                    <div class="fs-8 text-muted">
                        <span class="me-3"><i class="fa-solid fa-id-badge me-1"></i>ID: {{ $appraisal->employee->employee_id ?? $appraisal->employee_id }}</span>
                        <span><i class="fa-solid fa-calendar me-1"></i>Period: {{ $appraisal->formatted_month }}</span>
                    </div>
                </div>
            </div>

            <div class="text-md-end">
                <span class="text-muted fs-9 text-uppercase fw-semibold d-block">Overall Score</span>
                <div class="d-flex align-items-center gap-2">
                    <span class="display-6 fw-bold text-gray-900">{{ number_format($appraisal->overall_rating, 1) }}</span>
                    <div>
                        <span class="badge {{ $appraisal->rating_badge_class }} fs-7 mb-1 d-block">{{ $appraisal->rating_label }}</span>
                        <div class="text-warning fs-9">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= round($appraisal->overall_rating) ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competencies & Feedback Grid -->
    <div class="row g-4 mb-4">
        <!-- Core Competencies Score Breakdown -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="card-title fw-bold text-gray-900 mb-0"><i class="fa-solid fa-list-check me-2 text-primary"></i> Core Competency Evaluation</h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $competencies = [
                            'Quality of Work' => $appraisal->quality_of_work ?? 4,
                            'Efficiency & Speed' => $appraisal->efficiency ?? 4,
                            'Job Knowledge' => $appraisal->job_knowledge ?? 4,
                            'Teamwork & Collaboration' => $appraisal->teamwork ?? $appraisal->team_work ?? 4,
                            'Communication Skills' => $appraisal->communication ?? 4,
                            'Problem Solving' => $appraisal->problem_solving ?? 4,
                        ];
                    @endphp

                    @foreach($competencies as $label => $score)
                        @php
                            $percentage = ($score / 5) * 100;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fs-8 fw-semibold text-gray-800">{{ $label }}</span>
                                <span class="fs-8 font-monospace fw-bold text-gray-900">{{ number_format((float)$score, 1) }} / 5.0</span>
                            </div>
                            <div class="progress bg-light" style="height: 8px;">
                                <div class="progress-bar bg-primary rounded" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Feedback & Qualitative Notes -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="card-title fw-bold text-gray-900 mb-0"><i class="fa-solid fa-comment-dots me-2 text-primary"></i> Evaluator Feedback & Goals</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <span class="fs-9 text-uppercase text-success fw-bold d-block mb-1"><i class="fa-solid fa-circle-check me-1"></i> Areas of Strength</span>
                        <p class="fs-8 text-gray-800 bg-light-success p-3 rounded mb-0">{{ $appraisal->area_strength ?? 'Demonstrates high commitment to work quality and team collaboration.' }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="fs-9 text-uppercase text-warning fw-bold d-block mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Areas for Improvement</span>
                        <p class="fs-8 text-gray-800 bg-light-warning p-3 rounded mb-0">{{ $appraisal->area_imp ?? 'Continued focus on meeting aggressive project delivery deadlines.' }}</p>
                    </div>

                    <div>
                        <span class="fs-9 text-uppercase text-primary fw-bold d-block mb-1"><i class="fa-solid fa-bullseye me-1"></i> Future Goals</span>
                        <p class="fs-8 text-gray-800 bg-light-primary p-3 rounded mb-0">{{ $appraisal->future_goals ?? 'Lead technical architecture reviews and mentor junior team members.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
