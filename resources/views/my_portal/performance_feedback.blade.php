@extends('layouts.app')

@section('title', 'Performance Self-Rating & Feedback')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-solid fa-star me-1 text-warning"></i> Self-Service Portal
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Career Growth & Reviews</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Performance Self-Rating & Feedback</h1>
            <p class="text-body-secondary fs-7 mb-0">Evaluate core competencies, document key achievements, and establish strategic goals for the upcoming review cycle.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-body border rounded-pill px-3 py-1.5 shadow-sm text-body-emphasis fs-9 fw-semibold">
                <i class="fa-regular fa-calendar me-1.5 text-primary"></i> Annual Appraisal Cycle 2026
            </span>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
            <div class="flex-grow-1 fs-8">
                <strong>Attention required:</strong> {{ $errors->first() }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Dynamic Scorecard Header (Airy & Elevated: DENSITY: 3, MOTION: 4, VARIANCE: 6) -->
    <div class="row g-3 mb-4">
        <!-- Live Calculated Average Score -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Overall Self-Score</span>
                        <div class="stat-icon-wrapper rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-star fs-7"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-1">
                        <h3 class="h2 fw-bold text-body-emphasis mb-0" id="displayAvgScore">0.0</h3>
                        <span class="fs-7 text-body-secondary fw-medium">/ 5.0</span>
                    </div>
                    <div class="d-flex align-items-center gap-1" id="starIconsContainer">
                        <i class="fa-solid fa-star fs-9 text-muted opacity-50"></i>
                        <i class="fa-solid fa-star fs-9 text-muted opacity-50"></i>
                        <i class="fa-solid fa-star fs-9 text-muted opacity-50"></i>
                        <i class="fa-solid fa-star fs-9 text-muted opacity-50"></i>
                        <i class="fa-solid fa-star fs-9 text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluation Progress -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Assessment Progress</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-list-check fs-7"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-1 mb-2">
                        <h3 class="h2 fw-bold text-body-emphasis mb-0" id="displayProgressCount">0</h3>
                        <span class="fs-7 text-body-secondary fw-medium">/ 5 Competencies</span>
                    </div>
                    <div class="progress rounded-pill bg-body-tertiary" style="height: 6px;">
                        <div class="progress-bar bg-primary transition-all rounded-pill" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Classification Tier -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Preliminary Tier</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-award fs-7"></i>
                        </div>
                    </div>
                    <h4 class="h4 fw-bold text-body-emphasis mb-1" id="displayTierName">In Progress</h4>
                    <p class="fs-9 text-body-secondary mb-0" id="displayTierDesc">Select ratings below to determine tier</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Competencies Assessment Form Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body">
        <div class="card-header bg-transparent border-bottom py-3.5 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis">Core Competency Self-Evaluation</h5>
                </div>
                <span class="fs-9 text-body-secondary">Scale: 1 = Needs Improvement &bull; 5 = Exceptional</span>
            </div>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('my-portal.performance_feedback.store') }}" id="performanceFeedbackForm">
                @csrf
                <input type="hidden" name="form_id" value="1">

                @php
                    $competencies = [
                        1 => [
                            'category' => 'Technical & Delivery',
                            'icon' => 'fa-code',
                            'title' => 'Technical Expertise & Quality of Deliverables',
                            'hint' => 'Evaluate code quality, architecture adherence, system stability, bug density, and technical mastery shown across your projects.'
                        ],
                        2 => [
                            'category' => 'Team & Collaboration',
                            'icon' => 'fa-users',
                            'title' => 'Teamwork, Cross-Functional Alignment & Communication',
                            'hint' => 'How effectively do you partner with peers, communicate status, mentor teammates, and participate in sprint ceremonies?'
                        ],
                        3 => [
                            'category' => 'Execution & Reliability',
                            'icon' => 'fa-clock-rotate-left',
                            'title' => 'Punctuality, Sprint Commitments & Deadlines',
                            'hint' => 'Track your consistency in delivering milestones on schedule, honoring estimates, and adapting to shifting business priorities.'
                        ],
                        4 => [
                            'category' => 'Impact & Milestones',
                            'icon' => 'fa-trophy',
                            'title' => 'Key Accomplishments & Major Milestones',
                            'hint' => 'Highlight your biggest contributions, key client launches, system optimizations, or operational wins over the evaluation cycle.'
                        ],
                        5 => [
                            'category' => 'Growth & Development',
                            'icon' => 'fa-arrow-trend-up',
                            'title' => 'Professional Development & Next Growth Goals',
                            'hint' => 'Identify new tools, frameworks, leadership skills, or certifications you plan to conquer in the upcoming quarters.'
                        ]
                    ];

                    $ratingOptions = [
                        5 => ['label' => '5 - Exceptional', 'short' => 'Exceptional', 'color' => 'success'],
                        4 => ['label' => '4 - Exceeds Expectations', 'short' => 'Exceeds', 'color' => 'primary'],
                        3 => ['label' => '3 - Meets Expectations', 'short' => 'Meets', 'color' => 'info'],
                        2 => ['label' => '2 - Developing / Fair', 'short' => 'Fair', 'color' => 'warning'],
                        1 => ['label' => '1 - Needs Improvement', 'short' => 'Needs Work', 'color' => 'danger']
                    ];
                @endphp

                <div class="d-flex flex-column gap-4">
                    @foreach($competencies as $qId => $comp)
                        @php
                            $existing = $myAnswers[$qId] ?? null;
                            $savedRating = old("ratings.{$qId}", $existing?->rating ?? '');
                            $savedAnswer = old("answers.{$qId}", $existing?->answer ?? ($existing?->feedback ?? ''));
                        @endphp
                        <div class="competency-card border rounded-3 p-4 bg-body-tertiary transition-all" id="card_q_{{ $qId }}">
                            <!-- Competency Header -->
                            <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; min-width: 34px;">
                                        <i class="fa-solid {{ $comp['icon'] }} fs-7"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-body border text-body-secondary fs-9 fw-semibold px-2 py-0.5 rounded-pill">
                                                {{ $comp['category'] }}
                                            </span>
                                            <span class="fs-9 text-body-tertiary">#0{{ $loop->iteration }}</span>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0 mt-0.5 fs-7">{{ $comp['title'] }}</h6>
                                    </div>
                                </div>
                            </div>

                            <p class="text-body-secondary fs-8 mb-3">{{ $comp['hint'] }}</p>

                            <!-- Interactive Rating Selection (1-5 Level Pills) -->
                            <div class="mb-3">
                                <label class="form-label fs-9 fw-bold text-body-emphasis text-uppercase d-block mb-1.5">
                                    Select Self-Rating <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex flex-wrap gap-2 rating-pills-container" data-question-id="{{ $qId }}">
                                    @foreach($ratingOptions as $val => $opt)
                                        @php
                                            $isChecked = ((string)$savedRating === (string)$val);
                                        @endphp
                                        <label class="rating-pill-label border rounded-2 px-3 py-2 cursor-pointer transition-all d-flex align-items-center gap-2 bg-body {{ $isChecked ? 'selected-pill border-primary bg-primary-subtle text-primary fw-bold' : 'text-body-emphasis' }}" style="cursor: pointer;">
                                            <input type="radio" 
                                                   name="ratings[{{ $qId }}]" 
                                                   value="{{ $val }}" 
                                                   class="form-check-input m-0 rating-radio" 
                                                   {{ $isChecked ? 'checked' : '' }} 
                                                   required
                                                   onchange="handleRatingChange({{ $qId }}, this.value)">
                                            <span class="fs-8">{{ $opt['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Qualitative Answer Input -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fs-9 fw-bold text-body-emphasis text-uppercase mb-0">
                                        Evidence, Specific Examples & Comments <span class="text-danger">*</span>
                                    </label>
                                    <span class="fs-9 text-body-secondary char-counter" id="charCount_{{ $qId }}">0 / 500</span>
                                </div>
                                <textarea name="answers[{{ $qId }}]" 
                                          rows="3" 
                                          maxlength="500"
                                          class="form-control fs-8 bg-body text-body answer-textarea" 
                                          placeholder="Share specific deliverables, challenges overcome, metrics, or future growth ideas..." 
                                          required 
                                          oninput="updateCharCount(this, {{ $qId }})">{{ $savedAnswer }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer Actions (Rule 12 Compliant Submit Button) -->
                <div class="d-flex align-items-center justify-content-between pt-4 mt-4 border-top flex-wrap gap-3">
                    <div class="text-body-secondary fs-9">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> Your self-ratings will be securely reviewed by your assigned reporting manager.
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 py-2 transition-all hover-lift submit-loader" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-paper-plane me-1.5"></i> Save & Submit Self-Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.08) !important;
    }
    .rating-pill-label {
        border-color: var(--bs-border-color, #e2e8f0);
    }
    .rating-pill-label:hover {
        border-color: #3b82f6 !important;
        background-color: rgba(59, 130, 246, 0.05);
    }
    .rating-pill-label.selected-pill {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 1px #3b82f6;
    }
    [data-bs-theme="dark"] .rating-pill-label.selected-pill {
        background-color: rgba(59, 130, 246, 0.2) !important;
        color: #93c5fd !important;
    }
</style>
@endpush

@push('js')
<script>
    function updateCharCount(textarea, qId) {
        const counter = document.getElementById('charCount_' + qId);
        if (counter && textarea) {
            counter.textContent = textarea.value.length + ' / 500';
        }
    }

    function handleRatingChange(qId, val) {
        // Highlight active pill visually
        const container = document.querySelector('.rating-pills-container[data-question-id="' + qId + '"]');
        if (container) {
            container.querySelectorAll('.rating-pill-label').forEach(lbl => {
                const radio = lbl.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    lbl.classList.add('selected-pill', 'border-primary', 'bg-primary-subtle', 'text-primary', 'fw-bold');
                } else {
                    lbl.classList.remove('selected-pill', 'border-primary', 'bg-primary-subtle', 'text-primary', 'fw-bold');
                }
            });
        }
        recalculateScorecard();
    }

    function recalculateScorecard() {
        const radios = document.querySelectorAll('.rating-radio:checked');
        let total = 0;
        let count = radios.length;

        radios.forEach(r => {
            total += parseInt(r.value, 10) || 0;
        });

        const totalQuestions = 5;
        const avg = count > 0 ? (total / count).toFixed(1) : '0.0';

        // Update score display
        const displayAvg = document.getElementById('displayAvgScore');
        if (displayAvg) displayAvg.textContent = avg;

        // Update progress count & bar
        const displayCount = document.getElementById('displayProgressCount');
        if (displayCount) displayCount.textContent = count;

        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            const pct = Math.round((count / totalQuestions) * 100);
            progressBar.style.width = pct + '%';
            progressBar.setAttribute('aria-valuenow', pct);
        }

        // Update stars icons
        const starContainer = document.getElementById('starIconsContainer');
        if (starContainer) {
            const numericAvg = parseFloat(avg);
            let starHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (numericAvg >= i) {
                    starHtml += '<i class="fa-solid fa-star fs-9 text-warning"></i> ';
                } else if (numericAvg >= i - 0.5) {
                    starHtml += '<i class="fa-solid fa-star-half-stroke fs-9 text-warning"></i> ';
                } else {
                    starHtml += '<i class="fa-regular fa-star fs-9 text-body-tertiary"></i> ';
                }
            }
            starContainer.innerHTML = starHtml;
        }

        // Update tier classification
        const tierName = document.getElementById('displayTierName');
        const tierDesc = document.getElementById('displayTierDesc');
        if (tierName && tierDesc) {
            if (count === 0) {
                tierName.textContent = 'In Progress';
                tierDesc.textContent = 'Select ratings below to determine tier';
            } else {
                const numericAvg = parseFloat(avg);
                if (numericAvg >= 4.5) {
                    tierName.textContent = 'Exceptional';
                    tierName.className = 'h4 fw-bold text-success mb-1';
                    tierDesc.textContent = 'Role model across all competencies';
                } else if (numericAvg >= 3.8) {
                    tierName.textContent = 'Exceeds Expectations';
                    tierName.className = 'h4 fw-bold text-primary mb-1';
                    tierDesc.textContent = 'Consistently surpasses standard goals';
                } else if (numericAvg >= 2.8) {
                    tierName.textContent = 'Meets Expectations';
                    tierName.className = 'h4 fw-bold text-info mb-1';
                    tierDesc.textContent = 'Solid, dependable performance';
                } else if (numericAvg >= 2.0) {
                    tierName.textContent = 'Developing / Fair';
                    tierName.className = 'h4 fw-bold text-warning mb-1';
                    tierDesc.textContent = 'Opportunity for guided improvement';
                } else {
                    tierName.textContent = 'Needs Improvement';
                    tierName.className = 'h4 fw-bold text-danger mb-1';
                    tierDesc.textContent = 'Action plan recommended';
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initial character counters
        document.querySelectorAll('.answer-textarea').forEach((ta, idx) => {
            updateCharCount(ta, idx + 1);
        });

        // Initial scorecard calculation
        recalculateScorecard();
    });
</script>
@endpush
