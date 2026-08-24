@props([
    'user' => null,
    'roleName' => 'employee',
    'pendingApprovalsCount' => 0,
    'leaveBalance' => 0,
    'nextHoliday' => null,
    'candidateStallsCount' => 0,
    'hrPendingQueueCount' => 0,
    'systemIssuesCount' => 0,
])

@php
    $user = $user ?? Auth::user();
    $firstName = $user?->first_name ?? $user?->name ?? 'Team Member';
    
    $hour = date('H');
    $greeting = match(true) {
        $hour < 12 => 'Good morning',
        $hour < 17 => 'Good afternoon',
        default => 'Good evening',
    };

    $roleName = strtolower($roleName);
@endphp

<div class="card border-0 shadow-sm rounded-4 p-4 bg-body-tertiary mb-4 signature-my-day-card position-relative overflow-hidden">
    <div class="position-absolute top-0 end-0 opacity-10 p-3 d-none d-md-block pointer-events-none">
        <i class="fa-solid fa-sun-plant-wilt display-1 text-primary"></i>
    </div>

    <div class="row align-items-center g-3 position-relative z-1">
        <div class="col-lg-8">
            <h2 class="display-title font-serif text-body-emphasis mb-1">
                {{ $greeting }}, {{ $firstName }}.
            </h2>
            
            @if($roleName === 'super admin' || $roleName === 'superadmin' || $user?->is_super_admin)
                <p class="text-body-secondary fs-7 mb-3">
                    @if($systemIssuesCount > 0)
                        <span class="badge bg-warning-subtle text-warning fw-semibold px-2 py-1 me-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $systemIssuesCount }} System Alerts</span>
                        Attention required on webhooks, background jobs, or system health logs.
                    @else
                        All background workers and core services are running smoothly.
                    @endif
                </p>
            @elseif($roleName === 'recruiter')
                <p class="text-body-secondary fs-7 mb-3">
                    @if($candidateStallsCount > 0)
                        <span class="badge bg-danger-subtle text-danger fw-semibold px-2 py-1 me-1"><i class="fa-solid fa-hourglass-half me-1"></i>{{ $candidateStallsCount }} Stalled Candidates</span>
                        Candidates have been in the Interview stage for over 7 days.
                    @else
                        Your recruitment pipeline is moving nicely. No candidate stalls detected.
                    @endif
                </p>
            @elseif($roleName === 'manager')
                <p class="text-body-secondary fs-7 mb-3">
                    @if($pendingApprovalsCount > 0)
                        <span class="badge bg-warning-subtle text-warning fw-semibold px-2 py-1 me-1"><i class="fa-solid fa-clock me-1"></i>{{ $pendingApprovalsCount }} Approvals Waiting</span>
                        Team members have pending leave or profile change requests.
                    @else
                        Your team queue is clear. No pending leave or profile requests waiting.
                    @endif
                </p>
            @elseif($roleName === 'hr manager' || $roleName === 'hr')
                <p class="text-body-secondary fs-7 mb-3">
                    @if($hrPendingQueueCount > 0)
                        <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1 me-1"><i class="fa-solid fa-inbox me-1"></i>{{ $hrPendingQueueCount }} HR Queue Items</span>
                        Employee requests and tickets are awaiting review in today's queue.
                    @else
                        People Ops queue is clear today. All tickets and requests are answered.
                    @endif
                </p>
            @else
                <!-- Default Employee Persona -->
                <p class="text-body-secondary fs-7 mb-3">
                    You have <strong class="text-body-emphasis font-mono fw-bold">{{ $leaveBalance }} days</strong> of leave available. 
                    @if($nextHoliday)
                        Next holiday is <strong class="text-body-emphasis">{{ $nextHoliday->holiday_title ?? $nextHoliday->name ?? 'upcoming' }}</strong> on {{ \Carbon\Carbon::parse($nextHoliday->start_date ?? $nextHoliday->date ?? now())->format('d M') }}.
                    @endif
                </p>
            @endif

            <!-- Action Chips Stack -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('my-portal.leaves') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-xs">
                    <i class="fa-solid fa-calendar-plus me-1"></i> Apply for Leave
                </a>
                @if($roleName === 'manager' && $pendingApprovalsCount > 0)
                    <a href="{{ route('manager-portal.index') }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-xs text-dark">
                        <i class="fa-solid fa-check-double me-1"></i> Review Approvals ({{ $pendingApprovalsCount }})
                    </a>
                @endif
                @if(($roleName === 'recruiter' || $user?->is_super_admin) && Route::has('recruitment-applications.index'))
                    <a href="{{ route('recruitment-applications.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-network-wired me-1"></i> Open Pipeline
                    </a>
                @endif
                <a href="{{ route('my-portal.payslips') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-file-invoice-dollar me-1"></i> View Pay
                </a>
            </div>
        </div>

        <div class="col-lg-4 text-lg-end">
            <div class="d-inline-flex flex-column align-items-lg-end bg-body border border-subtle rounded-3 p-3 shadow-xs">
                <span class="fs-9 text-body-secondary text-uppercase fw-bold tracking-wider mb-1">Today's Date</span>
                <span class="fs-6 font-mono fw-bold text-body-emphasis">{{ date('l, d F Y') }}</span>
                <span class="badge bg-success-subtle text-success mt-2 fs-9 rounded-pill px-2 py-1">
                    <i class="fa-solid fa-circle me-1 fs-10"></i> Portal Online
                </span>
            </div>
        </div>
    </div>
</div>
