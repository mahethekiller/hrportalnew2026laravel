@extends('layouts.guest')

@section('title', 'Employee Sign In - ' . ($systemSetting->application_name ?? 'HR Portal'))

@push('css')
<style>
    /* ==========================================================================
       Design-Taste-Frontend: Organization HR Portal Sign-in Sanctuary
       DIALS: VARIANCE=7, MOTION=5, DENSITY=4
       AESTHETIC: Precision Corporate HR Architecture x Warm Executive ESS
       ========================================================================== */

    .auth-split-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 992px) {
        .auth-split-wrapper {
            flex-direction: row;
        }
    }

    /* --- Left: Organization Brand & Culture Showcase --- */
    .auth-brand-pane {
        position: relative;
        background: linear-gradient(150deg, #070d18 0%, #0d1527 50%, #111e38 100%);
        color: #f8fafc;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 3rem 2.25rem;
    }

    @media (min-width: 1200px) {
        .auth-brand-pane {
            padding: 4.5rem 4rem;
        }
    }

    /* Subtle architectural mesh grid */
    .auth-brand-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(to right, rgba(255, 255, 255, 0.035) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
        background-size: 38px 38px;
        mask-image: radial-gradient(circle at 50% 45%, rgba(0, 0, 0, 0.9) 0%, transparent 85%);
        -webkit-mask-image: radial-gradient(circle at 50% 45%, rgba(0, 0, 0, 0.9) 0%, transparent 85%);
        pointer-events: none;
    }

    /* Ambient corporate glows */
    .auth-ambient-glow {
        position: absolute;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(30, 64, 175, 0.28) 0%, rgba(14, 116, 144, 0.14) 45%, transparent 70%);
        top: -100px;
        left: -100px;
        filter: blur(60px);
        pointer-events: none;
    }

    .auth-ambient-glow-secondary {
        position: absolute;
        width: 440px;
        height: 440px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.16) 0%, transparent 65%);
        bottom: -90px;
        right: -90px;
        filter: blur(55px);
        pointer-events: none;
    }

    /* Bento mini-cards for HR Portal features */
    .bento-pill {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.09);
        border-radius: 14px;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.22s ease, background 0.22s ease;
    }

    .bento-pill:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.07);
    }

    /* --- Right: Organization Staff Sign-In Card --- */
    .auth-form-pane {
        position: relative;
        background-color: var(--bs-body-bg);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem 1.5rem;
    }

    @media (min-width: 768px) {
        .auth-form-pane {
            padding: 4rem 3.5rem;
        }
    }

    .auth-card-box {
        width: 100%;
        max-width: 440px;
        animation: authFadeUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* Input Field Craft & Focus Illumination */
    .auth-input-group {
        position: relative;
        display: flex;
        align-items: stretch;
        border-radius: 10px;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .auth-input-group .input-icon {
        background-color: var(--bs-tertiary-bg, #f8fafc);
        border: 1px solid var(--bs-border-color, #e2e8f0);
        border-right: none;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        color: var(--bs-secondary-color, #64748b);
        display: flex;
        align-items: center;
        padding: 0 1rem;
        transition: color 0.2s ease, border-color 0.2s ease;
    }

    .auth-input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-radius: 0 10px 10px 0;
        border-color: var(--bs-border-color, #e2e8f0);
        padding: 0.78rem 1rem;
        font-size: 0.925rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .auth-input-group:focus-within .input-icon {
        border-color: var(--bs-primary, #1e40af);
        color: var(--bs-primary, #1e40af);
    }

    .auth-input-group:focus-within .form-control {
        border-color: var(--bs-primary, #1e40af);
        box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.15);
    }

    .password-toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        border: none;
        background: transparent;
        color: var(--bs-secondary-color, #64748b);
        padding: 4px 8px;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .password-toggle-btn:hover {
        color: var(--bs-body-color, #0f172a);
    }

    /* Spring-physics submit button */
    .btn-auth-submit {
        border-radius: 10px;
        padding: 0.85rem 1.5rem;
        font-weight: 600;
        letter-spacing: 0.015em;
        background: linear-gradient(135deg, var(--bs-primary, #1e40af) 0%, #1d4ed8 100%);
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(30, 64, 175, 0.32);
        transition: transform 0.15s ease, box-shadow 0.2s ease, filter 0.15s ease;
    }

    .btn-auth-submit:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30, 64, 175, 0.42);
        filter: brightness(1.04);
        color: #ffffff;
    }

    .btn-auth-submit:active:not(:disabled) {
        transform: scale(0.985);
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.3);
    }

    /* Live pulse dot */
    .live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseGreen 2.2s infinite;
    }

    @keyframes pulseGreen {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    @keyframes authFadeUp {
        from {
            opacity: 0;
            transform: translateY(14px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .auth-card-box, .live-dot, .bento-pill, .btn-auth-submit {
            animation: none !important;
            transition: none !important;
            transform: none !important;
        }
    }
</style>
@endpush

@section('custom_layout')
<div class="auth-split-wrapper">
    <!-- LEFT COLUMN: Organization Identity, Core HR Capabilities & Welcome Message -->
    <div class="auth-brand-pane col-lg-6 col-xl-7">
        <div class="auth-brand-grid"></div>
        <div class="auth-ambient-glow"></div>
        <div class="auth-ambient-glow-secondary"></div>

        <!-- Organization Portal Header -->
        <div class="position-relative z-2">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-3 p-1.5 shadow-sm d-flex align-items-center justify-content-center" style="height: 52px; padding: 6px 10px;">
                    <img src="{{ asset('assets/images/portal_logo_transparent.png') }}" alt="i2u2 HR Portal Logo" style="max-height: 40px; width: auto; object-fit: contain;">
                </div>
                <div>
                    <h5 class="fw-bold tracking-tight text-white mb-0 fs-5">
                        {{ $systemSetting->application_name ?? 'i2u2 HR Portal' }}
                    </h5>
                    <span class="fs-9 text-white-50 text-uppercase tracking-wider fw-semibold">
                        Human Resource Management &amp; Employee Self-Service
                    </span>
                </div>
            </div>
        </div>

        <!-- Mid Hero: Corporate HR Statement & Self-Service Pillars -->
        <div class="position-relative z-2 my-5 my-lg-0 py-3">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-10 text-white mb-4">
                <span class="live-dot"></span>
                <span class="fs-9 fw-semibold tracking-wide">INTERNAL HR PORTAL &bull; AUTHORIZED STAFF ACCESS</span>
            </div>

            <h1 class="display-6 fw-bold tracking-tight text-white mb-3" style="line-height: 1.28;">
                Your centralized workspace for employee services &amp; HR operations.
            </h1>
            <p class="text-white-50 fs-7 mb-4" style="max-width: 560px; line-height: 1.65;">
                Log in to submit leave requests, view monthly payslips, verify attendance logs, track separation clearances, and access organization policies in one unified system.
            </p>

            <!-- Bento Highlights Tailored Directly to HR Portal -->
            <div class="row g-3" style="max-width: 600px;">
                <div class="col-sm-6">
                    <div class="bento-pill p-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-id-card-clip text-primary fs-7"></i>
                            <span class="fs-8 fw-bold text-white">Employee Self-Service</span>
                        </div>
                        <p class="fs-9 text-white-50 mb-0">Apply for leaves, download payslips, submit tax declarations &amp; update profile.</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="bento-pill p-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-clipboard-check text-success fs-7"></i>
                            <span class="fs-8 fw-bold text-white">HR Operations Hub</span>
                        </div>
                        <p class="fs-9 text-white-50 mb-0">Biometric shift attendance, performance appraisals &amp; 4-stage separation clearance.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Corporate Trust & Organization Footer -->
        <div class="position-relative z-2 d-flex flex-wrap align-items-center justify-content-between gap-3 pt-4 border-top border-white border-opacity-10 text-white-50 fs-9">
            <div>
                <i class="fa-solid fa-shield-halved me-1 text-success"></i> Enterprise Role-Based Access Control (RBAC)
            </div>
            <div>
                &copy; {{ date('Y') }} {{ $systemSetting->application_name ?? 'Organization HR' }}. All rights reserved.
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Staff Sign-In Card -->
    <div class="auth-form-pane col-lg-6 col-xl-5">
        <!-- Floating Theme Mode Toggle -->
        <div class="position-absolute top-0 end-0 p-4">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 shadow-xs d-flex align-items-center gap-2" onclick="togglePortalTheme()" title="Toggle Light / Dark Mode">
                <i class="fa-solid fa-moon theme-icon-dark d-none"></i>
                <i class="fa-solid fa-sun theme-icon-light"></i>
                <span class="fs-9 fw-semibold">Appearance</span>
            </button>
        </div>

        <div class="auth-card-box">
            <!-- Organization Brand Logo -->
            <div class="mb-4 text-start">
                <a href="/" class="d-inline-block text-decoration-none">
                    <img src="{{ asset('assets/images/portal_logo_transparent.png') }}" alt="{{ $systemSetting->application_name ?? 'i2u2 HR Portal' }}" class="img-fluid" style="max-height: 54px; width: auto; object-fit: contain;">
                </a>
            </div>

            <!-- Form Eyebrow & Headline -->
            <div class="mb-4">
                <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase fs-9 px-2.5 py-1 mb-2">Staff Access Gateway</span>
                <h2 class="fw-extrabold text-body-emphasis tracking-tight fs-3 mb-1">Employee Sign In</h2>
                <p class="text-body-secondary fs-8 mb-0">Enter your assigned employee credentials to access your HR portal.</p>
            </div>

            <!-- Session Feedback Alerts -->
            @if (session('status'))
                <div class="alert alert-info alert-dismissible fade show fs-8 border-0 shadow-xs mb-4" role="alert">
                    <i class="fa-solid fa-circle-info me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show fs-8 border-0 shadow-xs mb-4" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" onsubmit="submitWithLoader(this.querySelector('button[type=submit]'))">
                @csrf

                <!-- Employee ID Field -->
                <div class="mb-3">
                    <label for="employee_id" class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                        Employee ID / Username <span class="text-danger">*</span>
                    </label>
                    <div class="auth-input-group">
                        <span class="input-icon">
                            <i class="fa-solid fa-id-card"></i>
                        </span>
                        <input 
                            type="text" 
                            name="employee_id" 
                            id="employee_id" 
                            class="form-control @error('employee_id') is-invalid @enderror" 
                            value="{{ old('employee_id') }}" 
                            required 
                            autofocus 
                            placeholder="e.g. EMP-0102 or registered code"
                            autocomplete="username"
                        >
                    </div>
                    @error('employee_id')
                        <div class="invalid-feedback d-block mt-1 fs-9">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Field with Reveal Toggle -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label fw-semibold text-body-emphasis fs-8 mb-0">
                            Password <span class="text-danger">*</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fs-9 fw-medium">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="auth-input-group position-relative">
                        <span class="input-icon">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control pe-5 @error('password') is-invalid @enderror" 
                            required 
                            placeholder="Enter your account password"
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()" title="Show/Hide Password" aria-label="Toggle Password Visibility">
                            <i class="fa-regular fa-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1 fs-9">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="d-flex justify-content-between align-items-center mb-4 pt-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-body-secondary fs-8 select-none" for="remember_me">
                            Keep me signed in on this workstation
                        </label>
                    </div>
                </div>

                <!-- Submit Button with Rule 12 Loading State -->
                <button type="submit" class="btn btn-auth-submit w-100 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In to HR Portal
                </button>
            </form>

            <!-- HR Helpdesk & Support Assistance -->
            <div class="mt-4 pt-3 text-center border-top border-subtle">
                <div class="d-flex flex-column align-items-center gap-1 fs-9 text-body-secondary">
                    <div>
                        <i class="fa-solid fa-headset me-1 text-primary"></i> 
                        Need assistance? Contact HR at <a href="mailto:{{ $systemSetting->support_email ?? 'support@i2k2.com' }}" class="text-primary text-decoration-none fw-semibold">{{ $systemSetting->support_email ?? 'support@i2k2.com' }}</a>
                    </div>
                    <div class="text-body-tertiary">
                        <i class="fa-solid fa-lock me-1 text-success"></i> 256-bit TLS Encrypted Session &bull; Organization Intranet
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('passwordToggleIcon');
        if (!passwordInput || !icon) return;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
