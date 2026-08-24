@extends('layouts.app')

@section('title', 'Global System Settings')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Global System Settings</h1>
            <p class="text-body-secondary fs-7 mb-0">Configure portal identity, active core modules, timezone, and employee self-service capabilities.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('settings.theme.index') }}" class="btn btn-primary btn-sm fw-semibold">
                <i class="fa-solid fa-palette me-1"></i> Theme & Branding
            </a>
            <a href="{{ route('user-roles.index') }}" class="btn btn-light-primary btn-sm fw-semibold">
                <i class="fa-solid fa-user-shield me-1"></i> User Roles & Access
            </a>
            <a href="{{ route('smtp-profiles.index') }}" class="btn btn-light-success btn-sm fw-semibold">
                <i class="fa-solid fa-server me-1"></i> SMTP Profiles & Routing
            </a>
            <a href="{{ route('email-templates.index') }}" class="btn btn-light-info btn-sm fw-semibold">
                <i class="fa-solid fa-envelope-open-text me-1"></i> Email Templates
            </a>
            <a href="{{ route('email-logs.index') }}" class="btn btn-light-warning btn-sm fw-semibold">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Email Logs
            </a>
        </div>
    </div>

    <!-- Main Settings Form -->
    <form method="POST" action="{{ route('system-settings.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Column: System Identity & Localization -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 pt-4 bg-light bg-opacity-50">
                        <h3 class="card-title fw-bold text-gray-900 fs-6">
                            <i class="fa-solid fa-sliders text-primary me-2"></i> System Identity & Branding
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Application Name <span class="text-danger">*</span></label>
                            <input type="text" name="application_name" class="form-control form-control-sm" required value="{{ $setting->application_name ?? 'Antigravity HR Portal' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Support Contact Email <span class="text-danger">*</span></label>
                            <input type="email" name="support_email" class="form-control form-control-sm" required value="{{ $setting->support_email ?? 'support@company.com' }}">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold">Currency Code</label>
                                <input type="text" name="default_currency" class="form-control form-control-sm" value="{{ $setting->default_currency ?? 'INR' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold">Currency Symbol</label>
                                <input type="text" name="default_currency_symbol" class="form-control form-control-sm" value="{{ $setting->default_currency_symbol ?? '₹' }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">System Timezone</label>
                            <select name="system_timezone" class="form-select form-select-sm">
                                <option value="Asia/Kolkata" {{ ($setting->system_timezone ?? 'Asia/Kolkata') === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST +5:30)</option>
                                <option value="UTC" {{ ($setting->system_timezone ?? '') === 'UTC' ? 'selected' : '' }}>UTC Universal</option>
                                <option value="America/New_York" {{ ($setting->system_timezone ?? '') === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                <option value="Europe/London" {{ ($setting->system_timezone ?? '') === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Portal Footer Copyright Text</label>
                            <input type="text" name="footer_text" class="form-control form-control-sm" value="{{ $setting->footer_text ?? '© 2026 Antigravity HR Portal. All rights reserved.' }}">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pt-4 bg-light bg-opacity-50">
                        <h3 class="card-title fw-bold text-gray-900 fs-6">
                            <i class="fa-solid fa-user-lock text-success me-2"></i> Registration & Authentication Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="enable_registration" id="enableRegistration" {{ !empty($setting->enable_registration) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="enableRegistration">
                                Allow New User Self-Registration
                            </label>
                            <div class="form-text fs-9">Enabling this lets external candidates or unverified users register accounts directly.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Active Module Toggles & Employee Permissions -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 pt-4 bg-light bg-opacity-50">
                        <h3 class="card-title fw-bold text-gray-900 fs-6">
                            <i class="fa-solid fa-cubes text-info me-2"></i> Active Portal Modules
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="module_recruitment" id="moduleRecruitment" {{ !empty($setting->module_recruitment) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="moduleRecruitment">
                                Enable Talent Recruitment Module
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="module_training" id="moduleTraining" {{ !empty($setting->module_training) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="moduleTraining">
                                Enable Training & Development Module
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="module_performance" id="modulePerformance" {{ !empty($setting->module_performance) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="modulePerformance">
                                Enable Performance Management Module
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="module_assets" id="moduleAssets" {{ !empty($setting->module_assets) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="moduleAssets">
                                Enable Assets & Inventory Management Module
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pt-4 bg-light bg-opacity-50">
                        <h3 class="card-title fw-bold text-gray-900 fs-6">
                            <i class="fa-solid fa-user-gear text-warning me-2"></i> Employee Self-Service Permissions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="employee_manage_own_contact" id="empContact" {{ !empty($setting->employee_manage_own_contact) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="empContact">
                                Employees can manage their emergency contacts
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="employee_manage_own_profile" id="empProfile" {{ !empty($setting->employee_manage_own_profile) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="empProfile">
                                Employees can update basic profile info
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="employee_manage_own_qualification" id="empQualification" {{ !empty($setting->employee_manage_own_qualification) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="empQualification">
                                Employees can upload educational qualifications
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="employee_manage_own_document" id="empDocument" {{ !empty($setting->employee_manage_own_document) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold fs-8 text-gray-800" for="empDocument">
                                Employees can upload personal identity documents
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary px-4 fw-bold">
                <i class="fa-solid fa-floppy-disk me-1"></i> Save Global Configurations
            </button>
        </div>
    </form>
</div>
@endsection
