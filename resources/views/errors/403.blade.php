@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-7 col-md-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-relative p-4 p-md-5 error-card">
                <!-- Top Decorative Banner -->
                <div class="position-absolute top-0 start-0 w-100 bg-gradient text-white py-2 fs-9 fw-bold text-uppercase tracking-wider" style="background: linear-gradient(90deg, #ff416c, #ff4b2b);">
                    <i class="fa-solid fa-shield-halved me-1"></i> Security Clearance Level: Restricted
                </div>

                <!-- Floating Funny Icon Illustration -->
                <div class="my-4 pt-3 position-relative">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-4 shadow-sm" style="width: 120px; height: 120px; background-color: rgba(255, 65, 108, 0.15);">
                        <i class="fa-solid fa-user-ninja fa-4x text-danger animate-bounce"></i>
                    </div>
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger fs-8 px-3 py-2 shadow">
                        ERROR 403
                    </span>
                </div>

                <!-- Funny Headline & Content -->
                <h2 class="fw-bolder mb-3 display-6 card-heading">
                    Whoa there, Secret Agent! 🛑
                </h2>

                <div class="fs-6 mb-4 px-md-3 card-subtext">
                    @if(!empty($exception->getMessage()))
                        <div class="mb-3">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-8 p-2 text-wrap">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $exception->getMessage() }}
                            </span>
                        </div>
                    @endif
                    You just stumbled upon a classified file vault! Unless you possess <strong>Level-99 HR Clearance</strong> (or brought fresh cookies for the System Admin), you aren't allowed to edit this employee's profile.
                </div>

                <div class="p-3 help-box rounded-3 mb-4 text-start border border-dashed">
                    <div class="d-flex align-items-start gap-3">
                        <i class="fa-solid fa-lightbulb text-warning fs-4 mt-1"></i>
                        <div>
                            <strong class="help-title fs-8 d-block mb-1">What can you do now?</strong>
                            <ul class="mb-0 fs-9 help-text ps-3">
                                <li>Double-check if you logged into the correct employee account.</li>
                                <li>If you really need access, submit a ticket requesting permission upgrade.</li>
                                <li>Or simply walk back to safety before the security bot notices! 🤖</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Interactive Action Buttons -->
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 pt-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-md rounded-pill px-4 fw-bold action-btn">
                        <i class="fa-solid fa-arrow-left me-2"></i> Retrace Your Steps
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-md rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-house me-2"></i> Take Me Home
                    </a>
                    <a href="{{ route('support-tickets.create') }}" class="btn btn-warning text-dark btn-md rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-cookie-bite me-2"></i> Bribe Admin (Log Ticket)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes bounceSlow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
.animate-bounce {
    animation: bounceSlow 2s infinite ease-in-out;
}

/* Light Mode Defaults */
.error-card {
    background-color: #ffffff;
    color: #212529;
}
.card-heading {
    color: #1e293b;
}
.card-subtext {
    color: #475569;
}
.help-box {
    background-color: #f8fafc;
    border-color: #cbd5e1 !important;
}
.help-title {
    color: #0f172a;
}
.help-text {
    color: #475569;
}

/* Dark Mode Overrides */
[data-bs-theme="dark"] .error-card,
body.dark-mode .error-card,
.dark-mode .error-card,
.dark .error-card {
    background-color: #1e1e2d !important;
    color: #f8fafc !important;
}
[data-bs-theme="dark"] .card-heading,
body.dark-mode .card-heading,
.dark-mode .card-heading,
.dark .card-heading {
    color: #ffffff !important;
}
[data-bs-theme="dark"] .card-subtext,
body.dark-mode .card-subtext,
.dark-mode .card-subtext,
.dark .card-subtext {
    color: #cbd5e1 !important;
}
[data-bs-theme="dark"] .help-box,
body.dark-mode .help-box,
.dark-mode .help-box,
.dark .help-box {
    background-color: rgba(255, 255, 255, 0.06) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
}
[data-bs-theme="dark"] .help-title,
body.dark-mode .help-title,
.dark-mode .help-title,
.dark .help-title {
    color: #f8fafc !important;
}
[data-bs-theme="dark"] .help-text,
body.dark-mode .help-text,
.dark-mode .help-text,
.dark .help-text {
    color: #94a3b8 !important;
}
[data-bs-theme="dark"] .action-btn,
body.dark-mode .action-btn,
.dark-mode .action-btn,
.dark .action-btn {
    color: #e2e8f0 !important;
    border-color: #475569 !important;
}
</style>
@endsection
