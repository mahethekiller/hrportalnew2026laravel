@extends('layouts.app')

@section('title', 'Open HR Support Ticket')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('hr-tickets.index') }}" class="text-body-secondary text-decoration-none fs-8 fw-medium hover-text-primary">
                    <i class="fa-solid fa-ticket-simple me-1"></i> HR Support
                </a>
                <span class="text-body-tertiary fs-9">/</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-9 fw-semibold">
                    New Ticket
                </span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Open HR Support Ticket</h1>
            <p class="text-body-secondary fs-7 mb-0">Submit an internal inquiry or formal service request directly to the Human Resources department.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hr-tickets.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Queue
            </a>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-pen-to-square fs-7"></i>
                        </div>
                        <div>
                            <h5 class="card-title fw-bold text-body-emphasis mb-0 fs-7">Ticket Inquiry Details</h5>
                            <span class="fs-9 text-body-secondary">Fill in the fields below to file your HR inquiry</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center gap-2 py-2.5 px-3">
                            <i class="fa-solid fa-triangle-exclamation text-danger fs-6"></i>
                            <div class="fs-8 flex-grow-1">
                                <strong>Please review the form:</strong> {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hr-tickets.store') }}">
                        @csrf
                        <div class="row g-3 mb-4">
                            <!-- Subject Title -->
                            <div class="col-12">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Subject / Inquiry Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="subject" 
                                       class="form-control fs-8 @error('subject') is-invalid @enderror" 
                                       placeholder="e.g. Compensation Query / Leave Balance Adjustment / Policy Clarification" 
                                       value="{{ old('subject') }}" 
                                       required>
                                @error('subject')
                                    <div class="invalid-feedback fs-9">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Target Company (Rule 9: Searchable Select) -->
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Target Company Entity <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-select fs-8 select-search @error('company_id') is-invalid @enderror" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->company_id ?? $company->id }}" {{ old('company_id') == ($company->company_id ?? $company->id) ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback fs-9">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priority Level -->
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis">Priority Level <span class="text-danger">*</span></label>
                                <select name="ticket_priority" class="form-select fs-8 @error('ticket_priority') is-invalid @enderror" required>
                                    <option value="low" {{ old('ticket_priority') === 'low' ? 'selected' : '' }}>Low - General question / Non-urgent</option>
                                    <option value="medium" {{ old('ticket_priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium - Standard operational request</option>
                                    <option value="high" {{ old('ticket_priority') === 'high' ? 'selected' : '' }}>High - Urgent HR assistance required</option>
                                    <option value="critical" {{ old('ticket_priority') === 'critical' ? 'selected' : '' }}>Critical - Severe / Time-sensitive escalation</option>
                                </select>
                                @error('ticket_priority')
                                    <div class="invalid-feedback fs-9">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description (Rule 6: Mandatory WYSIWYG Editor) -->
                            <div class="col-12">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Detailed Description <span class="text-danger">*</span></label>
                                <p class="text-body-secondary fs-9 mb-2">Provide comprehensive details, relevant background, or attachments references.</p>
                                <x-wysiwyg-editor name="description" :value="old('description')" height="280px" />
                                @error('description')
                                    <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('hr-tickets.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                            <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner -->
                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                                <i class="fa-solid fa-paper-plane me-1.5"></i> Submit HR Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Context Information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-info text-primary fs-6"></i>
                        <h6 class="mb-0 fw-bold text-body-emphasis">Guidance & Tips</h6>
                    </div>
                </div>
                <div class="card-body p-4 fs-8 text-body-secondary line-height-base">
                    <h6 class="fw-bold text-body-emphasis fs-8 mb-2">What happens next?</h6>
                    <ul class="ps-3 mb-4 fs-9">
                        <li class="mb-1.5">Your ticket is assigned a unique reference code (`#HRTK-...`).</li>
                        <li class="mb-1.5">HR officers will review the inquiry and update resolution status.</li>
                        <li class="mb-1.5">You can monitor comments and resolution remarks in the live thread.</li>
                    </ul>

                    <h6 class="fw-bold text-body-emphasis fs-8 mb-2">Escalation Policy</h6>
                    <p class="fs-9 mb-0">For emergency payroll or medical insurance concerns, select <strong>Critical</strong> priority to expedite processing.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
