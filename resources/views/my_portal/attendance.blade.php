@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-clock me-2 text-primary"></i> My Attendance & Time Logs</h4>
        <p class="text-muted fs-8 mb-0">Daily clock-in/out logs synced with card no: <span class="badge bg-light text-dark border fw-bold">{{ $employee?->card_no ?? 'N/A' }}</span></p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header border-0 pt-3 bg-white">
        <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-list me-2 text-primary"></i> Attendance Punch Records</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
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
                            <td class="ps-4 fw-bold text-gray-900">{{ $log->card_no }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $log->punch_date }}</span></td>
                            <td class="text-success fw-semibold"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i> {{ $log->check_in_time ?? $log->check_in_datetime ?? 'N/A' }}</td>
                            <td class="text-danger fw-semibold"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i> {{ $log->check_out_time ?? $log->check_out_datetime ?? 'N/A' }}</td>
                            <td>{{ $log->badgenumber ?? 'N/A' }}</td>
                            <td>
                                @if($log->check_in_time || $log->check_in_datetime)
                                    <span class="badge bg-soft-success text-success">Present</span>
                                @else
                                    <span class="badge bg-soft-secondary text-muted">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No attendance logs found for your card number.</td>
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
