@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-7 col-md-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-relative p-4 p-md-5 bg-white">
                <!-- Top Decorative Banner -->
                <div class="position-absolute top-0 start-0 w-100 bg-gradient text-white py-2 fs-9 fw-bold text-uppercase tracking-wider" style="background: linear-gradient(90deg, #ff416c, #ff4b2b);">
                    <i class="fa-solid fa-shield-halved me-1"></i> Security Clearance Level: Restricted
                </div>

                <!-- Floating Funny Icon Illustration -->
                <div class="my-4 pt-3 position-relative">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-soft-danger text-danger p-4 shadow-sm" style="width: 120px; height: 120px; background-color: rgba(255, 65, 108, 0.1);">
                        <i class="fa-solid fa-user-ninja fa-4x text-danger animate-bounce"></i>
                    </div>
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger fs-8 px-3 py-2 shadow">
                        ERROR 403
                    </span>
                </div>

                <!-- Funny Headline & Content -->
                <h2 class="fw-bolder text-gray-900 mb-3 display-6">
                    Whoa there, Secret Agent! 🛑
                </h2>

                <p class="fs-6 text-muted mb-4 px-md-3">
                    @if(!empty($exception->getMessage()))
                        <span class="badge bg-light text-danger border border-danger-subtle fs-8 p-2 mb-2 d-inline-block">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $exception->getMessage() }}
                        </span>
                        <br>
                    @endif
                    You just stumbled upon a classified file vault! Unless you possess <strong>Level-99 HR Clearance</strong> (or brought fresh cookies for the System Admin), you aren't allowed to edit this employee's profile.
                </p>

                <div class="p-3 bg-light rounded-3 mb-4 text-start border border-dashed">
                    <div class="d-flex align-items-start gap-3">
                        <i class="fa-solid fa-lightbulb text-warning fs-4 mt-1"></i>
                        <div>
                            <strong class="text-gray-900 fs-8 d-block mb-1">What can you do now?</strong>
                            <ul class="mb-0 fs-9 text-muted ps-3">
                                <li>Double-check if you logged into the correct employee account.</li>
                                <li>If you really need access, submit a ticket requesting permission upgrade.</li>
                                <li>Or simply walk back to safety before the security bot notices! 🤖</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Interactive Action Buttons -->
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 pt-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-md rounded-pill px-4 fw-bold">
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
</style>
@endsection
