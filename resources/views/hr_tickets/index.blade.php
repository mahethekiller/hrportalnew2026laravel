@extends('layouts.app')

@section('title', 'HR Support Tickets')

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
                    <i class="fa-solid fa-ticket-simple me-1"></i> HR Service Desk
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Human Resources Inquiries & Requests</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">HR Support Tickets</h1>
            <p class="text-body-secondary fs-7 mb-0">Submit internal employee requests to HR, track real-time resolution progress, and review processing remarks.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#createHrTicketModal">
                <i class="fa-solid fa-plus me-1.5"></i> Open HR Ticket
            </button>
            <a href="{{ route('hr-tickets.create') }}" class="btn btn-outline-secondary btn-sm fw-medium px-3 py-2" title="Open Fullpage Form">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Full Form
            </a>
        </div>
    </div>

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
                    <p class="fs-9 text-body-secondary mb-0">Total logged HR support requests</p>
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
                            <i class="fa-solid fa-circle-dot fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-danger mb-1">{{ $stats['open'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Pending HR review & response</p>
                </div>
            </div>
        </div>

        <!-- On Hold -->
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
                    <p class="fs-9 text-body-secondary mb-0">Awaiting documentation or clarification</p>
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
                    <p class="fs-9 text-body-secondary mb-0">Successfully addressed cases</p>
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
                    <h5 class="mb-0 fw-bold text-body-emphasis">HR Ticket Queue</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 fs-9 fw-normal ms-1">
                        {{ $tickets->total() }} Total
                    </span>
                </div>

                <!-- Table Filters -->
                <form method="GET" action="{{ route('hr-tickets.index') }}" class="d-flex align-items-center gap-2 flex-wrap" id="hrTicketFilterForm">
                    <!-- Status Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" id="statusFilterTabs">
                        <a href="{{ route('hr-tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === 'all' ? 'active fw-semibold' : '' }}">
                            All
                        </a>
                        <a href="{{ route('hr-tickets.index', array_merge(request()->except('status', 'page'), ['status' => '1'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === '1' ? 'active fw-semibold' : '' }}">
                            Open
                        </a>
                        <a href="{{ route('hr-tickets.index', array_merge(request()->except('status', 'page'), ['status' => '3'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === '3' ? 'active fw-semibold' : '' }}">
                            On Hold
                        </a>
                        <a href="{{ route('hr-tickets.index', array_merge(request()->except('status', 'page'), ['status' => '2'])) }}" 
                           class="btn btn-outline-secondary {{ $currentStatus === '2' ? 'active fw-semibold' : '' }}">
                            Closed
                        </a>
                    </div>

                    <!-- Priority Select Filter -->
                    <select name="priority" class="form-select form-select-sm fs-9 w-auto" onchange="this.form.submit()">
                        <option value="all" {{ $currentPriority === 'all' ? 'selected' : '' }}>All Priorities</option>
                        <option value="low" {{ $currentPriority === 'low' ? 'selected' : '' }}>Low Priority</option>
                        <option value="medium" {{ $currentPriority === 'medium' ? 'selected' : '' }}>Medium Priority</option>
                        <option value="high" {{ $currentPriority === 'high' ? 'selected' : '' }}>High Priority</option>
                        <option value="critical" {{ $currentPriority === 'critical' ? 'selected' : '' }}>Critical Priority</option>
                    </select>

                    <!-- Client/Server Live Search Input -->
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-body-tertiary border-end-0 text-body-secondary"><i class="fa-solid fa-magnifying-glass fs-9"></i></span>
                        <input type="text" name="search" id="hrTicketTableSearch" class="form-control fs-9 border-start-0 ps-0" placeholder="Search ticket..." value="{{ request('search') }}">
                    </div>

                    @if(request()->hasAny(['status', 'priority', 'search']))
                        <a href="{{ route('hr-tickets.index') }}" class="btn btn-sm btn-outline-danger px-2" title="Reset All Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Table Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8">
                    <thead class="bg-body-tertiary text-body-secondary text-uppercase fs-9 fw-bold">
                        <tr>
                            <!-- Rule 8: Action Buttons in Column 1 -->
                            <th class="ps-4" style="width: 100px;">Actions</th>
                            <th style="width: 125px;">Ticket Ref</th>
                            <th style="min-width: 240px;">Subject & Issue Summary</th>
                            <th style="width: 180px;">Requested By</th>
                            <th style="width: 160px;">Company Entity</th>
                            <th style="width: 120px;">Priority</th>
                            <th style="width: 110px;">Status</th>
                            <th class="pe-4 text-end" style="width: 150px;">Logged On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $tk)
                            @php
                                $empName = $tk->employee ? $tk->employee->first_name . ' ' . $tk->employee->last_name : ($tk->created_by ?: 'System User');
                                $initials = strtoupper(substr($tk->employee?->first_name ?? 'U', 0, 1) . substr($tk->employee?->last_name ?? 'S', 0, 1));
                                $statusSlug = match(strval($tk->ticket_status)) {
                                    '2' => 'closed',
                                    '3' => 'on-hold',
                                    default => 'open'
                                };
                            @endphp
                            <tr class="hr-ticket-row transition-colors" data-status="{{ $statusSlug }}">
                                <!-- Rule 8: Column 1 Icon-Only Action Buttons with explicit 6px gap -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- Open Conversation Thread -->
                                        <a href="{{ route('hr-tickets.show', $tk->ticket_id) }}" 
                                           class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                           title="Open Ticket Thread">
                                            <i class="fa-solid fa-comments fs-8"></i>
                                        </a>

                                        <!-- Quick Dossier Modal Trigger -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-secondary px-2.5 rounded-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewHrTicketModal{{ $tk->ticket_id }}" 
                                                title="Quick Dossier">
                                            <i class="fa-solid fa-eye fs-8"></i>
                                        </button>

                                        <!-- HR Delete Action if Authorized -->
                                        @can('delete.hr_tickets')
                                            <form action="{{ route('hr-tickets.destroy', $tk->ticket_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this HR ticket?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Ticket">
                                                    <i class="fa-solid fa-trash-can fs-8"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                                <!-- Ticket Ref -->
                                <td>
                                    <span class="badge bg-body border text-body-emphasis font-monospace fs-9 fw-semibold px-2 py-1 shadow-xs">
                                        #{{ $tk->ticket_code }}
                                    </span>
                                </td>

                                <!-- Subject & Summary -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('hr-tickets.show', $tk->ticket_id) }}" class="fw-bold text-body-emphasis text-decoration-none hover-primary mb-0.5">
                                            {{ $tk->clean_subject }}
                                        </a>
                                        <div class="text-body-secondary fs-9 text-truncate" style="max-width: 320px;" title="{{ $tk->plain_description }}">
                                            {{ Str::limit($tk->plain_description, 55, '...') }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Requested By -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center fs-9 border border-primary-subtle" style="width: 28px; height: 28px; min-width: 28px;">
                                            {{ $initials }}
                                        </div>
                                        <div class="d-flex flex-column text-truncate">
                                            <span class="fw-semibold text-body-emphasis text-truncate">{{ $empName }}</span>
                                            <span class="fs-9 text-body-secondary">{{ $tk->employee?->email ?: 'Employee' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Company Entity -->
                                <td>
                                    <div class="d-flex align-items-center gap-1.5 text-body-secondary fs-9">
                                        <i class="fa-regular fa-building text-body-tertiary"></i>
                                        <span class="text-truncate" style="max-width: 140px;" title="{{ $tk->company?->name }}">
                                            {{ $tk->company?->name ?? 'Organization' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Priority -->
                                <td>
                                    {!! $tk->priority_badge !!}
                                </td>

                                <!-- Status -->
                                <td>
                                    {!! $tk->status_badge !!}
                                </td>

                                <!-- Logged On Date -->
                                <td class="pe-4 text-end">
                                    <span class="text-body-secondary fs-9">
                                        <x-human-date :value="$tk->created_at" :time="true" />
                                    </span>
                                </td>
                            </tr>

                            <!-- Quick Dossier Modal for Each Ticket (Rule 11: daisyUI / Bootstrap dialog standards) -->
                            <div class="modal fade" id="viewHrTicketModal{{ $tk->ticket_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg bg-body">
                                        <div class="modal-header border-bottom py-3 px-4">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    <i class="fa-solid fa-ticket-simple fs-6"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis mb-0">HR Ticket #{{ $tk->ticket_code }}</h5>
                                                    <span class="fs-9 text-body-secondary">Logged by {{ $empName }} &bull; <x-human-date :value="$tk->created_at" :time="true" /></span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 fs-8">
                                            <!-- Ticket Meta Strip -->
                                            <div class="row g-3 mb-4 p-3 rounded-3 bg-body-tertiary border">
                                                <div class="col-sm-3 col-6">
                                                    <span class="text-body-secondary fs-9 d-block fw-semibold text-uppercase">Status</span>
                                                    <div class="mt-1">{!! $tk->status_badge !!}</div>
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <span class="text-body-secondary fs-9 d-block fw-semibold text-uppercase">Priority</span>
                                                    <div class="mt-1">{!! $tk->priority_badge !!}</div>
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <span class="text-body-secondary fs-9 d-block fw-semibold text-uppercase">Company Entity</span>
                                                    <span class="fw-semibold text-body-emphasis d-block mt-1 text-truncate">{{ $tk->company?->name ?? 'Organization' }}</span>
                                                </div>
                                                <div class="col-sm-3 col-6">
                                                    <span class="text-body-secondary fs-9 d-block fw-semibold text-uppercase">Requester</span>
                                                    <span class="fw-semibold text-body-emphasis d-block mt-1 text-truncate">{{ $empName }}</span>
                                                </div>
                                            </div>

                                            <!-- Subject -->
                                            <div class="mb-3">
                                                <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Subject / Inquiry Title</label>
                                                <h6 class="fw-bold text-body-emphasis mb-0">{{ $tk->clean_subject }}</h6>
                                            </div>

                                            <!-- Description (Rule 6: clean rich text rendering) -->
                                            <div class="mb-4">
                                                <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Description</label>
                                                <div class="p-3 rounded-2 bg-body-tertiary border text-body-emphasis line-height-base fs-8">
                                                    {!! $tk->clean_description !!}
                                                </div>
                                            </div>

                                            <!-- Resolution Remarks if any -->
                                            @if(!empty($tk->remarks))
                                                <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle text-body-emphasis mb-3">
                                                    <h6 class="fw-bold text-primary mb-1 fs-9 text-uppercase">
                                                        <i class="fa-solid fa-comment-dots me-1"></i> HR Resolution Remarks
                                                    </h6>
                                                    <div class="fs-8 text-body-emphasis">{!! $tk->clean_remarks !!}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-top py-2.5 px-4 bg-body-tertiary d-flex justify-content-between">
                                            <span class="fs-9 text-body-secondary font-monospace">Ticket ID: {{ $tk->ticket_id }}</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                <a href="{{ route('hr-tickets.show', $tk->ticket_id) }}" class="btn btn-primary btn-sm fw-semibold">
                                                    <i class="fa-solid fa-comments me-1"></i> Open Full Thread
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="rounded-circle bg-body-tertiary p-3 mb-3 text-body-secondary">
                                            <i class="fa-solid fa-ticket-simple fs-2 text-primary"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No HR support tickets found</h6>
                                        <p class="text-body-secondary fs-8 mb-3" style="max-width: 320px;">There are no support tickets matching your current filter criteria.</p>
                                        <button type="button" class="btn btn-sm btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#createHrTicketModal">
                                            <i class="fa-solid fa-plus me-1"></i> Open First HR Ticket
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
                <div class="card-footer bg-transparent border-top py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="fs-9 text-body-secondary">
                        Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} entries
                    </span>
                    <div>
                        {{ $tickets->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- Create HR Ticket Modal (Rule 6, 9, 10, 11, 12) -->
<!-- ========================================== -->
<div class="modal fade" id="createHrTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg bg-body">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-plus fs-7"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-body-emphasis mb-0">Open HR Support Ticket</h5>
                        <span class="fs-9 text-body-secondary">Submit an inquiry or service request to the Human Resources department</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('hr-tickets.store') }}" method="POST" id="createHrTicketForm">
                @csrf
                <div class="modal-body p-4 fs-8">
                    <!-- Rule 10: In-Modal Error Alert Banner -->
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-3 d-flex align-items-center gap-2 py-2 px-3">
                            <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                            <div class="fs-9 flex-grow-1">
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
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

                        <!-- Priority -->
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
                            <p class="text-body-secondary fs-9 mb-2">Provide comprehensive details, employee IDs, dates, or context regarding your request.</p>
                            <x-wysiwyg-editor name="description" :value="old('description')" height="220px" />
                            @error('description')
                                <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-3 px-4 bg-body-tertiary d-flex justify-content-between align-items-center">
                    <span class="fs-9 text-body-secondary"><i class="fa-solid fa-shield-halved me-1"></i> Gated via HR Policy</span>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner -->
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3 submit-loader" onclick="submitWithLoader(this)">
                            <i class="fa-solid fa-paper-plane me-1"></i> Submit Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Client-side quick filter tabs fallback
        const filterTabs = document.querySelectorAll('#statusFilterTabs a');
        const rows = document.querySelectorAll('.hr-ticket-row');

        // Instant search filtering
        const searchInput = document.getElementById('hrTicketTableSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }

        // Rule 10: Auto-reopen modal if validation errors exist
        @if(isset($errors) && $errors->any())
            const createModalEl = document.getElementById('createHrTicketModal');
            if (createModalEl && typeof bootstrap !== 'undefined') {
                const modalInstance = new bootstrap.Modal(createModalEl);
                modalInstance.show();
            }
        @endif
    });
</script>
@endpush
@endsection
