@extends('layouts.app')

@section('title', 'Office Shift Schedules')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Office Shift Roster</h1>
            <p class="text-muted fs-7 mb-0">Configure daily working hours and weekly office shifts.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendance.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Attendance Dashboard
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createShiftModal">
                <i class="fa-solid fa-plus me-1"></i> Create Office Shift
            </button>
        </div>
    </div>

    <!-- Shifts Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4"># ID</th>
                            <th>Shift Name</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#{{ $shift->office_shift_id }}</td>
                                <td>
                                    <div class="fw-bold text-gray-900">{{ $shift->shift_name }}</div>
                                    @if($shift->default_shift)
                                        <span class="badge badge-light-success fs-9">Default Shift</span>
                                    @endif
                                </td>
                                <td><span class="fs-9 font-monospace">{{ $shift->monday_in_time ?? '09:00' }} - {{ $shift->monday_out_time ?? '18:00' }}</span></td>
                                <td><span class="fs-9 font-monospace">{{ $shift->tuesday_in_time ?? '09:00' }} - {{ $shift->tuesday_out_time ?? '18:00' }}</span></td>
                                <td><span class="fs-9 font-monospace">{{ $shift->wednesday_in_time ?? '09:00' }} - {{ $shift->wednesday_out_time ?? '18:00' }}</span></td>
                                <td><span class="fs-9 font-monospace">{{ $shift->thursday_in_time ?? '09:00' }} - {{ $shift->thursday_out_time ?? '18:00' }}</span></td>
                                <td><span class="fs-9 font-monospace">{{ $shift->friday_in_time ?? '09:00' }} - {{ $shift->friday_out_time ?? '18:00' }}</span></td>
                                <td class="text-end pe-4">
                                    <form method="POST" action="{{ route('office-shifts.destroy', $shift->office_shift_id) }}" class="d-inline" onsubmit="return confirm('Delete shift {{ $shift->shift_name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-light-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-clock fs-2 mb-2 d-block text-muted"></i>
                                    No office shifts configured.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($shifts->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $shifts->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Create Shift -->
<div class="modal fade" id="createShiftModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('office-shifts.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Create Office Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Shift Name <span class="text-danger">*</span></label>
                        <input type="text" name="shift_name" class="form-control form-control-sm" required placeholder="e.g. Regular Day Shift (9 AM - 6 PM)">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fs-9 fw-semibold text-muted">In Time</label>
                            <input type="time" name="monday_in_time" class="form-control form-control-sm" value="09:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-9 fw-semibold text-muted">Out Time</label>
                            <input type="time" name="monday_out_time" class="form-control form-control-sm" value="18:00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Shift</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
