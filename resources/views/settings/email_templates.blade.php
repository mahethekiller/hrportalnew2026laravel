@extends('layouts.app')

@section('title', 'Notification Email Templates')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Notification Email Templates</h1>
            <p class="text-muted fs-7 mb-0">Configure automated email notification templates for leave approvals, job applications, payslips, and training.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('system-settings.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-sliders me-1"></i> System Settings
            </a>
        </div>
    </div>

    <!-- Email Templates Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Template Name</th>
                            <th>Template Code</th>
                            <th>Email Subject</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $tpl)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900"><i class="fa-solid fa-envelope-open-text me-2 text-primary"></i>{{ $tpl->name }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-secondary font-monospace fs-9">{{ $tpl->template_code }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800 fw-medium">{{ $tpl->subject }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $tpl->status == 1 ? 'badge-light-success' : 'badge-light-danger' }}">
                                        {{ $tpl->status == 1 ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-light-primary py-1 px-2 fs-8" data-bs-toggle="modal" data-bs-target="#editTemplateModal{{ $tpl->template_id }}">
                                        <i class="fa-solid fa-pen me-1"></i> Edit Template
                                    </button>

                                    <!-- Modal: Edit Template -->
                                    <div class="modal fade text-start" id="editTemplateModal{{ $tpl->template_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <form method="POST" action="{{ route('email-templates.update', $tpl->template_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa-solid fa-pen me-2 text-primary"></i> Edit Email Template</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Template Name</label>
                                                            <input type="text" class="form-control form-control-sm bg-light" readonly value="{{ $tpl->name }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Email Subject <span class="text-danger">*</span></label>
                                                            <input type="text" name="subject" class="form-control form-control-sm" required value="{{ $tpl->subject }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fs-8 fw-semibold">Email Body HTML / Plain Content <span class="text-danger">*</span></label>
                                                            <textarea name="message" class="form-control form-control-sm font-monospace" rows="6" required>{{ $tpl->message }}</textarea>
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
                                    <i class="fa-solid fa-envelope-open-text fs-2 mb-2 d-block text-muted"></i>
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
