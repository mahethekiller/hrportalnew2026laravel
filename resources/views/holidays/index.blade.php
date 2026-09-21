@extends('layouts.app')

@section('title', 'Corporate Holiday Calendar ' . $year)

@section('content')
@php
    $canManageHolidays = auth()->check() && (
        auth()->user()->can('edit.employees') || 
        auth()->user()->user_role_id == 1 || 
        in_array(strtolower(auth()->user()->roleRelation->role_name ?? ''), ['administrator', 'super admin', 'hr'])
    );
    $todayStr = now()->toDateString();
@endphp

<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-regular fa-calendar-check me-1"></i> People & Governance
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Statutory Observances</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Corporate Holiday Calendar & Observances</h1>
            <p class="text-body-secondary fs-7 mb-0">Official schedule of gazetted public holidays, regional festivals, and restricted holidays (RH) for Year {{ $year }}.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Year Selector Filter -->
            <form method="GET" action="{{ route('holidays.index') }}" class="d-flex align-items-center gap-1.5 me-1">
                <label class="fs-9 fw-semibold text-body-secondary mb-0 text-nowrap">Calendar Year:</label>
                <select name="year" class="form-select form-select-sm select-search bg-body fw-bold text-primary" style="min-width: 130px;" onchange="this.form.submit()">
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>Year {{ $yr }}</option>
                    @endforeach
                </select>
            </form>

            @if($canManageHolidays)
                <button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#createHolidayModal">
                    <i class="fa-solid fa-plus me-1.5"></i> Add New Holiday
                </button>
            @endif
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
        <!-- Total Annual Holidays -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Total Holidays</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-calendar-days fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-emphasis mb-1">{{ $stats['total'] ?? count($holidays) }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Total scheduled days in {{ $year }}</p>
                </div>
            </div>
        </div>

        <!-- Gazetted / Mandatory Public Holidays -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Official / Gazetted</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-umbrella-beach fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-success mb-1">{{ $stats['mandatory'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Mandatory company-wide closures</p>
                </div>
            </div>
        </div>

        <!-- Restricted Holidays (RH) -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Restricted (RH)</span>
                        <div class="stat-icon-wrapper rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-hand-holding-heart fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-warning mb-1">{{ $stats['restricted'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Optional / elective observances</p>
                </div>
            </div>
        </div>

        <!-- Next Upcoming Holiday Spotlight -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Next Upcoming</span>
                        <div class="stat-icon-wrapper rounded-2 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-bell fs-7"></i>
                        </div>
                    </div>
                    @if(!empty($stats['upcoming']))
                        @php
                            $upStart = \Carbon\Carbon::parse($stats['upcoming']->start_date);
                            $diffDays = now()->startOfDay()->diffInDays($upStart->startOfDay(), false);
                        @endphp
                        <h5 class="fw-bold text-body-emphasis mb-0 text-truncate" title="{{ $stats['upcoming']->event_name }}">
                            {{ $stats['upcoming']->event_name }}
                        </h5>
                        <p class="fs-9 text-body-secondary mb-0">
                            {{ $upStart->format('M d') }} 
                            @if($diffDays >= 0)
                                <span class="badge bg-info-subtle text-info rounded-pill px-1.5 py-0.2 ms-1">in {{ $diffDays }} {{ Str::plural('day', $diffDays) }}</span>
                            @endif
                        </p>
                    @else
                        <h5 class="fw-bold text-body-secondary mb-0">No more upcoming</h5>
                        <p class="fs-9 text-body-secondary mb-0">All holidays in {{ $year }} concluded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Holidays Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
        <!-- Card Header with Filters & Search -->
        <div class="card-header bg-transparent border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis">Holiday Schedule & Observances</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 fs-9 fw-normal ms-1">
                        {{ count($holidays) }} Dates
                    </span>
                </div>

                <!-- Table Quick Category Filters -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="btn-group btn-group-sm" role="group" id="holidayCategoryTabs">
                        <button type="button" class="btn btn-outline-secondary active fw-semibold" data-hol-type="all">All</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-hol-type="mandatory">Official</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-hol-type="restricted">Restricted (RH)</button>
                    </div>
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <span class="input-group-text bg-body border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass fs-9"></i></span>
                        <input type="text" id="holidayTableSearch" class="form-control bg-body border-start-0 fs-8" placeholder="Search occasion...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8" id="holidayTable">
                    <thead class="table-light border-bottom">
                        <tr>
                            <!-- Rule 8: First-Column Icon-Only Actions -->
                            <th class="ps-4" style="width: 110px;">Actions</th>
                            <th style="width: 280px;">Occasion / Festival</th>
                            <th style="width: 170px;">Date / Duration</th>
                            <th style="width: 140px;">Day of Week</th>
                            <th style="width: 150px;">Classification</th>
                            <th class="pe-4" style="width: 260px;">Observance Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holidays as $h)
                            @php
                                $hid = $h->holiday_id ?? $h->id;
                                $startDate = \Carbon\Carbon::parse($h->start_date);
                                $endDate = \Carbon\Carbon::parse($h->end_date);
                                $isSingleDay = $startDate->isSameDay($endDate);
                                $isPast = $startDate->endOfDay()->isPast();
                                $isToday = $startDate->isToday();

                                $isRestricted = str_contains(strtolower($h->event_name . ' ' . $h->description), 'restricted') || str_contains(strtolower($h->event_name), '(rh)');
                                $categoryType = $isRestricted ? 'restricted' : 'mandatory';
                            @endphp
                            <tr class="holiday-row transition-colors {{ $isToday ? 'bg-primary-subtle' : '' }}" data-hol-type="{{ $categoryType }}">
                                <!-- Rule 8: First-Column Icon-Only Actions with explicit 6px spacing -->
                                <td class="ps-4 text-nowrap">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- Quick View Modal Trigger -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewHolidayModal{{ $hid }}" 
                                                title="View Holiday Details">
                                            <i class="fa-solid fa-eye fs-8"></i>
                                        </button>

                                        @if($canManageHolidays)
                                            <!-- Edit Holiday Trigger -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning px-2.5 rounded-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editHolidayModal{{ $hid }}" 
                                                    title="Edit Holiday">
                                                <i class="fa-solid fa-pen-to-square fs-8"></i>
                                            </button>

                                            <!-- Delete Holiday Form -->
                                            <form method="POST" action="{{ route('holidays.destroy', $hid) }}" onsubmit="return confirm('Are you sure you want to delete this holiday?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Holiday">
                                                    <i class="fa-solid fa-trash-can fs-8"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                                <!-- Occasion / Festival Name -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center {{ $isRestricted ? 'bg-warning-subtle text-warning' : 'bg-primary-subtle text-primary' }}" style="width: 32px; height: 32px; min-width: 32px;">
                                            @if($isRestricted)
                                                <i class="fa-solid fa-hand-holding-heart fs-8"></i>
                                            @else
                                                <i class="fa-solid fa-star fs-8"></i>
                                            @endif
                                        </div>
                                        <div class="overflow-hidden">
                                            <span class="fw-bold text-body-emphasis d-block text-truncate" title="{{ $h->event_name }}">
                                                {{ $h->event_name }}
                                            </span>
                                            @if($isToday)
                                                <span class="badge bg-success text-white rounded-pill px-2 py-0.2 fs-9">Today</span>
                                            @elseif(!$isPast && $startDate->diffInDays(now()) <= 14)
                                                <span class="badge bg-info-subtle text-info rounded-pill px-1.5 py-0.2 fs-9">Coming soon</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Date / Duration Badge -->
                                <td class="text-nowrap">
                                    @if($isSingleDay)
                                        <span class="badge bg-body-tertiary text-body-emphasis border rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-regular fa-calendar me-1 text-primary"></i>
                                            {{ $startDate->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="badge bg-body-tertiary text-body-emphasis border rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-regular fa-calendar-days me-1 text-primary"></i>
                                            {{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Day of Week -->
                                <td>
                                    <span class="text-body-secondary fs-8 fw-medium">
                                        {{ $startDate->format('l') }}
                                    </span>
                                </td>

                                <!-- Classification (Mandatory vs Restricted) -->
                                <td>
                                    @if($isRestricted)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-hand-holding-heart me-1"></i> Restricted (RH)
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-check-double me-1"></i> Gazetted Holiday
                                        </span>
                                    @endif
                                </td>

                                <!-- Description / Remarks -->
                                <td class="pe-4">
                                    <span class="text-body-secondary fs-9 d-block text-truncate" style="max-width: 250px;" title="{{ $h->description ?? 'Official company non-working day' }}">
                                        {{ $h->description ?: 'Official company holiday observance' }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Quick View Holiday Modal -->
                            <div class="modal fade" id="viewHolidayModal{{ $hid }}" tabindex="-1" aria-labelledby="viewHolidayModalLabel{{ $hid }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content border-0 shadow-lg bg-body">
                                        <div class="modal-header border-bottom py-3 px-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-2 p-2 {{ $isRestricted ? 'bg-warning-subtle text-warning' : 'bg-primary-subtle text-primary' }}">
                                                    <i class="fa-solid {{ $isRestricted ? 'fa-hand-holding-heart' : 'fa-star' }} fs-6"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis fs-7 mb-0">Holiday Details</h5>
                                                    <span class="fs-9 text-body-secondary">{{ $startDate->format('l, F d, Y') }}</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <span class="fs-9 text-body-secondary fw-semibold text-uppercase d-block mb-1">Occasion / Festival</span>
                                                <h5 class="fw-bold text-body-emphasis mb-0">{{ $h->event_name }}</h5>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Classification</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">{{ $isRestricted ? 'Restricted Holiday (RH)' : 'Official Gazetted' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Day</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">{{ $startDate->format('l') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Start Date</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">{{ $startDate->format('M d, Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">End Date</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">{{ $endDate->format('M d, Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 rounded-2 bg-body-tertiary border mb-3">
                                                <span class="fs-9 text-body-secondary fw-medium d-block mb-1">Description & Guidelines</span>
                                                <p class="fs-8 text-body-emphasis mb-0 leading-relaxed">
                                                    {{ $h->description ?: 'This day is an official non-working day observed across company operations. Restricted holidays can be opted for based on individual eligibility in the leave portal.' }}
                                                </p>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between pt-2">
                                                <span class="fs-9 text-body-secondary">Applicable Entity: {{ $h->company->name ?? 'All Entities' }}</span>
                                                <button type="button" class="btn btn-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($canManageHolidays)
                                <!-- Edit Holiday Modal -->
                                <div class="modal fade" id="editHolidayModal{{ $hid }}" tabindex="-1" aria-labelledby="editHolidayModalLabel{{ $hid }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <form class="modal-content border-0 shadow-lg bg-body" method="POST" action="{{ route('holidays.update', $hid) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-bottom py-3 px-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-2 bg-warning-subtle text-warning p-2">
                                                        <i class="fa-solid fa-pen-to-square fs-6"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="modal-title fw-bold text-body-emphasis fs-7 mb-0">Edit Holiday: {{ $h->event_name }}</h5>
                                                        <span class="fs-9 text-body-secondary">Modify schedule or occasion classification</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Holiday Name / Occasion <span class="text-danger">*</span></label>
                                                    <input type="text" name="event_name" value="{{ old('event_name', $h->event_name) }}" class="form-control fs-8 bg-body" required>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Start Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="start_date" value="{{ old('start_date', $h->start_date) }}" class="form-control fs-8 bg-body font-monospace" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">End Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="end_date" value="{{ old('end_date', $h->end_date) }}" class="form-control fs-8 bg-body font-monospace" required>
                                                    </div>
                                                </div>

                                                <!-- Rule 9 Searchable Select for Company -->
                                                <div class="mb-3">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Applicable Corporate Entity</label>
                                                    <select name="company_id" class="form-select form-select-sm select-search bg-body" data-control="select2">
                                                        <option value="0" {{ old('company_id', $h->company_id) == 0 ? 'selected' : '' }}>All Companies (Organization-Wide)</option>
                                                        @foreach($companies ?? [] as $comp)
                                                            <option value="{{ $comp->company_id }}" {{ old('company_id', $h->company_id) == $comp->company_id ? 'selected' : '' }}>
                                                                {{ $comp->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Description & Remarks</label>
                                                    <textarea name="description" class="form-control fs-8 bg-body" rows="3" placeholder="Optional notes, e.g. Subject to moon sighting...">{{ old('description', $h->description) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer border-top py-2.5 px-4 d-flex justify-content-between">
                                                <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                                                    <i class="fa-solid fa-check me-1"></i> Update Holiday
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                        <div class="rounded-circle bg-body-tertiary d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                            <i class="fa-solid fa-calendar-xmark fs-3 text-body-tertiary"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No holidays scheduled for {{ $year }}</h6>
                                        <p class="fs-8 text-body-secondary mb-0">No holiday events have been configured for the selected calendar year.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($canManageHolidays)
    <!-- Modal: Create New Holiday (Rules 9, 10, 11, 12) -->
    <div class="modal fade @if(isset($errors) && $errors->any()) show d-block @endif" id="createHolidayModal" tabindex="-1" aria-labelledby="createHolidayModalLabel" aria-hidden="true" @if(isset($errors) && $errors->any()) style="background: rgba(0,0,0,0.5);" @endif>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content border-0 shadow-lg bg-body" method="POST" action="{{ route('holidays.store') }}">
                @csrf
                <div class="modal-header border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-2 bg-primary-subtle text-primary p-2">
                            <i class="fa-solid fa-calendar-plus fs-6"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Add Corporate Holiday</h5>
                            <span class="fs-9 text-body-secondary">Configure official gazetted holidays or restricted festivals</span>
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
                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Holiday Name / Occasion <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" value="{{ old('event_name') }}" class="form-control fs-8 bg-body @error('event_name') is-invalid @enderror" placeholder="e.g. Diwali (Holiday) / Good Friday (Restricted Holiday)" required>
                        @error('event_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="form-control fs-8 bg-body font-monospace @error('start_date') is-invalid @enderror" required>
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" class="form-control fs-8 bg-body font-monospace @error('end_date') is-invalid @enderror" required>
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Rule 9 Searchable Select for Company -->
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Applicable Corporate Entity</label>
                        <select name="company_id" class="form-select form-select-sm select-search bg-body" data-control="select2">
                            <option value="0" {{ old('company_id', '0') == '0' ? 'selected' : '' }}>All Companies (Organization-Wide)</option>
                            @foreach($companies ?? [] as $comp)
                                <option value="{{ $comp->company_id }}" {{ old('company_id') == $comp->company_id ? 'selected' : '' }}>
                                    {{ $comp->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="fs-9 text-body-secondary mt-1 d-block">Select specific company or keep Organization-Wide for all entities.</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Description & Guidelines (Optional)</label>
                        <textarea name="description" class="form-control fs-8 bg-body" rows="3" placeholder="Additional observance details or restricted holiday guidelines...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner State -->
                <div class="modal-footer border-top py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-calendar-check me-1.5"></i> Save Holiday
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('holidayTableSearch');
    const tabButtons = document.querySelectorAll('#holidayCategoryTabs button');
    const tableRows = document.querySelectorAll('#holidayTable tbody tr.holiday-row');

    let currentType = 'all';

    function filterHolidays() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

        tableRows.forEach(row => {
            const rowType = row.getAttribute('data-hol-type') || 'mandatory';
            const textContent = row.textContent.toLowerCase();

            const matchesType = (currentType === 'all' || rowType === currentType);
            const matchesSearch = !query || textContent.includes(query);

            if (matchesType && matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterHolidays);
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentType = this.getAttribute('data-hol-type');
            filterHolidays();
        });
    });
});
</script>
@endpush

@endsection
