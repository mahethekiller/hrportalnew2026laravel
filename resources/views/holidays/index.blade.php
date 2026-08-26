@extends('layouts.app')

@section('title', 'Company Holiday Calendar')

@section('content')
@php
    $canManageHolidays = auth()->check() && (
        auth()->user()->can('edit.employees') || 
        auth()->user()->user_role_id == 1 || 
        in_array(strtolower(auth()->user()->roleRelation->role_name ?? ''), ['administrator', 'super admin', 'hr'])
    );
@endphp
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900"><i class="fa-solid fa-calendar-heart me-2 text-danger"></i> Corporate Holiday Calendar</h1>
            <p class="text-muted fs-7 mb-0">View official company holidays, festive observances, and scheduled non-working days.</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <!-- Year Selector Filter -->
            <form method="GET" action="{{ route('holidays.index') }}" class="d-inline">
                <select name="year" class="form-select form-select-sm fw-bold border-secondary text-primary" onchange="this.form.submit()">
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>Year {{ $yr }}</option>
                    @endforeach
                </select>
            </form>

            @if($canManageHolidays)
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createHolidayModal">
                    <i class="fa-solid fa-plus me-1"></i> Add New Holiday
                </button>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Holidays Table Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header border-0 pt-4 bg-body d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-list-check me-2 text-primary"></i> Official Holidays for {{ $year }}</h5>
            <span class="badge bg-light-danger text-danger fw-bold fs-8 px-3 py-2 rounded-pill">{{ count($holidays) }} Scheduled Holidays</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4">Actions</th>
                            <th>Holiday Name</th>
                            <th>Date / Duration</th>
                            <th>Day of Week</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holidays as $h)
                            @php
                                $startDate = \Carbon\Carbon::parse($h->start_date);
                                $endDate = \Carbon\Carbon::parse($h->end_date);
                                $isSingleDay = $startDate->isSameDay($endDate);
                            @endphp
                            <tr>
                                <td class="ps-4 text-nowrap">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        @if($canManageHolidays)
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-outline-warning px-2.5 rounded-2" data-bs-toggle="modal" data-bs-target="#editHolidayModal{{ $h->holiday_id }}" title="Edit Holiday">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('holidays.destroy', $h->holiday_id) }}" onsubmit="return confirm('Are you sure you want to delete this holiday?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Holiday">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted fs-9"><i class="fa-solid fa-lock"></i> View Only</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="fw-bold text-body-emphasis fs-7">
                                    <i class="fa-solid fa-star me-2 text-warning"></i> {{ $h->event_name }}
                                </td>
                                <td class="text-nowrap">
                                    @if($isSingleDay)
                                        <span class="badge bg-light-primary text-primary border px-2.5 py-1.5 fs-8">{{ $startDate->format('M d, Y') }}</span>
                                    @else
                                        <span class="badge bg-light-info text-info border px-2.5 py-1.5 fs-8">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</span>
                                    @endif
                                </td>
                                <td class="text-muted fw-semibold">
                                    {{ $startDate->format('l') }}
                                </td>
                                <td class="text-muted">{{ $h->description ?? 'Official Company Holiday' }}</td>
                            </tr>

                            @if($canManageHolidays)
                                <!-- Edit Holiday Modal -->
                                <div class="modal fade" id="editHolidayModal{{ $h->holiday_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form class="modal-content" method="POST" action="{{ route('holidays.update', $h->holiday_id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-gray-900"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Holiday: {{ $h->event_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body py-4">
                                                <div class="mb-3">
                                                    <label class="form-label fs-8 fw-semibold">Holiday Name / Occasion <span class="text-danger">*</span></label>
                                                    <input type="text" name="event_name" value="{{ old('event_name', $h->event_name) }}" class="form-control form-control-sm" required>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold">Start Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="start_date" value="{{ old('start_date', $h->start_date) }}" class="form-control form-control-sm" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-8 fw-semibold">End Date <span class="text-danger">*</span></label>
                                                        <input type="date" name="end_date" value="{{ old('end_date', $h->end_date) }}" class="form-control form-control-sm" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fs-8 fw-semibold">Description / Remarks</label>
                                                    <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description', $h->description) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning btn-sm px-4" onclick="submitWithLoader(this)">
                                                    <i class="fa-solid fa-check me-1"></i> Update Holiday
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-calendar-xmark fs-1 text-muted mb-3 d-block opacity-50"></i>
                                    No holidays published for Year {{ $year }}.
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
    <!-- Modal: Create New Holiday -->
    <div class="modal fade @if($errors->any()) show d-block @endif" id="createHolidayModal" tabindex="-1" aria-hidden="true" @if($errors->any()) style="background: rgba(0,0,0,0.5);" @endif>
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('holidays.store') }}">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-gray-900"><i class="fa-solid fa-calendar-plus text-primary me-2"></i> Add Official Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-4">
                    @if($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Holiday Name / Occasion <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" value="{{ old('event_name') }}" class="form-control form-control-sm @error('event_name') is-invalid @enderror" placeholder="e.g. Independence Day / Diwali" required>
                        @error('event_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="form-control form-control-sm @error('start_date') is-invalid @enderror" required>
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" class="form-control form-control-sm @error('end_date') is-invalid @enderror" required>
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Description / Remarks</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="e.g. Official National Holiday">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-plus me-1"></i> Save Holiday
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
