<!-- Upcoming Birthdays Widget -->
<div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4 overflow-hidden">
    <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold text-body-emphasis fs-7 mb-0">
            <i class="fa-solid fa-cake-candles text-danger me-2"></i> This Month's Birthdays
        </h5>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-0.5 fs-9 font-monospace">
            {{ today()->format('F') }} ({{ $upcomingBirthdays->count() }})
        </span>
    </div>
    <div class="card-body px-4 pb-3 pt-1" @if($upcomingBirthdays->count() > 5) style="max-height: 340px; overflow-y: auto;" @endif>
        @forelse($upcomingBirthdays as $emp)
            @php
                $dob = \Carbon\Carbon::parse($emp->date_of_birth);
                $isToday = $dob->format('m-d') === today()->format('m-d');
                $isUpcoming = $dob->day > today()->day;
            @endphp
            <div class="d-flex align-items-center justify-content-between py-2.5 border-bottom border-subtle last-border-0">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="avatar-sm rounded-circle bg-danger-subtle text-danger fw-bold fs-9 border border-danger-subtle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                        {{ strtoupper(substr($emp->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($emp->last_name ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <span class="d-block fw-bold text-body-emphasis fs-8">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                        <span class="fs-9 text-body-secondary">{{ $emp->designation->designation_name ?? 'Team Member' }}</span>
                    </div>
                </div>
                <div class="text-end">
                    @if($isToday)
                        <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fs-9 shadow-xs animate-pulse">
                            <i class="fa-solid fa-gift me-1"></i> Today!
                        </span>
                    @elseif($isUpcoming)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 fs-9 font-monospace">
                            <i class="fa-solid fa-cake-candles me-1"></i> {{ $dob->format('M d') }}
                        </span>
                    @else
                        <span class="badge bg-body border border-subtle text-body-secondary rounded-pill px-2 py-0.5 fs-9 font-monospace">
                            {{ $dob->format('M d') }}
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-3 text-body-secondary fs-9">
                <i class="fa-solid fa-cake-candles fs-6 mb-1 text-danger opacity-50 d-block"></i>
                <span>No birthdays recorded this month.</span>
            </div>
        @endforelse
    </div>
</div>

<!-- Work Anniversaries Widget -->
<div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4 overflow-hidden">
    <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold text-body-emphasis fs-7 mb-0">
            <i class="fa-solid fa-medal text-warning me-2"></i> Work Anniversaries
        </h5>
        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-0.5 fs-9 font-monospace">
            {{ today()->format('F') }} ({{ $upcomingAnniversaries->count() }})
        </span>
    </div>
    <div class="card-body px-4 pb-3 pt-1" @if($upcomingAnniversaries->count() > 5) style="max-height: 340px; overflow-y: auto;" @endif>
        @forelse($upcomingAnniversaries as $emp)
            @php
                $doj = \Carbon\Carbon::parse($emp->date_of_joining);
                $years = today()->year - $doj->year;
                $isToday = $doj->format('m-d') === today()->format('m-d');
                $isUpcoming = $doj->day > today()->day;
            @endphp
            <div class="d-flex align-items-center justify-content-between py-2.5 border-bottom border-subtle last-border-0">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="avatar-sm rounded-circle bg-warning-subtle text-warning fw-bold fs-9 border border-warning-subtle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                        {{ strtoupper(substr($emp->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($emp->last_name ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <span class="d-block fw-bold text-body-emphasis fs-8">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                        <span class="badge bg-body border border-subtle text-body-secondary fs-9 px-1.5 py-0.5">
                            {{ max(1, $years) }} {{ Str::plural('year', max(1, $years)) }} milestone
                        </span>
                    </div>
                </div>
                <div class="text-end">
                    @if($isToday)
                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-9 shadow-xs animate-pulse">
                            <i class="fa-solid fa-award me-1"></i> Today!
                        </span>
                    @elseif($isUpcoming)
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-0.5 fs-9 font-monospace">
                            <i class="fa-solid fa-calendar me-1"></i> {{ $doj->format('M d') }}
                        </span>
                    @else
                        <span class="badge bg-body border border-subtle text-body-secondary rounded-pill px-2 py-0.5 fs-9 font-monospace">
                            {{ $doj->format('M d') }}
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-3 text-body-secondary fs-9">
                <i class="fa-solid fa-medal fs-6 mb-1 text-warning opacity-50 d-block"></i>
                <span>No work anniversaries recorded this month.</span>
            </div>
        @endforelse
    </div>
</div>

<!-- Upcoming Holidays Widget -->
<div class="card border-0 shadow-sm rounded-3 bg-body-tertiary overflow-hidden">
    <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold text-body-emphasis fs-7 mb-0">
            <i class="fa-solid fa-umbrella-beach text-success me-2"></i> Upcoming Holidays
        </h5>
        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-9">Official Calendar</span>
    </div>
    <div class="card-body px-4 pb-3 pt-1">
        @forelse($upcomingHolidays as $hld)
            @php
                $start = \Carbon\Carbon::parse($hld->start_date);
                $diff = today()->diffInDays($start, false);
            @endphp
            <div class="d-flex align-items-center justify-content-between py-2.5 border-bottom border-subtle last-border-0">
                <div class="d-flex align-items-center gap-2.5">
                    <!-- Date block tile -->
                    <div class="rounded-2 bg-body border border-subtle text-center py-1.5 px-1 d-flex flex-column justify-content-center align-items-center flex-shrink-0" style="width: 50px; min-width: 50px; height: 50px;">
                        <span class="d-block text-danger fw-bold text-uppercase font-monospace" style="font-size: 0.68rem; line-height: 1; letter-spacing: 0.5px; white-space: nowrap;">{{ $start->format('M') }}</span>
                        <span class="d-block text-body-emphasis fw-bolder fs-6" style="line-height: 1.2; white-space: nowrap;">{{ $start->format('d') }}</span>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-body-emphasis fs-8">{{ $hld->event_name }}</span>
                        <span class="fs-9 text-body-secondary">{{ $start->format('l, M d, Y') }}</span>
                    </div>
                </div>
                <div class="text-end">
                    @if($diff === 0)
                        <span class="badge bg-success text-white rounded-pill px-2.5 py-1 fs-9 shadow-xs">Today</span>
                    @elseif($diff === 1)
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-9">Tomorrow</span>
                    @else
                        <span class="badge bg-body border border-subtle text-body-secondary rounded-pill px-2 py-0.5 fs-9 font-monospace">In {{ $diff }} days</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-3 text-body-secondary fs-9">
                <i class="fa-solid fa-umbrella-beach fs-6 mb-1 text-success opacity-50 d-block"></i>
                <span>No scheduled official holidays.</span>
            </div>
        @endforelse
    </div>
</div>

