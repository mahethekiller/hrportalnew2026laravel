@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-door-open me-2 text-primary"></i> Book Conference Room & Meetings</h4>
        <p class="text-muted fs-8 mb-0">Reserve conference rooms, schedule client sessions, and view room availability.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#meetingModal">
            <i class="fa-solid fa-plus me-1"></i> Book Conference Room
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show fs-8" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header border-0 pt-3 bg-white">
        <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-calendar-days me-2 text-warning"></i> Scheduled Room Bookings</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Meeting Topic</th>
                        <th>Conference Room</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $mtg)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">{{ $mtg->meeting_title }}</td>
                            <td><span class="badge bg-light-primary text-primary border">{{ $mtg->room_name }}</span></td>
                            <td>{{ $mtg->meeting_date }}</td>
                            <td>{{ $mtg->meeting_time }}</td>
                            <td>{{ Str::limit($mtg->note, 40) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No conference room bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('my-portal.meetings.store') }}">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-gray-900">Book Conference Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Meeting Topic / Title <span class="text-danger">*</span></label>
                        <input type="text" name="meeting_title" class="form-control form-control-sm" required placeholder="e.g. Q3 Sprint Planning">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Conference Room <span class="text-danger">*</span></label>
                        <select name="room_name" class="form-select form-select-sm" required>
                            <option value="Executive Boardroom A">Executive Boardroom A (16 Seats)</option>
                            <option value="Innovation Lab B">Innovation Lab B (10 Seats)</option>
                            <option value="Scrum Huddle Room 1">Scrum Huddle Room 1 (6 Seats)</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="meeting_date" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Time Slot <span class="text-danger">*</span></label>
                            <input type="time" name="meeting_time" class="form-control form-control-sm" required value="10:00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Notes / Agenda (Optional)</label>
                        <textarea name="note" rows="2" class="form-control form-control-sm" placeholder="Meeting agenda or required AV equipment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Reserve Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
