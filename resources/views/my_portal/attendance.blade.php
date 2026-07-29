@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-clock me-2 text-primary"></i> My Attendance & Time Logs</h4>
        <p class="text-body-secondary fs-8 mb-0">Daily clock-in/out logs synced with card no: <span class="badge bg-body-secondary text-body-emphasis border fw-bold">{{ $employee?->card_no ?? 'N/A' }}</span></p>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('my-portal.attendance') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fs-8 fw-bold text-body-emphasis mb-1">From Date</label>
                <input type="date" name="from_date" class="form-control form-control-sm fs-8" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fs-8 fw-bold text-body-emphasis mb-1">To Date</label>
                <input type="date" name="to_date" class="form-control form-control-sm fs-8" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fs-8 fw-bold text-body-emphasis mb-1">Attendance Status</label>
                <select name="status" class="form-select form-select-sm fs-8">
                    <option value="">All Statuses</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm fs-8 fw-bold flex-fill">
                    <i class="fa-solid fa-filter me-1"></i> Apply Filter
                </button>
                @if(request()->hasAny(['from_date', 'to_date', 'status']))
                    <a href="{{ route('my-portal.attendance') }}" class="btn btn-light btn-sm fs-8 fw-bold">
                        <i class="fa-solid fa-xmark me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header border-0 pt-3 bg-body">
        <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-list me-2 text-primary"></i> Attendance Punch Records</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-body-tertiary">
                    <tr>
                        <th class="ps-4">Card No</th>
                        <th>Punch Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Badge Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceLogs as $log)
                        <tr>
                            <td class="ps-4 fw-bold text-body-emphasis">{{ $log->card_no }}</td>
                            <td>
                                @if($log->punch_date)
                                    <span class="badge bg-body-secondary text-body-emphasis border"><i class="fa-regular fa-calendar me-1"></i> {{ date('d M Y, D', strtotime($log->punch_date)) }}</span>
                                @else
                                    <span class="text-body-secondary">N/A</span>
                                @endif
                            </td>
                            <td class="text-success fw-semibold">
                                @if($log->check_in_time)
                                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> {{ date('h:i A', strtotime($log->check_in_time)) }}
                                @elseif($log->check_in_datetime)
                                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> {{ date('h:i A', strtotime($log->check_in_datetime)) }}
                                @else
                                    <span class="text-body-secondary">N/A</span>
                                @endif
                            </td>
                            <td class="text-danger fw-semibold">
                                @if($log->check_out_time)
                                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> {{ date('h:i A', strtotime($log->check_out_time)) }}
                                @elseif($log->check_out_datetime)
                                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> {{ date('h:i A', strtotime($log->check_out_datetime)) }}
                                @else
                                    <span class="text-body-secondary">N/A</span>
                                @endif
                            </td>
                            <td>{{ $log->badgenumber ?? 'N/A' }}</td>
                            <td>
                                @if($log->check_in_time || $log->check_in_datetime)
                                    <span class="badge bg-success-subtle text-success">Present</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-body-secondary">No attendance logs match your selected filter criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($attendanceLogs, 'links'))
            <div class="p-3 border-top">
                {{ $attendanceLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
