@extends('layouts.app')

@section('title', 'Email Delivery Audit Logs')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Email Delivery Audit Logs</h1>
            <p class="text-body-secondary fs-7 mb-0">Track all dispatched email notifications, recipient lists, modules, and message contents across the portal.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('smtp-profiles.index') }}" class="btn btn-light-primary btn-sm fw-semibold">
                <i class="fa-solid fa-server me-1"></i> SMTP Profiles & Routing
            </a>
            <a href="{{ route('email-templates.index') }}" class="btn btn-light-info btn-sm fw-semibold">
                <i class="fa-solid fa-envelope-open-text me-1"></i> Email Templates
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-4 bg-body-tertiary d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-clock-rotate-left text-warning me-2"></i> Sent Email History
            </h3>
            <span class="badge bg-primary fs-8">Total Records: {{ $logs->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%;">Dispatched Date</th>
                            <th style="width: 12%;">Module Category</th>
                            <th style="width: 25%;">Subject</th>
                            <th style="width: 20%;">Sender Email</th>
                            <th style="width: 20%;">Recipients (TO / CC)</th>
                            <th style="width: 8%;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="fs-8 text-body-secondary">
                                <i class="fa-regular fa-clock me-1"></i>
                                {{ $log->formatted_sent_date }}
                            </td>
                            <td>
                                <span class="badge bg-light-primary text-primary fw-bold text-uppercase fs-9">
                                    {{ $log->mail_type ?? 'System' }}
                                </span>
                            </td>
                            <td class="fw-semibold text-body-emphasis fs-7">
                                {{ Str::limit($log->subject, 45) }}
                            </td>
                            <td class="fs-8 text-body-secondary">
                                {{ $log->from_email }}
                            </td>
                            <td class="fs-8 text-body-secondary">
                                {{ Str::limit($log->to_emails, 50) }}
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light-info" data-bs-toggle="modal" data-bs-target="#viewLogModal" onclick="showLogDetails({{ json_encode(array_merge($log->toArray(), ['formatted_sent_date' => $log->formatted_sent_date])) }})">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted fs-7">
                                <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-muted opacity-50"></i>
                                No email logs recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
        <div class="card-footer bg-body-tertiary border-0 py-3">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: View Email Message Body -->
<div class="modal fade" id="viewLogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="logSubject">Email Content Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-body-tertiary rounded mb-3 fs-8">
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Sent At:</strong> <span id="logSentDate"></span></div>
                        <div class="col-md-6"><strong>Category:</strong> <span id="logCategory" class="badge bg-primary"></span></div>
                        <div class="col-md-6"><strong>From:</strong> <span id="logFrom"></span></div>
                        <div class="col-md-6"><strong>Recipients:</strong> <span id="logTo"></span></div>
                    </div>
                </div>

                <div class="border rounded p-3 bg-body" style="max-height: 400px; overflow-y: auto;">
                    <div id="logMessageBody"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showLogDetails(log) {
        document.getElementById('logSubject').innerText = log.subject || 'Email Details';
        document.getElementById('logSentDate').innerText = log.formatted_sent_date || log.sent_date || '';
        document.getElementById('logCategory').innerText = (log.mail_type || 'System').toUpperCase();
        document.getElementById('logFrom').innerText = log.from_email || '';
        document.getElementById('logTo').innerText = log.to_emails || '';

        document.getElementById('logMessageBody').innerHTML = log.message || '<em class="text-muted">No HTML message content</em>';
    }
</script>
@endsection
