@extends('layouts.app')

@section('title', 'Webhook Subscriptions Manager')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Webhook Subscriptions Manager</h1>
            <p class="text-muted fs-7 mb-0">Subscribe HTTP endpoints to real-time portal lifecycle events (employee hire, leave request, payroll release).</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('api.docs') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-code me-1"></i> API Documentation
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWebhookModal">
                <i class="fa-solid fa-bolt me-1"></i> Subscribe New Webhook
            </button>
        </div>
    </div>

    <!-- Webhooks Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Subscribed Event</th>
                            <th>Target Endpoint URL</th>
                            <th>Signing Secret Key</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($webhooks as $wh)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge badge-light-primary font-monospace fs-8">{{ $wh->event_name }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-900 font-monospace">{{ Str::limit($wh->target_url, 45) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-secondary font-monospace fs-9">{{ substr($wh->secret_key ?? 'whsec_123', 0, 10) }}...</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $wh->created_at ?? '--' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $wh->status_badge_class }}">
                                        {{ ucfirst($wh->status ?? 'Active') }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <form method="POST" action="{{ route('webhooks.toggle', $wh->webhook_id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light-secondary py-1 px-2 fs-8">
                                            <i class="fa-solid fa-power-off me-1"></i> Toggle Status
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-bolt fs-2 mb-2 d-block text-muted"></i>
                                    No webhook subscriptions active.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Subscribe Webhook -->
<div class="modal fade" id="createWebhookModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('webhooks.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-bolt me-2 text-primary"></i> Subscribe New Webhook</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Event Type <span class="text-danger">*</span></label>
                        <select name="event_name" class="form-select form-select-sm" required>
                            <option value="employee.created">employee.created</option>
                            <option value="leave.requested">leave.requested</option>
                            <option value="leave.approved">leave.approved</option>
                            <option value="payroll.processed">payroll.processed</option>
                            <option value="job_application.received">job_application.received</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Target HTTP URL <span class="text-danger">*</span></label>
                        <input type="url" name="target_url" class="form-control form-control-sm" required placeholder="https://api.yourcompany.com/webhooks/hr">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Subscribe Webhook</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
