@extends('layouts.app')

@section('title', 'Multi-SMTP Profiles & Mail System')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Multi-SMTP Profiles & Mail System</h1>
            <p class="text-body-secondary fs-7 mb-0">Manage multiple SMTP sender profiles, assign profiles to portal modules, and configure company-specific Extra CC recipients.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('email-templates.index') }}" class="btn btn-light-info btn-sm fw-semibold">
                <i class="fa-solid fa-envelope-open-text me-1"></i> Email Templates
            </a>
            <a href="{{ route('email-logs.index') }}" class="btn btn-light-warning btn-sm fw-semibold">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Email Delivery Logs
            </a>
            <a href="{{ route('system-settings.index') }}" class="btn btn-light-secondary btn-sm fw-semibold">
                <i class="fa-solid fa-sliders me-1"></i> System Settings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Top Section: Multi-SMTP Sender Profiles Grid -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 pt-4 bg-body-tertiary d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-server text-primary me-2"></i> SMTP Sender Profiles
            </h3>
            <button type="button" class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#profileModal" onclick="resetProfileForm()">
                <i class="fa-solid fa-plus me-1"></i> Add SMTP Profile
            </button>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @forelse($smtpProfiles as $profile)
                <div class="col-md-6 col-xl-4">
                    <div class="card border h-100 {{ !empty($profile['is_default']) ? 'border-primary border-2' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0 text-body-emphasis fs-6">{{ $profile['name'] ?? 'SMTP Profile' }}</h5>
                                <div class="d-flex gap-1">
                                    @if(!empty($profile['is_default']))
                                        <span class="badge bg-primary">Default</span>
                                    @endif
                                    @if(!empty($profile['is_active']))
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </div>
                            </div>
                            <ul class="list-unstyled fs-7 text-body-secondary mb-4">
                                <li class="mb-1"><i class="fa-solid fa-globe me-2 text-muted"></i> <strong>Host:</strong> {{ $profile['host'] ?? '' }}:{{ $profile['port'] ?? '' }}</li>
                                <li class="mb-1"><i class="fa-solid fa-user-shield me-2 text-muted"></i> <strong>User:</strong> {{ $profile['username'] ?? '' }}</li>
                                <li class="mb-1"><i class="fa-solid fa-at me-2 text-muted"></i> <strong>From:</strong> {{ $profile['from_address'] ?? '' }}</li>
                                <li class="mb-1"><i class="fa-solid fa-lock me-2 text-muted"></i> <strong>Encryption:</strong> {{ strtoupper($profile['encryption'] ?? 'TLS') }}</li>
                            </ul>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#testModal" onclick="populateTestModal({{ json_encode($profile) }})">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Test Connection
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#profileModal" onclick="editProfile({{ json_encode($profile) }})">
                                        <i class="fa-solid fa-pen me-1"></i> Edit
                                    </button>
                                    @if(empty($profile['is_default']))
                                    <form method="POST" action="{{ route('smtp-profiles.destroy', $profile['id']) }}" onsubmit="return confirm('Remove this SMTP Profile?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info border-0 mb-0">
                        <i class="fa-solid fa-circle-info me-2"></i> No custom SMTP profiles configured yet. Default fallback configuration is active.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Middle Section: Global Mail Routing & Module Switches -->
    <form method="POST" action="{{ route('smtp-profiles.routing') }}" class="mb-4">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header border-0 pt-4 bg-body-tertiary">
                <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                    <i class="fa-solid fa-route text-success me-2"></i> Global Mail Master Switch & Module SMTP Routing
                </h3>
            </div>
            <div class="card-body">
                <!-- Master Toggle -->
                <div class="p-3 rounded bg-body-tertiary border mb-4">
                    <div class="form-check form-switch d-flex align-items-center gap-3">
                        <input class="form-check-input fs-4" type="checkbox" name="global_enabled" id="globalEnabledSwitch" {{ !empty($mailConfig['global_enabled']) ? 'checked' : '' }}>
                        <div>
                            <label class="form-check-label fw-bold text-body-emphasis fs-6" for="globalEnabledSwitch">
                                Enable Global Outgoing Email System
                            </label>
                            <div class="text-body-secondary fs-8">Toggling this master switch off disables all outgoing portal email notifications instantly.</div>
                        </div>
                    </div>
                </div>

                <!-- Module Routing Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%;">Module Event Category</th>
                                <th style="width: 15%;">Module Status</th>
                                <th style="width: 30%;">Assigned SMTP Profile</th>
                                <th style="width: 35%;">Global Extra CC Email Recipients (Comma-Separated)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $modules = [
                                    'leave' => ['name' => 'Leave Management', 'icon' => 'fa-calendar-minus', 'color' => 'text-primary'],
                                    'ticket' => ['name' => 'Support & HR Tickets', 'icon' => 'fa-ticket-simple', 'color' => 'text-info'],
                                    'announcement' => ['name' => 'Announcements', 'icon' => 'fa-bullhorn', 'color' => 'text-warning'],
                                    'recruitment' => ['name' => 'Talent & Recruitment', 'icon' => 'fa-user-plus', 'color' => 'text-success'],
                                    'payroll' => ['name' => 'Payroll & Payslips', 'icon' => 'fa-money-bill-wave', 'color' => 'text-danger'],
                                    'onboarding' => ['name' => 'Employee Onboarding', 'icon' => 'fa-id-card', 'color' => 'text-indigo'],
                                ];
                            @endphp

                            @foreach($modules as $key => $mod)
                            <tr>
                                <td class="fw-bold text-body-emphasis">
                                    <i class="fa-solid {{ $mod['icon'] }} {{ $mod['color'] }} me-2"></i> {{ $mod['name'] }}
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="module_switch_{{ $key }}" id="modSwitch_{{ $key }}" {{ !empty($mailConfig['module_switches'][$key]) ? 'checked' : '' }}>
                                        <label class="form-check-label fs-8" for="modSwitch_{{ $key }}">Enable</label>
                                    </div>
                                </td>
                                <td>
                                    <select name="profile_{{ $key }}" class="form-select form-select-sm">
                                        @foreach($smtpProfiles as $profKey => $prof)
                                            <option value="{{ $profKey }}" {{ ($mailConfig['module_profile_mappings'][$key] ?? 'default') === $profKey ? 'selected' : '' }}>
                                                {{ $prof['name'] ?? $profKey }} ({{ $prof['from_address'] ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="extra_cc_{{ $key }}" class="form-control form-control-sm" placeholder="e.g. hr@company.com, audit@company.com" value="{{ $mailConfig['global_extra_ccs'][$key] ?? '' }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Global Routing & Module Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Bottom Section: Company-Based Extra CC Recipient Configuration -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 pt-4 bg-body-tertiary">
            <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-building text-warning me-2"></i> Company-Specific Extra CC Email Routing
            </h3>
        </div>
        <div class="card-body">
            <p class="text-body-secondary fs-7 mb-4">Configure entity-specific extra CC email recipients. When events trigger for a specific company, notifications will automatically include these CC addresses.</p>

            <form method="POST" action="{{ route('smtp-profiles.company-routing') }}">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fs-8 fw-bold">Select Target Company</label>
                        <select name="company_id" id="companySelect" class="form-select form-select-sm" onchange="loadCompanyExtraCcs(this.value)">
                            <option value="">-- Choose Company --</option>
                            @foreach($companies as $comp)
                                <option value="{{ $comp->company_id }}">{{ $comp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="companyCcContainer" style="display: none;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Leave Management Extra CCs</label>
                            <input type="text" name="company_extra_cc_leave" id="compCc_leave" class="form-control form-control-sm" placeholder="e.g. hr-tech@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Support Tickets Extra CCs</label>
                            <input type="text" name="company_extra_cc_ticket" id="compCc_ticket" class="form-control form-control-sm" placeholder="e.g. helpdesk-tech@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Talent & Recruitment Extra CCs</label>
                            <input type="text" name="company_extra_cc_recruitment" id="compCc_recruitment" class="form-control form-control-sm" placeholder="e.g. talent-tech@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Payroll & Payslips Extra CCs</label>
                            <input type="text" name="company_extra_cc_payroll" id="compCc_payroll" class="form-control form-control-sm" placeholder="e.g. finance-tech@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Announcements Extra CCs</label>
                            <input type="text" name="company_extra_cc_announcement" id="compCc_announcement" class="form-control form-control-sm" placeholder="e.g. board-tech@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Onboarding Extra CCs</label>
                            <input type="text" name="company_extra_cc_onboarding" id="compCc_onboarding" class="form-control form-control-sm" placeholder="e.g. onboarding-tech@company.com">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-warning fw-bold px-4 text-dark">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Company Extra CC Routing
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 1: Add/Edit SMTP Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('smtp-profiles.store') }}">
                @csrf
                <input type="hidden" name="id" id="profile_id">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="profileModalTitle">Add SMTP Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Profile Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="prof_name" class="form-control form-control-sm" required placeholder="e.g. HR & Leave Mailer">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label fs-8 fw-semibold">SMTP Host <span class="text-danger">*</span></label>
                            <input type="text" name="host" id="prof_host" class="form-control form-control-sm" required placeholder="e.g. smtp.gmail.com">
                        </div>
                        <div class="col-4">
                            <label class="form-label fs-8 fw-semibold">Port <span class="text-danger">*</span></label>
                            <input type="number" name="port" id="prof_port" class="form-control form-control-sm" required value="587">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Encryption</label>
                            <select name="encryption" id="prof_encryption" class="form-select form-select-sm">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="prof_username" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Password</label>
                        <input type="password" name="password" id="prof_password" class="form-control form-control-sm" placeholder="Leave blank to keep existing password">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Sender Email <span class="text-danger">*</span></label>
                            <input type="email" name="from_address" id="prof_from_address" class="form-control form-control-sm" required placeholder="hr@company.com">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Sender Name <span class="text-danger">*</span></label>
                            <input type="text" name="from_name" id="prof_from_name" class="form-control form-control-sm" required placeholder="Antigravity HR Team">
                        </div>
                    </div>
                    <div class="d-flex gap-4 border-top pt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="prof_is_active" checked>
                            <label class="form-check-label fs-8" for="prof_is_active">Profile Active</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_default" id="prof_is_default">
                            <label class="form-check-label fs-8" for="prof_is_default">Set as Default Fallback</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Save Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Test SMTP Connection -->
<div class="modal fade" id="testModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('smtp-profiles.test') }}">
                @csrf
                <input type="hidden" name="name" id="test_name">
                <input type="hidden" name="host" id="test_host">
                <input type="hidden" name="port" id="test_port">
                <input type="hidden" name="encryption" id="test_encryption">
                <input type="hidden" name="username" id="test_username">
                <input type="hidden" name="password" id="test_password">
                <input type="hidden" name="from_address" id="test_from_address">
                <input type="hidden" name="from_name" id="test_from_name">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">SMTP Connection Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-body-secondary fs-7 mb-3">Send a real-time diagnostic email using profile <strong id="testProfileLabel">Profile Name</strong> to test SMTP authentication and TLS handshake.</p>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Recipient Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="test_email" class="form-control form-control-sm" required placeholder="you@company.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info btn-sm fw-bold">
                        <i class="fa-solid fa-paper-plane me-1"></i> Send Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const companyData = @json($mailConfig['company_extra_ccs'] ?? []);

    function resetProfileForm() {
        document.getElementById('profileModalTitle').innerText = 'Add SMTP Profile';
        document.getElementById('profile_id').value = '';
        document.getElementById('prof_name').value = '';
        document.getElementById('prof_host').value = '';
        document.getElementById('prof_port').value = '587';
        document.getElementById('prof_encryption').value = 'tls';
        document.getElementById('prof_username').value = '';
        document.getElementById('prof_password').value = '';
        document.getElementById('prof_from_address').value = '';
        document.getElementById('prof_from_name').value = '';
        document.getElementById('prof_is_active').checked = true;
        document.getElementById('prof_is_default').checked = false;
    }

    function editProfile(prof) {
        document.getElementById('profileModalTitle').innerText = 'Edit SMTP Profile';
        document.getElementById('profile_id').value = prof.id || '';
        document.getElementById('prof_name').value = prof.name || '';
        document.getElementById('prof_host').value = prof.host || '';
        document.getElementById('prof_port').value = prof.port || '587';
        document.getElementById('prof_encryption').value = prof.encryption || 'tls';
        document.getElementById('prof_username').value = prof.username || '';
        document.getElementById('prof_password').value = prof.password || '';
        document.getElementById('prof_from_address').value = prof.from_address || '';
        document.getElementById('prof_from_name').value = prof.from_name || '';
        document.getElementById('prof_is_active').checked = !!prof.is_active;
        document.getElementById('prof_is_default').checked = !!prof.is_default;
    }

    function populateTestModal(prof) {
        document.getElementById('testProfileLabel').innerText = prof.name || 'SMTP Profile';
        document.getElementById('test_name').value = prof.name || '';
        document.getElementById('test_host').value = prof.host || '';
        document.getElementById('test_port').value = prof.port || '587';
        document.getElementById('test_encryption').value = prof.encryption || 'tls';
        document.getElementById('test_username').value = prof.username || '';
        document.getElementById('test_password').value = prof.password || '';
        document.getElementById('test_from_address').value = prof.from_address || '';
        document.getElementById('test_from_name').value = prof.from_name || '';
    }

    function loadCompanyExtraCcs(compId) {
        const container = document.getElementById('companyCcContainer');
        if (!compId) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';
        const cc = companyData[compId] || {};

        document.getElementById('compCc_leave').value = cc.leave || '';
        document.getElementById('compCc_ticket').value = cc.ticket || '';
        document.getElementById('compCc_recruitment').value = cc.recruitment || '';
        document.getElementById('compCc_payroll').value = cc.payroll || '';
        document.getElementById('compCc_announcement').value = cc.announcement || '';
        document.getElementById('compCc_onboarding').value = cc.onboarding || '';
    }
</script>
@endsection
