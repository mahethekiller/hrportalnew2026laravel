@extends('layouts.app')

@section('title', 'Notification Email Templates')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Notification Email Templates</h1>
            <p class="text-body-secondary fs-7 mb-0">Configure automated email notification templates for leave approvals, support tickets, onboarding, announcements, and recruitment.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('smtp-profiles.index') }}" class="btn btn-light-primary btn-sm fw-semibold">
                <i class="fa-solid fa-server me-1"></i> SMTP Profiles & Routing
            </a>
            <a href="{{ route('email-logs.index') }}" class="btn btn-light-warning btn-sm fw-semibold">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Email Delivery Logs
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Dynamic Placeholders Helper Box -->
    <div class="card border-0 shadow-sm mb-4 bg-body-tertiary">
        <div class="card-body py-3">
            <div class="d-flex align-items-center mb-2">
                <i class="fa-solid fa-code text-info fs-5 me-2"></i>
                <h6 class="fw-bold mb-0 text-body-emphasis">Available Dynamic Template Placeholders</h6>
            </div>
            <div class="d-flex flex-wrap gap-2 fs-8">
                <span class="badge bg-body border text-body font-monospace">{employee_name}</span>
                <span class="badge bg-body border text-body font-monospace">{employee_email}</span>
                <span class="badge bg-body border text-body font-monospace">{company_name}</span>
                <span class="badge bg-body border text-body font-monospace">{leave_type}</span>
                <span class="badge bg-body border text-body font-monospace">{start_date}</span>
                <span class="badge bg-body border text-body font-monospace">{end_date}</span>
                <span class="badge bg-body border text-body font-monospace">{ticket_code}</span>
                <span class="badge bg-body border text-body font-monospace">{action_url}</span>
                <span class="badge bg-body border text-body font-monospace">{site_name}</span>
            </div>
        </div>
    </div>

    <!-- Email Templates Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 25%;">Template Name</th>
                            <th style="width: 15%;">Template Code</th>
                            <th style="width: 35%;">Email Subject</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 15%;" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $tpl)
                            <tr>
                                <td class="ps-4 fw-bold text-body-emphasis">
                                    <i class="fa-solid fa-envelope-open-text me-2 text-primary"></i> {{ $tpl->name }}
                                </td>
                                <td>
                                    <span class="badge bg-body border text-body font-monospace fs-9">{{ $tpl->template_code }}</span>
                                </td>
                                <td class="fs-8 text-body-emphasis fw-medium">
                                    {{ $tpl->subject }}
                                </td>
                                <td>
                                    <span class="badge {{ $tpl->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $tpl->status == 1 ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" data-bs-toggle="modal" data-bs-target="#editTemplateModal{{ $tpl->template_id }}">
                                        <i class="fa-solid fa-pen me-1"></i> Edit
                                    </button>

                                    <!-- Modal: Edit Template -->
                                    <div class="modal fade text-start" id="editTemplateModal{{ $tpl->template_id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <form method="POST" action="{{ route('email-templates.update', $tpl->template_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-2 text-primary"></i> Edit Template: {{ $tpl->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3 mb-3">
                                                            <div class="col-md-8">
                                                                <label class="form-label fs-8 fw-semibold">Template Name</label>
                                                                <input type="text" class="form-control form-control-sm bg-body-tertiary" readonly value="{{ $tpl->name }}">
                                                            </div>
                                                            <div class="col-md-4 d-flex align-items-end">
                                                                <div class="form-check form-switch mb-1">
                                                                    <input class="form-check-input" type="checkbox" name="status" id="status_{{ $tpl->template_id }}" {{ $tpl->status == 1 ? 'checked' : '' }}>
                                                                    <label class="form-check-label fs-8 fw-bold" for="status_{{ $tpl->template_id }}">Enable Template</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Email Subject <span class="text-danger">*</span></label>
                                                            <input type="text" name="subject" class="form-control form-control-sm" required value="{{ $tpl->subject }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Email Body HTML / Plain Content <span class="text-danger">*</span></label>
                                                            <textarea name="message" class="form-control form-control-sm font-monospace" rows="8" required>{{ $tpl->message }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Update Template</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-envelope-open-text fs-2 mb-2 d-block text-muted opacity-50"></i>
                                    No notification email templates configured.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
