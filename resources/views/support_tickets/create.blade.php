@extends('layouts.app')

@section('title', 'Open Support Ticket')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('support-tickets.index') }}" class="text-body-secondary text-decoration-none fs-8 fw-medium hover-text-primary">
                    <i class="fa-solid fa-headset me-1"></i> Helpdesk
                </a>
                <span class="text-body-tertiary fs-9">/</span>
                <span class="text-body-secondary fs-8 fw-medium">Create Request</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Open New Support Ticket</h1>
            <p class="text-body-secondary fs-7 mb-0">Submit an inquiry, technical malfunction report, or service request to company support teams.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('support-tickets.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Queue
            </a>
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

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-body">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-file-pen text-primary fs-6"></i>
                        <h5 class="mb-0 fw-bold text-body-emphasis fs-6">Ticket Parameters</h5>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('support-tickets.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Subject / Issue Summary <span class="text-danger">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="form-control fs-8 bg-body @error('subject') is-invalid @enderror" placeholder="e.g. VPN gateway unresponsive on Windows / Salary slip discrepancy" required>
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Rule 9: Searchable Select with Company Disambiguation -->
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Target Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-select form-select-sm select-search bg-body @error('department_id') is-invalid @enderror" data-control="select2" required>
                                    <option value="">Select Department...</option>
                                    @foreach($departments ?? [] as $dept)
                                        @php
                                            $compName = $dept->company->name ?? '';
                                            $deptLabel = $compName ? "{$dept->department_name} (Company: {$compName})" : $dept->department_name;
                                        @endphp
                                        <option value="{{ $dept->department_id ?? $dept->id }}" {{ old('department_id') == ($dept->department_id ?? $dept->id) ? 'selected' : '' }}>
                                            {{ $deptLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Ticket Priority <span class="text-danger">*</span></label>
                                <select name="ticket_priority" class="form-select form-select-sm select-search bg-body @error('ticket_priority') is-invalid @enderror" data-control="select2" required>
                                    <option value="low" {{ old('ticket_priority') === 'low' ? 'selected' : '' }}>Low - General question / Non-urgent</option>
                                    <option value="medium" {{ old('ticket_priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium - Normal business inquiry</option>
                                    <option value="high" {{ old('ticket_priority') === 'high' ? 'selected' : '' }}>High - Work obstructed</option>
                                    <option value="critical" {{ old('ticket_priority') === 'critical' ? 'selected' : '' }}>Critical - System / Service outage</option>
                                </select>
                                @error('ticket_priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Rule 6: Mandatory WYSIWYG Editor Component -->
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Detailed Description & Evidence <span class="text-danger">*</span></label>
                            <x-wysiwyg-editor name="description" :value="old('description')" height="240px" />
                            @error('description') <div class="text-danger fs-9 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Diagnostic Attachment / Screenshot (Optional)</label>
                            <input type="file" name="attachment" class="form-control fs-8 bg-body @error('attachment') is-invalid @enderror" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <span class="fs-9 text-body-secondary mt-1 d-block">Max file size: 5MB. Human-readable naming applied automatically on upload.</span>
                            @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner State -->
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <a href="{{ route('support-tickets.index') }}" class="btn btn-light btn-sm fw-semibold">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                                <i class="fa-solid fa-paper-plane me-1.5"></i> Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Guidelines & SLAs Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body mb-3">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-info text-primary fs-7"></i>
                        <h6 class="mb-0 fw-bold text-body-emphasis fs-7">Helpdesk SLA Guidelines</h6>
                    </div>
                </div>
                <div class="card-body p-4 fs-8 text-body-secondary">
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="badge bg-danger text-white rounded-pill px-2 py-0.5 fs-9">Critical</span>
                            <span class="fs-9 text-body-emphasis fw-bold">SLA: 2 Hours</span>
                        </div>
                        <p class="fs-9 text-body-secondary mb-0">For complete work blockers, server crashes, or payroll blocking errors.</p>
                    </div>

                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 fs-9">High</span>
                            <span class="fs-9 text-body-emphasis fw-bold">SLA: 6 Hours</span>
                        </div>
                        <p class="fs-9 text-body-secondary mb-0">Major functional issues where a temporary workaround is not available.</p>
                    </div>

                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-0.5 fs-9">Medium</span>
                            <span class="fs-9 text-body-emphasis fw-bold">SLA: 24 Hours</span>
                        </div>
                        <p class="fs-9 text-body-secondary mb-0">Standard business inquiries, software requests, and general questions.</p>
                    </div>

                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-0.5 fs-9">Low</span>
                            <span class="fs-9 text-body-emphasis fw-bold">SLA: 48 Hours</span>
                        </div>
                        <p class="fs-9 text-body-secondary mb-0">Minor enhancements, aesthetic questions, and non-blocking inquiries.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
