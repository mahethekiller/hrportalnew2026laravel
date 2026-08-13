<!-- Upcoming Birthdays Widget -->
<div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
    <div class="card-header border-0 bg-transparent pt-4 pb-0">
        <h5 class="fw-bold text-gray-900 fs-7 mb-0"><i class="fa-solid fa-cake-candles text-danger me-2"></i> Upcoming Birthdays</h5>
        <span class="text-muted fs-9">Next 30 days</span>
    </div>
    <div class="card-body">
        @forelse($upcomingBirthdays as $emp)
            @php
                $dob = \Carbon\Carbon::parse($emp->date_of_birth);
                $isToday = $dob->format('m-d') === today()->format('m-d');
            @endphp
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-light-danger text-danger p-2 rounded-circle fw-bold fs-9" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($emp->first_name, 0, 1)) }}{{ strtoupper(substr($emp->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="d-block fw-bold text-gray-900 fs-8">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                        <span class="fs-9 text-muted">{{ $emp->designation->designation_name ?? 'Staff Member' }}</span>
                    </div>
                </div>
                <div class="text-end">
                    @if($isToday)
                        <span class="badge bg-danger fs-9 animate-pulse"><i class="fa-solid fa-gift me-1"></i> Today!</span>
                    @else
                        <span class="badge bg-light-danger text-danger fs-9">{{ $dob->format('M d') }}</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted fs-8 mb-0 py-2">No birthdays in the next 30 days.</p>
        @endforelse
    </div>
</div>

<!-- Work Anniversaries Widget -->
<div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
    <div class="card-header border-0 bg-transparent pt-4 pb-0">
        <h5 class="fw-bold text-gray-900 fs-7 mb-0"><i class="fa-solid fa-medal text-warning me-2"></i> Work Anniversaries</h5>
        <span class="text-muted fs-9">Next 30 days</span>
    </div>
    <div class="card-body">
        @forelse($upcomingAnniversaries as $emp)
            @php
                $doj = \Carbon\Carbon::parse($emp->date_of_joining);
                $years = today()->year - $doj->year;
                $isToday = $doj->format('m-d') === today()->format('m-d');
            @endphp
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-light-warning text-warning p-2 rounded-circle fw-bold fs-9" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($emp->first_name, 0, 1)) }}{{ strtoupper(substr($emp->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="d-block fw-bold text-gray-900 fs-8">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                        <span class="fs-9 text-muted">{{ $years }} {{ Str::plural('year', $years) }} of service</span>
                    </div>
                </div>
                <div class="text-end">
                    @if($isToday)
                        <span class="badge bg-warning text-dark fs-9"><i class="fa-solid fa-award me-1"></i> Today!</span>
                    @else
                        <span class="badge bg-light-warning text-warning fs-9">{{ $doj->format('M d') }}</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted fs-8 mb-0 py-2">No work anniversaries in the next 30 days.</p>
        @endforelse
    </div>
</div>

<!-- Upcoming Holidays Widget -->
<div class="card border-0 shadow-sm rounded-3 bg-white">
    <div class="card-header border-0 bg-transparent pt-4 pb-0">
        <h5 class="fw-bold text-gray-900 fs-7 mb-0"><i class="fa-solid fa-umbrella-beach text-success me-2"></i> Upcoming Holidays</h5>
        <span class="text-muted fs-9">Official Calendar</span>
    </div>
    <div class="card-body">
        @forelse($upcomingHolidays as $hld)
            @php
                $start = \Carbon\Carbon::parse($hld->start_date);
                $diff = today()->diffInDays($start, false);
            @endphp
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <div>
                    <span class="d-block fw-bold text-gray-900 fs-8">{{ $hld->event_name }}</span>
                    <span class="fs-9 text-muted">{{ $start->format('l, M d, Y') }}</span>
                </div>
                <div class="text-end">
                    @if($diff === 0)
                        <span class="badge bg-success fs-9">Today</span>
                    @elseif($diff === 1)
                        <span class="badge bg-light-success text-success fs-9">Tomorrow</span>
                    @else
                        <span class="badge bg-light text-secondary fs-9">In {{ $diff }} days</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted fs-8 mb-0 py-2">No upcoming scheduled holidays.</p>
        @endforelse
    </div>
</div>
