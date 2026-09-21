@extends('layouts.app')

@section('title', 'Support Tickets Helpdesk')

@section('content')
@php
    $currentStatus = request('status', 'all');
    $currentPriority = request('priority', 'all');
@endphp

<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-solid fa-headset me-1"></i> Employee Helpdesk
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Issue Resolution & Ticketing</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Support Tickets & Service Desk</h1>
            <p class="text-body-secondary fs-7 mb-0">Submit IT, HR, and facilities inquiries, track resolution progress, and collaborate in ticket threads.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                <i class="fa-solid fa-plus me-1.5"></i> Open New Ticket
            </button>
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

    <!-- Metric KPI Cards (Airy & Elevated: DENSITY: 3, MOTION: 4, VARIANCE: 6) -->
    <div class="row g-3 mb-4">
        <!-- Total Inquiries -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Total Inquiries</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-ticket fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-emphasis mb-1">{{ $stats['total'] ?? $tickets->total() }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Total logged helpdesk requests</p>
                </div>
            </div>
        </div>

        <!-- Open / Active -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Active & Open</span>
                        <div class="stat-icon-wrapper rounded-2 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-circle-exclamation fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-danger mb-1">{{ $stats['open'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Pending agent investigation</p>
                </div>
            </div>
        </div>

        <!-- On Hold / In Progress -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">On Hold</span>
                        <div class="stat-icon-wrapper rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-pause fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-warning mb-1">{{ $stats['on_hold'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Awaiting user info / 3rd party</p>
                </div>
            </div>
        </div>

        <!-- Resolved & Closed -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Resolved & Closed</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-circle-check fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-success mb-1">{{ $stats['closed'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Successfully completed tickets</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
        <!-- Card Header with Filters & Instant Search -->
        <div class="card-header bg-transparent border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-check text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis">Helpdesk Ticket Queue</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 fs-9 fw-normal ms-1">
                        {{ $tickets->total() }} Total
                    </span>
                </div>

                <!-- Table Filters -->
                <form method="GET" action="{{ route('support-tickets.index') }}" class="d-flex align-items-center gap-2 flex-wrap" id="ticketFilterForm">
                    <!-- Status Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('support-tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === 'all' ? 'active fw-semibold' : '' }}">
                            All
                        </a>
                        <a href="{{ route('support-tickets.index', array_merge(request()->except('status', 'page'), ['status' => '1'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === '1' ? 'active fw-semibold' : '' }}">
                            Open
                        </a>
                        <a href="{{ route('support-tickets.index', array_merge(request()->except('status', 'page'), ['status' => '3'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === '3' ? 'active fw-semibold' : '' }}">
                            On Hold
                        </a>
                        <a href="{{ route('support-tickets.index', array_merge(request()->except('status', 'page'), ['status' => '2'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === '2' ? 'active fw-semibold' : '' }}">
                            Closed
                        </a>
                    </div>

                    <!-- Priority Filter Dropdown -->
                    <select name="priority" class="form-select form-select-sm bg-body fs-8" style="width: 140px;" onchange="document.getElementById('ticketFilterForm').submit()">
                        <option value="all" {{ $currentPriority === 'all' ? 'selected' : '' }}>All Priorities</option>
                        <option value="low" {{ $currentPriority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $currentPriority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $currentPriority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ $currentPriority === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>

                    <!-- Search Input -->
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <span class="input-group-text bg-body border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass fs-9"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-body border-start-0 fs-8" placeholder="Search ticket / code...">
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8">
                    <thead class="table-light border-bottom">
                        <tr>
                            <!-- Rule 8: First-Column Icon-Only Actions -->
                            <th class="ps-4" style="width: 110px;">Actions</th>
                            <th style="width: 130px;">Ticket Code</th>
                            <th style="width: 280px;">Subject & Issue</th>
                            <th style="width: 180px;">Requester</th>
                            <th style="width: 170px;">Department</th>
                            <th style="width: 110px;">Priority</th>
                            <th style="width: 110px;">Status</th>
                            <th class="pe-4 text-end" style="width: 130px;">Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $tk)
                            @php
                                $tkId = $tk->ticket_id ?? $tk->id;
                                $requesterName = $tk->employee ? ($tk->employee->first_name . ' ' . $tk->employee->last_name) : 'System';
                                $statusStr = strval($tk->ticket_status);
                                $statusBadgeClass = match($statusStr) {
                                    '2' => 'bg-success-subtle text-success border border-success-subtle',
                                    '3' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                    default => 'bg-danger-subtle text-danger border border-danger-subtle'
                                };
                                $statusLabel = match($statusStr) {
                                    '2' => 'Closed',
                                    '3' => 'On Hold',
                                    default => 'Open'
                                };

                                $priorityStr = strtolower((string)$tk->ticket_priority);
                                $priorityBadgeClass = match($priorityStr) {
                                    '4', 'critical' => 'bg-danger text-white',
                                    '3', 'high' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    '2', 'medium' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                    default => 'bg-info-subtle text-info border border-info-subtle'
                                };
                                $priorityLabel = match($priorityStr) {
                                    '4', 'critical' => 'Critical',
                                    '3', 'high' => 'High',
                                    '2', 'medium' => 'Medium',
                                    default => 'Low'
                                };
                            @endphp
                            <tr class="transition-colors">
                                <!-- Rule 8: First-Column Icon-Only Actions with explicit 6px spacing -->
                                <td class="ps-4 text-nowrap">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- View Thread Action -->
                                        <a href="{{ route('support-tickets.show', $tkId) }}" 
                                           class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                           title="Open Ticket Conversation Thread">
                                            <i class="fa-solid fa-comments fs-8"></i>
                                        </a>

                                        <!-- Quick Dossier Modal Trigger -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#quickTicketModal{{ $tkId }}" 
                                                title="Quick Ticket Summary">
                                            <i class="fa-solid fa-eye fs-8"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Ticket Code -->
                                <td>
                                    <span class="font-monospace fw-bold text-primary fs-8">#{{ $tk->ticket_code }}</span>
                                </td>

                                <!-- Subject & Description Snippet -->
                                <td>
                                    <a href="{{ route('support-tickets.show', $tkId) }}" class="fw-bold text-body-emphasis text-decoration-none d-block text-truncate" style="max-width: 280px;" title="{{ $tk->clean_subject }}">
                                        {{ $tk->clean_subject }}
                                    </a>
                                    <span class="fs-9 text-body-secondary text-truncate d-block" style="max-width: 280px;">
                                        {{ Str::limit($tk->plain_description, 55) }}
                                    </span>
                                </td>

                                <!-- Requester -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-9" style="width: 28px; height: 28px; min-width: 28px;">
                                            {{ strtoupper(substr($requesterName, 0, 2)) }}
                                        </div>
                                        <span class="fw-medium text-body-emphasis text-truncate" style="max-width: 140px;">
                                            {{ $requesterName }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Target Department -->
                                <td>
                                    <span class="text-body-secondary fs-8">
                                        <i class="fa-solid fa-building-user me-1 text-muted"></i>
                                        {{ $tk->department ? $tk->department->department_name : 'General' }}
                                    </span>
                                </td>

                                <!-- Priority -->
                                <td>
                                    <span class="badge {{ $priorityBadgeClass }} rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                        {{ $priorityLabel }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    <span class="badge {{ $statusBadgeClass }} rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <!-- Created At -->
                                <td class="pe-4 text-end">
                                    <span class="text-body-secondary fs-9">
                                        <x-human-date :value="$tk->created_at" :time="true" />
                                    </span>
                                </td>
                            </tr>

                            <!-- Quick Ticket Dossier Modal -->
                            <div class="modal fade" id="quickTicketModal{{ $tkId }}" tabindex="-1" aria-labelledby="quickTicketModalLabel{{ $tkId }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content border-0 shadow-lg bg-body">
                                        <div class="modal-header border-bottom py-3 px-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-2 bg-primary-subtle text-primary p-2">
                                                    <i class="fa-solid fa-ticket fs-6"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis fs-7 mb-0">Ticket #{{ $tk->ticket_code }}</h5>
                                                    <span class="fs-9 text-body-secondary">Opened on <x-human-date :value="$tk->created_at" :time="true" /></span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <span class="fs-9 text-body-secondary fw-semibold text-uppercase d-block mb-1">Subject</span>
                                                <h6 class="fw-bold text-body-emphasis mb-0">{{ $tk->clean_subject }}</h6>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Department</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">{{ $tk->department ? $tk->department->department_name : 'General' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Priority</span>
                                                        <span class="badge {{ $priorityBadgeClass }} rounded-pill px-2 py-0.5 fs-9 fw-semibold">{{ $priorityLabel }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Status</span>
                                                        <span class="badge {{ $statusBadgeClass }} rounded-pill px-2 py-0.5 fs-9 fw-semibold">{{ $statusLabel }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Requester</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis text-truncate d-block">{{ $requesterName }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 rounded-2 bg-body-tertiary border mb-3">
                                                <span class="fs-9 text-body-secondary fw-medium d-block mb-1">Issue Description</span>
                                                <div class="fs-8 text-body-emphasis mb-0 leading-relaxed" style="max-height: 180px; overflow-y: auto;">
                                                    {!! $tk->clean_description !!}
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between pt-2">
                                                <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Close</button>
                                                <a href="{{ route('support-tickets.show', $tkId) }}" class="btn btn-primary btn-sm fw-semibold px-3">
                                                    <i class="fa-solid fa-comments me-1.5"></i> Enter Conversation Thread
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                        <div class="rounded-circle bg-body-tertiary d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                            <i class="fa-solid fa-headset fs-3 text-body-tertiary"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No support tickets found</h6>
                                        <p class="fs-8 text-body-secondary mb-0">No helpdesk requests match your selected status, priority, or search query.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tickets->hasPages())
            <div class="card-footer bg-transparent border-top py-3 px-4">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Open New Support Ticket (Rules 6, 9, 10, 11, 12) -->
<div class="modal fade @if(isset($errors) && $errors->any()) show d-block @endif" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true" @if(isset($errors) && $errors->any()) style="background: rgba(0,0,0,0.5);" @endif>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content border-0 shadow-lg bg-body" method="POST" action="{{ route('support-tickets.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-2 bg-primary-subtle text-primary p-2">
                        <i class="fa-solid fa-headset fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Open Support Ticket</h5>
                        <span class="fs-9 text-body-secondary">Submit an inquiry or service request to company support teams</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                        <ul class="mb-0 ps-3 fs-8">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Subject / Issue Summary <span class="text-danger">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="form-control fs-8 bg-body @error('subject') is-invalid @enderror" placeholder="e.g. Printer offline on Floor 3 / VPN configuration error" required>
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <!-- Rule 9: Searchable Select with Multi-Company Disambiguation -->
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

                    <!-- Priority Select -->
                    <div class="col-md-6">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Ticket Priority <span class="text-danger">*</span></label>
                        <select name="ticket_priority" class="form-select form-select-sm select-search bg-body @error('ticket_priority') is-invalid @enderror" data-control="select2" required>
                            <option value="low" {{ old('ticket_priority') === 'low' ? 'selected' : '' }}>Low - Minor inconvenience</option>
                            <option value="medium" {{ old('ticket_priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium - Normal business inquiry</option>
                            <option value="high" {{ old('ticket_priority') === 'high' ? 'selected' : '' }}>High - Work obstructed</option>
                            <option value="critical" {{ old('ticket_priority') === 'critical' ? 'selected' : '' }}>Critical - System/service down</option>
                        </select>
                        @error('ticket_priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Rule 6: Mandatory WYSIWYG Editor Component -->
                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Detailed Description & Evidence <span class="text-danger">*</span></label>
                    <x-wysiwyg-editor name="description" :value="old('description')" height="220px" />
                    @error('description') <div class="text-danger fs-9 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Screenshot / Diagnostic Attachment (Optional)</label>
                    <input type="file" name="attachment" class="form-control fs-8 bg-body @error('attachment') is-invalid @enderror" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                    <span class="fs-9 text-body-secondary mt-1 d-block">Max file size: 5MB. Human-readable naming applied automatically on upload.</span>
                    @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner State -->
            <div class="modal-footer border-top py-2.5 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                    <i class="fa-solid fa-paper-plane me-1.5"></i> Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
