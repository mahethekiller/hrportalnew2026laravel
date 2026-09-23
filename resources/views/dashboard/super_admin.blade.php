@extends('layouts.app')

@section('title', 'Super Admin Console')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner (Rule 13 Benchmark) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-9 font-monospace">
                    <i class="fa-solid fa-shield-halved me-1"></i> Root Governance • Super Admin Console
                </span>
                <span class="text-body-secondary fs-9">• Global Operations & Multi-Tenant Architecture</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">System Operations & Global Configuration</h4>
            <p class="text-body-secondary fs-8 mb-0">Manage multi-tenant entities, global branding parameters, Spatie security rules, and active operational modules.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <a href="{{ url('/api-docs') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-code me-1.5 text-info"></i> API Docs
            </a>
            <a href="{{ route('user-roles.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-user-shield me-1.5 text-warning"></i> Access Roles
            </a>
            <a href="{{ route('system-settings.index') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 shadow-xs">
                <i class="fa-solid fa-sliders me-1.5"></i> System Settings
            </a>
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards (Rule 13 Benchmark) -->
    <div class="row g-3 mb-4">
        <!-- Registered Companies -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Registered Companies</span>
                        <h3 class="fw-bolder text-body-emphasis mb-1 mt-1">{{ $totalCompanies }}</h3>
                        <span class="fs-9 text-body-secondary">Multi-tenant parent entities</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-building fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Departments -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Departments</span>
                        <h3 class="fw-bolder text-info mb-1 mt-1">{{ $totalDepartments }}</h3>
                        <span class="fs-9 text-body-secondary">Operating business divisions</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-sitemap fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Designations -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Designations</span>
                        <h3 class="fw-bolder text-warning mb-1 mt-1">{{ $totalDesignations }}</h3>
                        <span class="fs-9 text-body-secondary">Standardized job positions</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-briefcase fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Office Locations -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Office Locations</span>
                        <h3 class="fw-bolder text-success mb-1 mt-1">{{ $totalLocations }}</h3>
                        <span class="fs-9 text-body-secondary">Physical branches & facilities</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-location-dot fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Main Operations Studio Grid -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Active Brand Parameters Bento Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4 overflow-hidden">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-body-emphasis fs-6 mb-1">
                            <i class="fa-solid fa-sliders text-primary me-2"></i> Active Brand & Portal Parameters
                        </h5>
                        <p class="text-body-secondary fs-8 mb-0">System-wide settings governing white-labeling, regional localization, and legal copyright.</p>
                    </div>
                    <a href="{{ route('system-settings.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs px-2.5 py-1.5 rounded-2 fs-9">
                        <i class="fa-solid fa-pen-to-square me-1 text-primary"></i> Edit Branding
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 fs-8">
                            <tbody>
                                <tr>
                                    <td class="ps-4 text-body-secondary fw-semibold" style="width: 32%;">
                                        <i class="fa-solid fa-tag me-2 text-primary opacity-75"></i> Portal Brand Name
                                    </td>
                                    <td class="pe-4 text-body-emphasis fw-bold">
                                        {{ $systemSetting->application_name ?? 'Antigravity HR Portal' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-body-secondary fw-semibold">
                                        <i class="fa-solid fa-envelope me-2 text-info opacity-75"></i> Support Desk Email
                                    </td>
                                    <td class="pe-4 text-body-emphasis font-monospace">
                                        {{ $systemSetting->support_email ?? 'support@example.com' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-body-secondary fw-semibold">
                                        <i class="fa-solid fa-globe me-2 text-success opacity-75"></i> System Timezone
                                    </td>
                                    <td class="pe-4">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 font-monospace">
                                            <i class="fa-solid fa-clock me-1"></i> {{ $systemSetting->system_timezone ?? 'Asia/Kolkata' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-body-secondary fw-semibold">
                                        <i class="fa-solid fa-coins me-2 text-warning opacity-75"></i> Default Currency
                                    </td>
                                    <td class="pe-4 text-body-emphasis">
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 font-monospace me-1">
                                            {{ $systemSetting->default_currency ?? 'INR' }}
                                        </span>
                                        <span class="text-body-secondary fw-bold">({{ $systemSetting->default_currency_symbol ?? '₹' }})</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-body-secondary fw-semibold">
                                        <i class="fa-solid fa-copyright me-2 text-secondary opacity-75"></i> Copyright Footer
                                    </td>
                                    <td class="pe-4 text-body-secondary fs-9">
                                        {{ $systemSetting->footer_text ?? '© ' . date('Y') . ' Antigravity HR Portal. All rights reserved.' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Portal Modules Operational Status -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold text-body-emphasis fs-6 mb-1">
                            <i class="fa-solid fa-cubes-stacked text-info me-2"></i> Operational Module Ecosystem
                        </h5>
                        <p class="text-body-secondary fs-8 mb-0">Feature flags dynamically controlling sidebar navigation and workflow accessibility.</p>
                    </div>
                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fs-9">
                        <i class="fa-solid fa-toggle-on me-1"></i> Dynamic Modules
                    </span>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <div class="row g-3">
                        @php
                            $modules = [
                                [
                                    'name' => 'Recruitment Pipeline',
                                    'status' => $systemSetting->module_recruitment ?? 0,
                                    'icon' => 'fa-user-tie',
                                    'color' => 'primary',
                                    'desc' => 'Candidate sourcing, interviewing stages & job offerings'
                                ],
                                [
                                    'name' => 'Travel & Expenses',
                                    'status' => $systemSetting->module_travel ?? 0,
                                    'icon' => 'fa-plane-departure',
                                    'color' => 'info',
                                    'desc' => 'Official travel requisitions, itineraries & claim disbursements'
                                ],
                                [
                                    'name' => 'Performance & Appraisals',
                                    'status' => $systemSetting->module_performance ?? 0,
                                    'icon' => 'fa-chart-line',
                                    'color' => 'warning',
                                    'desc' => 'KRA / KPI reviews, goal monitoring & evaluation cycles'
                                ],
                                [
                                    'name' => 'Assets & Equipment',
                                    'status' => $systemSetting->module_assets ?? 0,
                                    'icon' => 'fa-laptop-file',
                                    'color' => 'success',
                                    'desc' => 'Hardware provisioning, custodial inventory & license assignments'
                                ],
                                [
                                    'name' => 'Training & Certifications',
                                    'status' => $systemSetting->module_training ?? 0,
                                    'icon' => 'fa-graduation-cap',
                                    'color' => 'danger',
                                    'desc' => 'Corporate training programs, skill workshops & certificates'
                                ],
                            ];
                        @endphp

                        @foreach($modules as $m)
                            <div class="col-md-6">
                                <div class="p-3 border border-subtle rounded-3 bg-body h-100 d-flex flex-column justify-content-between transition-all">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="avatar-sm rounded-2 bg-{{ $m['color'] }}-subtle text-{{ $m['color'] }} d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                <i class="fa-solid {{ $m['icon'] }} fs-6"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-body-emphasis fs-8 d-block">{{ $m['name'] }}</span>
                                            </div>
                                        </div>
                                        @if($m['status'])
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-9">
                                                <i class="fa-solid fa-circle-check me-1"></i> Enabled
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-body-secondary border border-secondary-subtle rounded-pill px-2 py-0.5 fs-9">
                                                <i class="fa-solid fa-circle-xmark me-1"></i> Disabled
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-body-secondary fs-9 mb-0 ps-1">{{ $m['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Server & Environment Telemetry Strip -->
            <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 fs-9">
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-body-secondary">
                                <i class="fa-solid fa-server me-1.5 text-primary"></i> <strong>PHP:</strong> {{ phpversion() }}
                            </span>
                            <span class="text-body-secondary">•</span>
                            <span class="text-body-secondary">
                                <i class="fa-brands fa-laravel me-1.5 text-danger"></i> <strong>Laravel:</strong> {{ app()->version() }}
                            </span>
                            <span class="text-body-secondary">•</span>
                            <span class="text-body-secondary">
                                <i class="fa-solid fa-database me-1.5 text-info"></i> <strong>DB:</strong> Connected
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                <i class="fa-solid fa-circle-check me-1"></i> All Services Operational
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column Sidebar -->
        <div class="col-lg-4">
            <!-- Root Administration Quick Panel -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary">
                <div class="card-header border-0 bg-transparent pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-body-emphasis fs-7 mb-0">
                        <i class="fa-solid fa-bolt text-warning me-2"></i> Root Control Desk
                    </h5>
                    <p class="text-body-secondary fs-9 mb-0">Quick shortcuts for system administration</p>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <div class="d-grid gap-2">
                        <a href="{{ route('system-settings.index') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-sliders text-primary"></i>
                                <span>Global System Settings</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>
                        <a href="{{ route('user-roles.index') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-user-shield text-warning"></i>
                                <span>Spatie Roles & Permissions</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>
                        <a href="{{ url('/api-docs') }}" class="btn btn-body text-start fs-8 fw-semibold py-2.5 px-3 border border-subtle text-body-emphasis shadow-xs rounded-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-book-open text-info"></i>
                                <span>Developer API Specifications</span>
                            </div>
                            <i class="fa-solid fa-chevron-right fs-9 text-body-secondary"></i>
                        </a>
                    </div>
                </div>
            </div>

            @include('dashboard.partials.sidebar_widgets')
        </div>
    </div>
</div>
@endsection

