@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-star me-2 text-warning"></i> Performance Self-Rating & Feedback</h4>
        <p class="text-muted fs-8 mb-0">Evaluate your performance, rate core competencies, and submit feedback.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show fs-8" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3 col-lg-9">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('my-portal.performance_feedback.store') }}">
            @csrf
            
            <div class="mb-4 pb-3 border-bottom">
                <h5 class="fw-bold text-gray-900 mb-1">Annual Employee Self-Evaluation</h5>
                <p class="text-muted fs-8 mb-0">Rate your performance across key indicators (1 = Needs Improvement, 5 = Exceptional).</p>
            </div>

            @php
                $sampleQuestions = [
                    1 => 'Technical Expertise & Quality of Deliverables',
                    2 => 'Teamwork, Collaboration & Communication',
                    3 => 'Punctuality, Deadlines & Goal Achievement',
                    4 => 'Key Accomplishments & Major Milestones This Year',
                    5 => 'Professional Development & Growth Goals for Next Quarter'
                ];
            @endphp

            @foreach($sampleQuestions as $qId => $qText)
                @php
                    $existing = $myAnswers[$qId] ?? null;
                @endphp
                <div class="card border mb-3 p-3 bg-light">
                    <label class="form-label fs-8 fw-bold text-gray-900">{{ $loop->iteration }}. {{ $qText }}</label>
                    
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-sm-4">
                            <label class="form-label fs-9 text-muted mb-0">Self Rating (1-5)</label>
                            <select name="ratings[{{ $qId }}]" class="form-select form-select-sm" required>
                                <option value="5" {{ ($existing && $existing->rating == 5) ? 'selected' : '' }}>5 - Exceptional</option>
                                <option value="4" {{ ($existing && $existing->rating == 4) ? 'selected' : '' }}>4 - Exceeds Expectations</option>
                                <option value="3" {{ ($existing && $existing->rating == 3) ? 'selected' : '' }}>3 - Meets Expectations</option>
                                <option value="2" {{ ($existing && $existing->rating == 2) ? 'selected' : '' }}>2 - Fair</option>
                                <option value="1" {{ ($existing && $existing->rating == 1) ? 'selected' : '' }}>1 - Needs Improvement</option>
                            </select>
                        </div>
                    </div>

                    <textarea name="answers[{{ $qId }}]" rows="2" class="form-control form-control-sm" placeholder="Provide specific comments, examples, or notes..." required>{{ $existing->answer ?? '' }}</textarea>
                </div>
            @endforeach

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-sm fw-bold px-4">
                    <i class="fa-solid fa-paper-plane me-1"></i> Submit Self-Rating & Feedback
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
