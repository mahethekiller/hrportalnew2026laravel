@extends('layouts.app')

@section('title', $employee->first_name . ' ' . $employee->last_name . ' - Profile')
@section('page_title', 'Employee Profile Details')

@push('css')
<style>
    /* Profile Navigation Tabs */
    .nav-line-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        background: none;
        color: var(--bs-secondary-color);
        padding: 10px 18px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .nav-line-tabs .nav-link:hover {
        color: var(--bs-primary);
        border-bottom-color: var(--bs-border-color-subtle);
    }
    .nav-line-tabs .nav-link.active {
        color: var(--bs-primary);
        border-bottom-color: var(--bs-primary);
        background: none;
    }

    .info-label {
        font-size: 0.725rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--bs-secondary-color);
        font-weight: 700;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--bs-emphasis-color);
    }

    .info-value.empty-val {
        color: var(--bs-secondary-color);
        font-weight: 400;
        font-style: italic;
    }

    /* Social Buttons & Chips */
    .social-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        min-height: 36px;
        border-radius: 8px;
        color: #ffffff !important;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.15s ease, filter 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .social-btn i {
        font-size: 1rem;
        width: 16px;
        text-align: center;
    }
    .social-btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.18);
    }
    .social-linkedin { background-color: #0A66C2; }
    .social-skype { background-color: #0078D4; }
    .social-twitter { background-color: #0F1419; border: 1px solid rgba(255,255,255,0.15); }
    .social-facebook { background-color: #1877F2; }
    .social-instagram { background: linear-gradient(45deg, #F58529, #DD2A7B, #8134AF); }
    .social-youtube { background-color: #FF0000; }
    .social-pinterest { background-color: #BD081C; }
    .social-blogger { background-color: #F57D00; }
    .social-google { background-color: #EA4335; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner (Matching Rule 13 /recruitment-applications benchmark) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-id-card me-1"></i> Talent Dossier
                </span>
                <span class="text-body-secondary fs-9">• Employee Profile Details</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
            <p class="text-body-secondary fs-8 mb-0">Official ID: <span class="fw-bold font-monospace text-primary">{{ (!empty($employee->employee_id) && $employee->employee_id !== '0') ? $employee->employee_id : 'EMP-' . sprintf('%04d', $employee->id) }}</span> • Department: {{ $employee->department->department_name ?? $employee->department->name ?? 'General' }} • Designation: {{ $employee->designation->designation_name ?? $employee->designation->name ?? 'Staff' }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5 text-secondary"></i> Directory
            </a>
            <button type="button" class="btn btn-sm btn-outline-info shadow-xs fw-semibold px-3 py-2 rounded-2" onclick="navigator.clipboard.writeText('{{ route('onboarding', md5((string)$employee->user_id)) }}'); toastr.success('Onboarding link copied to clipboard!');" title="Copy Onboarding Link">
                <i class="fa-solid fa-link me-1.5"></i> Onboarding Link
            </button>
            <a href="{{ route('leaves.index', ['employee_id' => $employee->id]) }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-calendar-plus me-1.5 text-primary"></i> Request Leave
            </a>
            @can('edit.employees')
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning fw-semibold px-3 py-2 rounded-2 shadow-xs">
                    <i class="fa-solid fa-pen-to-square me-1.5"></i> Edit Profile
                </a>
            @endcan
        </div>
    </div>

    <!-- 4 Telemetry Metrics Cards (Rule 13 exact pattern) -->
    <div class="row g-3 mb-4">
        <!-- Employment Status -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Employment Status</span>
                        <h3 class="fw-bolder {{ $employee->is_active ? 'text-success' : 'text-danger' }} mb-1 mt-1 fs-5">
                            {{ $employee->probation_status == 1 ? 'Under Probation' : 'Permanent Staff' }}
                        </h3>
                        <span class="fs-9 text-body-secondary">{{ $employee->is_active ? 'Active & Enrolled on Payroll' : 'Inactive / Exited' }}</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-{{ $employee->is_active ? 'success' : 'danger' }}-subtle text-{{ $employee->is_active ? 'success' : 'danger' }} d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-shield-halved fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-{{ $employee->is_active ? 'success' : 'danger' }}" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Operational Entity -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Operating Unit</span>
                        <h3 class="fw-bolder text-primary mb-1 mt-1 fs-5 text-truncate" style="max-width: 170px;">
                            {{ $employee->company->name ?? $employee->company->company_name ?? 'Default Corp' }}
                        </h3>
                        <span class="fs-9 text-body-secondary">Location: {{ $employee->reporting_location ?? 'Headquarters' }}</span>
                    </div>
                    <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-building fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Date of Joining & Tenure -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Date of Joining</span>
                        <h3 class="fw-bolder text-info mb-1 mt-1 fs-5">
                            {{ $employee->date_of_joining ? date('M d, Y', strtotime($employee->date_of_joining)) : 'Not Recorded' }}
                        </h3>
                        <span class="fs-9 text-body-secondary">
                            {{ $employee->date_of_joining ? \Carbon\Carbon::parse($employee->date_of_joining)->diffForHumans(null, true) . ' tenure' : 'Tenure unavailable' }}
                        </span>
                    </div>
                    <div class="avatar-md rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-calendar-check fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
            </div>
        </div>

        <!-- Reporting Manager -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-body-secondary fs-9 fw-bold text-uppercase tracking-wider">Direct Supervisor</span>
                        <h3 class="fw-bolder text-warning mb-1 mt-1 fs-5 text-truncate" style="max-width: 170px;">
                            {{ $employee->manager ? ($employee->manager->first_name . ' ' . $employee->manager->last_name) : 'Executive Dept' }}
                        </h3>
                        <span class="fs-9 text-body-secondary">
                            {{ $employee->manager?->designation?->designation_name ?? 'Management' }}
                        </span>
                    </div>
                    <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-tie fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <!-- Employee Profile Hero Card -->
    <div class="card border-0 shadow-sm rounded-3 p-4 bg-body-tertiary mb-4 position-relative overflow-hidden">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            <div class="position-relative flex-shrink-0">
                @if($employee->profile_picture && file_exists(public_path('uploads/profile/' . $employee->profile_picture)))
                    <img src="{{ asset('uploads/profile/' . $employee->profile_picture) }}" alt="{{ $employee->first_name }}" class="rounded-circle border border-2 border-primary shadow-sm" style="width: 84px; height: 84px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-2 border border-2 border-primary shadow-xs" style="width: 84px; height: 84px;">
                        {{ substr($employee->first_name ?? 'E', 0, 1) }}{{ substr($employee->last_name ?? '', 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-grow-1 text-center text-md-start">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                    <h4 class="card-title mb-0 fw-bold text-body-emphasis">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                    <x-status-badge :status="$employee->is_active" pulse="true" />
                    @if($employee->probation_status == 1)
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill fw-semibold fs-9 px-2.5 py-1"><i class="fa-solid fa-user-clock me-1"></i>Probation</span>
                    @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-semibold fs-9 px-2.5 py-1"><i class="fa-solid fa-shield-halved me-1"></i>Permanent</span>
                    @endif
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold fs-9 px-2.5 py-1">
                        <i class="fa-solid fa-user-shield me-1"></i>{{ $employee->userRole->role_name ?? $employee->roles->first()?->name ?? 'Employee' }}
                    </span>
                </div>
                <div class="text-body-secondary fw-medium mb-2 fs-7">
                    <span class="text-primary fw-semibold">{{ $employee->designation->designation_name ?? $employee->designation->name ?? 'Staff Member' }}</span> 
                    <span class="text-body-secondary">•</span> 
                    <span>{{ $employee->department->department_name ?? $employee->department->name ?? 'General Department' }}</span>
                    @if(!empty($employee->sub_department))
                        <span class="text-body-secondary">({{ $employee->sub_department }})</span>
                    @endif
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3 fs-8 text-body-secondary">
                    <span><i class="fa-solid fa-envelope me-1.5 text-primary"></i><a href="mailto:{{ $employee->email }}" class="text-body-secondary text-decoration-none">{{ $employee->email }}</a></span>
                    @if($employee->contact_no)
                        <span><i class="fa-solid fa-phone me-1.5 text-success"></i><a href="tel:{{ $employee->contact_no }}" class="text-body-secondary text-decoration-none">{{ $employee->contact_no }}</a></span>
                    @endif
                    @if($employee->date_of_joining)
                        <span><i class="fa-solid fa-calendar-check me-1.5 text-warning"></i>Joined {{ date('M d, Y', strtotime($employee->date_of_joining)) }}</span>
                    @endif
                    @if($employee->reporting_location)
                        <span><i class="fa-solid fa-location-dot me-1.5 text-danger"></i>{{ $employee->reporting_location }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Navigation Tabs Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-body-tertiary mb-4">
        <div class="card-header border-bottom border-subtle bg-transparent p-0">
            <ul class="nav nav-tabs nav-line-tabs px-3" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-pane" type="button">
                        <i class="fa-solid fa-id-card me-1.5 text-primary"></i>Personal Profile
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-pane" type="button">
                        <i class="fa-solid fa-briefcase me-1.5 text-info"></i>Job Specs & Shifts
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="compensation-tab" data-bs-toggle="tab" data-bs-target="#compensation-pane" type="button">
                        <i class="fa-solid fa-wallet me-1.5 text-warning"></i>Compensation & Leaves
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-pane" type="button">
                        <i class="fa-solid fa-folder-open me-1.5 text-danger"></i>Documents ({{ $employee->employeeDocuments->count() + $employee->employeeContracts->count() }})
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" id="qualifications-tab" data-bs-toggle="tab" data-bs-target="#qualifications-pane" type="button">
                        <i class="fa-solid fa-graduation-cap me-1.5 text-success"></i>Qualifications ({{ $employee->employeeQualifications->count() + $employee->employeeWorkExperiences->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts-pane" type="button">
                        <i class="fa-solid fa-phone-flip me-1.5 text-secondary"></i>Emergency Contacts ({{ $employee->employeeContacts->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="profileTabsContent">
                <!-- Tab 1: Personal Profile Pane -->
                <div class="tab-pane fade show active" id="personal-pane" role="tabpanel">
                    <div class="row g-4">
                        <!-- Col 1: Personal Details & Demographics -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-user me-2 text-primary"></i>Personal Demographics</h6>
                                    <i class="fa-solid fa-shield-halved text-body-secondary fs-8" title="Verified Record"></i>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="info-label">Full Name</div>
                                            <div class="info-value">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Username</div>
                                            <div class="info-value">{{ $employee->username ? '@' . $employee->username : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Gender</div>
                                            <div class="info-value">{{ $employee->gender ?? 'Not Specified' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Date of Birth</div>
                                            <div class="info-value">{{ $employee->date_of_birth ? date('M d, Y', strtotime($employee->date_of_birth)) : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Age</div>
                                            <div class="info-value">{{ $employee->age ? $employee->age . ' Years' : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Place of Birth</div>
                                            <div class="info-value">{{ $employee->place_of_birth ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Mother Tongue</div>
                                            <div class="info-value">{{ $employee->mother_tongue ?? 'English' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Blood Group</div>
                                            <div class="info-value"><span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5"><i class="fa-solid fa-droplet me-1"></i>{{ $employee->blood_group ?? 'O+' }}</span></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Marital Status</div>
                                            <div class="info-value">{{ $employee->marital_status ?? 'Single' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Category</div>
                                            <div class="info-value">{{ $employee->category ?? 'General' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">PAN Card Number</div>
                                            <div class="info-value"><code class="text-primary fw-bold">{{ $employee->pan_number ?? 'N/A' }}</code></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Aadhar Card Number</div>
                                            <div class="info-value"><code class="text-info fw-bold">{{ $employee->aadhar_no ?? 'N/A' }}</code></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Col 2: Contacts & Addresses -->
                        <div class="col-lg-6 d-flex flex-column gap-4">
                            <!-- Card 1: Contact Details -->
                            <div class="card border border-subtle rounded-3 bg-body flex-grow-1">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-address-book text-success me-2"></i>Contact Details</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="info-label">Primary Mobile</div>
                                            <div class="info-value"><i class="fa-solid fa-phone text-success me-1"></i>{{ $employee->contact_no ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Official Mobile</div>
                                            <div class="info-value"><i class="fa-solid fa-phone-volume text-primary me-1"></i>{{ $employee->official_contact_no ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Personal Email</div>
                                            <div class="info-value"><i class="fa-solid fa-envelope text-warning me-1"></i>{{ $employee->email_personal ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Skype Account</div>
                                            <div class="info-value">
                                                @if($employee->skype_id)
                                                    <i class="fa-brands fa-skype text-info me-1"></i>{{ $employee->skype_id }}
                                                @else
                                                    <span class="empty-val">N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Paytm Number</div>
                                            <div class="info-value">{{ $employee->paytm_no ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Corporate Email</div>
                                            <div class="info-value"><i class="fa-solid fa-envelope text-primary me-1"></i>{{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Addresses -->
                            <div class="card border border-subtle rounded-3 bg-body flex-grow-1">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-map-location-dot text-danger me-2"></i>Addresses</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-label"><i class="fa-solid fa-house-chimney text-primary me-1"></i>Permanent Address</div>
                                            <div class="info-value">
                                                @if($employee->address)
                                                    {{ $employee->address }}<br>
                                                    {{ $employee->city ?? '' }}, {{ $employee->state ?? '' }} - {{ $employee->pincode ?? '' }}
                                                @else
                                                    <span class="empty-val">Address not registered</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label"><i class="fa-solid fa-house-laptop text-info me-1"></i>Communication Address</div>
                                            <div class="info-value">
                                                @if($employee->address_com)
                                                    {{ $employee->address_com }}<br>
                                                    {{ $employee->city_temp ?? '' }}, {{ $employee->state_temp ?? '' }} - {{ $employee->pin_temp ?? '' }}
                                                @else
                                                    <span class="empty-val">Same as permanent address</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Social Profiles -->
                            <div class="card border border-subtle rounded-3 bg-body">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-share-nodes text-primary me-2"></i>Social & External Profiles</h6>
                                </div>
                                <div class="card-body p-3 d-flex flex-wrap gap-2 align-items-center">
                                    @if($employee->linkdedin_link)
                                        <a href="{{ $employee->linkdedin_link }}" target="_blank" class="social-btn social-linkedin" title="LinkedIn Profile">
                                            <i class="fa-brands fa-linkedin-in"></i>
                                            <span>LinkedIn</span>
                                        </a>
                                    @endif
                                    @if($employee->skype_id)
                                        <a href="skype:{{ $employee->skype_id }}?chat" class="social-btn social-skype" title="Skype Chat">
                                            <i class="fa-brands fa-skype"></i>
                                            <span>Skype</span>
                                        </a>
                                    @endif
                                    @if($employee->twitter_link)
                                        <a href="{{ $employee->twitter_link }}" target="_blank" class="social-btn social-twitter" title="Twitter / X Handle">
                                            <i class="fa-brands fa-x-twitter"></i>
                                            <span>Twitter / X</span>
                                        </a>
                                    @endif
                                    @if($employee->facebook_link)
                                        <a href="{{ $employee->facebook_link }}" target="_blank" class="social-btn social-facebook" title="Facebook Profile">
                                            <i class="fa-brands fa-facebook-f"></i>
                                            <span>Facebook</span>
                                        </a>
                                    @endif
                                    @if($employee->instagram_link)
                                        <a href="{{ $employee->instagram_link }}" target="_blank" class="social-btn social-instagram" title="Instagram Profile">
                                            <i class="fa-brands fa-instagram"></i>
                                            <span>Instagram</span>
                                        </a>
                                    @endif
                                    @if($employee->youtube_link)
                                        <a href="{{ $employee->youtube_link }}" target="_blank" class="social-btn social-youtube" title="YouTube Channel">
                                            <i class="fa-brands fa-youtube"></i>
                                            <span>YouTube</span>
                                        </a>
                                    @endif
                                    @if(!$employee->linkdedin_link && !$employee->skype_id && !$employee->twitter_link && !$employee->facebook_link && !$employee->instagram_link && !$employee->youtube_link)
                                        <span class="text-body-secondary small italic"><i class="fa-solid fa-address-card me-1"></i>No social media handles registered.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Job Specs & Shifts Pane -->
                <div class="tab-pane fade" id="job-pane" role="tabpanel">
                    <div class="row g-4">
                        <!-- Job Specs -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-sitemap text-primary me-2"></i>Job Specifications</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="info-label">Company Entity</div>
                                            <div class="info-value">{{ $employee->company->name ?? $employee->company->company_name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Department / Designation</div>
                                            <div class="info-value">{{ $employee->department->department_name ?? 'N/A' }} / {{ $employee->designation->designation_name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Sub Department</div>
                                            <div class="info-value">{{ $employee->sub_department ? $employee->sub_department : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Employment Type</div>
                                            <div class="info-value"><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5">{{ $employee->employment_type ?? 'Full Time' }}</span></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Date of Joining</div>
                                            <div class="info-value">{{ $employee->date_of_joining ? date('M d, Y', strtotime($employee->date_of_joining)) : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Reporting Location</div>
                                            <div class="info-value">{{ $employee->reporting_location ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Employee Source</div>
                                            <div class="info-value">{{ $employee->employee_source ?? 'Direct' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Vehicle Registered</div>
                                            <div class="info-value">
                                                @if($employee->vehicle_no)
                                                    <span class="fw-semibold">{{ $employee->vehicle_no }}</span> <span class="text-body-secondary">({{ $employee->vehicle_type ?? 'Private' }})</span>
                                                @else
                                                    <span class="empty-val">No Vehicle</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supervisor & Shifts -->
                        <div class="col-lg-6 d-flex flex-column gap-4">
                            <!-- Card 1: Org Hierarchy / Supervisor -->
                            <div class="card border border-subtle rounded-3 bg-body flex-grow-1">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-users-gear text-info me-2"></i>Reporting Line</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-label"><i class="fa-solid fa-user-tie text-primary me-1"></i>Primary Manager</div>
                                            <div class="info-value pt-1">
                                                @if($employee->manager)
                                                    <a href="{{ route('employees.show', $employee->manager->id) }}" class="fw-bold text-decoration-none text-primary">
                                                        <i class="fa-solid fa-circle-user me-1"></i>{{ $employee->manager->first_name }} {{ $employee->manager->last_name }}
                                                    </a>
                                                @else
                                                    <span class="empty-val">No Manager Assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label"><i class="fa-solid fa-user-shield text-info me-1"></i>Sub Manager</div>
                                            <div class="info-value pt-1">
                                                @if($employee->subManager)
                                                    <a href="{{ route('employees.show', $employee->subManager->id) }}" class="fw-bold text-decoration-none text-info">
                                                        <i class="fa-solid fa-circle-user me-1"></i>{{ $employee->subManager->first_name }} {{ $employee->subManager->last_name }}
                                                    </a>
                                                @else
                                                    <span class="empty-val">No Sub-Manager Assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Office Shifts -->
                            <div class="card border border-subtle rounded-3 bg-body flex-grow-1">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-clock text-warning me-2"></i>Shift Specifications</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="info-label">Assigned Shift Name</div>
                                            <div class="info-value"><span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill fw-bold fs-8 px-2.5 py-1"><i class="fa-solid fa-business-time me-1"></i>{{ $employee->officeShift->shift_name ?? 'Standard Office Shift' }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Milestones & Dates -->
                            <div class="card border border-subtle rounded-3 bg-body flex-grow-1">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-calendar-days text-danger me-2"></i>Milestones & Dates</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="info-label">Confirmation Date</div>
                                            <div class="info-value">{{ $employee->confirmation_date ? date('M d, Y', strtotime($employee->confirmation_date)) : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Probation End Date</div>
                                            <div class="info-value">{{ $employee->probation_end_date ? date('M d, Y', strtotime($employee->probation_end_date)) : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Notice Period</div>
                                            <div class="info-value">{{ $employee->notice_period ? $employee->notice_period . ' Days' : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Leaving Date</div>
                                            <div class="info-value">{{ $employee->date_of_leaving ? date('M d, Y', strtotime($employee->date_of_leaving)) : 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Compensation & Statutory Benefits Pane -->
                <div class="tab-pane fade" id="compensation-pane" role="tabpanel">
                    <div class="row g-4">
                        <!-- Financial Details Card -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-coins text-warning me-2"></i>Financial & Statutory Details</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="info-label">Basic Monthly Salary</div>
                                            <div class="info-value fs-5 text-success fw-bold font-monospace">
                                                {{ $employee->salary ? '$' . number_format((float)$employee->salary, 2) : '$0.00' }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Corporate Bank Account</div>
                                            <div class="info-value">
                                                <code class="text-primary">{{ $employee->corporate_bank_account ?? 'N/A' }}</code>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Provident Fund (PF)</div>
                                            <div class="info-value">
                                                @if($employee->pf_opted == 1)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5"><i class="fa-solid fa-check-double me-1"></i>Opted In</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill fs-9 px-2 py-0.5">Opted Out</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Health Insurance</div>
                                            <div class="info-value">
                                                @if($employee->health_ins_opted == 1)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5"><i class="fa-solid fa-check-double me-1"></i>Opted In</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill fs-9 px-2 py-0.5">Opted Out</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Earned Leave Balance</div>
                                            <div class="info-value"><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5"><i class="fa-solid fa-calendar-check me-1"></i>{{ $employee->earned_leave ?? 0 }} Days</span></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-label">Casual Leave Balance</div>
                                            <div class="info-value"><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5"><i class="fa-solid fa-calendar-check me-1"></i>{{ $employee->casual_leave ?? 0 }} Days</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Deposit Accounts Card -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-building-columns text-primary me-2"></i>Direct Deposit Accounts</h6>
                                    @can('edit.employees')
                                        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#addBankModal">
                                            <i class="fa-solid fa-plus me-1"></i> Add Bank
                                        </button>
                                    @endcan
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0 fs-8">
                                            <thead class="table-light border-bottom">
                                                <tr>
                                                    <th class="ps-3 text-nowrap" style="width: 80px;">Action</th>
                                                    <th>Account Title</th>
                                                    <th>Bank Name</th>
                                                    <th>Account Number</th>
                                                    <th>Branch / Code</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employee->employeeBankaccounts as $bank)
                                                    <tr>
                                                        <td class="ps-3">
                                                            @can('delete.employees')
                                                                <form method="POST" action="{{ route('employee-bankaccounts.destroy', $bank->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove bank account?');">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Bank Account"><i class="fa-solid fa-trash"></i></button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                        <td class="fw-semibold text-body-emphasis">{{ $bank->account_title }}</td>
                                                        <td class="text-body-secondary">{{ $bank->bank_name }}</td>
                                                        <td><code class="text-primary fw-bold">{{ $bank->account_number }}</code></td>
                                                        <td class="text-body-secondary">{{ $bank->bank_branch ?? 'N/A' }} ({{ $bank->bank_code ?? 'N/A' }})</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="p-0">
                                                            <x-empty-state 
                                                                icon="fa-solid fa-building-columns" 
                                                                title="No Bank Accounts" 
                                                                description="No direct deposit accounts registered."
                                                            />
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Documents & Contracts Pane -->
                <div class="tab-pane fade" id="documents-pane" role="tabpanel">
                    <!-- Standard Documents Grid -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border border-subtle rounded-3 bg-body p-3 d-flex flex-row align-items-center gap-3">
                                <div class="avatar-md rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="fa-solid fa-file-pdf fs-4"></i></div>
                                <div class="flex-grow-1">
                                    <div class="info-label">Curriculum Vitae (Resume)</div>
                                    <div class="pt-1">
                                        @if($employee->resume)
                                            <a href="{{ asset('uploads/resumes/' . $employee->resume) }}" target="_blank" class="btn btn-sm btn-outline-primary px-2.5 rounded-2 fw-semibold">
                                                <i class="fa-solid fa-file-arrow-down me-1"></i>Download Resume
                                            </a>
                                        @else
                                            <span class="text-body-secondary small italic">No resume uploaded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border border-subtle rounded-3 bg-body p-3 d-flex flex-row align-items-center gap-3">
                                <div class="avatar-md rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="fa-solid fa-file-contract fs-4"></i></div>
                                <div class="flex-grow-1">
                                    <div class="info-label">Key Responsibility Areas (KRA)</div>
                                    <div class="pt-1 d-flex align-items-center gap-2 flex-wrap">
                                        @if($employee->kra_doc)
                                            <a href="{{ asset('uploads/kra/' . $employee->kra_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary px-2.5 rounded-2 fw-semibold" title="Download KRA Document">
                                                <i class="fa-solid fa-file-arrow-down me-1"></i>Download
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary px-2 rounded-2" onclick="document.getElementById('uploadKraModal').showModal()" title="Upload New / Replace KRA">
                                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            </button>
                                        @else
                                            <span class="text-body-secondary small italic me-1">No doc</span>
                                            <button type="button" class="btn btn-sm btn-primary px-2.5 rounded-2 fw-semibold" onclick="document.getElementById('uploadKraModal').showModal()">
                                                <i class="fa-solid fa-cloud-arrow-up me-1"></i>Upload KRA
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border border-subtle rounded-3 bg-body p-3 d-flex flex-row align-items-center gap-3">
                                <div class="avatar-md rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="fa-solid fa-chart-line fs-4"></i></div>
                                <div class="flex-grow-1">
                                    <div class="info-label">Performance Indicators (KPI)</div>
                                    <div class="pt-1 d-flex align-items-center gap-2 flex-wrap">
                                        @if($employee->kpi_doc)
                                            <a href="{{ asset('uploads/kpi/' . $employee->kpi_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary px-2.5 rounded-2 fw-semibold" title="Download KPI Document">
                                                <i class="fa-solid fa-file-arrow-down me-1"></i>Download
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary px-2 rounded-2" onclick="document.getElementById('uploadKpiModal').showModal()" title="Upload New / Replace KPI">
                                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            </button>
                                        @else
                                            <span class="text-body-secondary small italic me-1">No doc</span>
                                            <button type="button" class="btn btn-sm btn-warning text-dark px-2.5 rounded-2 fw-semibold" onclick="document.getElementById('uploadKpiModal').showModal()">
                                                <i class="fa-solid fa-cloud-arrow-up me-1"></i>Upload KPI
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Documents List -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-folder-open text-primary me-2"></i>Document Repository</h6>
                                    @can('edit.employees')
                                        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                            <i class="fa-solid fa-plus me-1"></i> Upload
                                        </button>
                                    @endcan
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0 fs-8">
                                            <thead class="table-light border-bottom">
                                                <tr>
                                                    <th class="ps-3 text-nowrap" style="width: 80px;">Action</th>
                                                    <th>Document Type</th>
                                                    <th>Title</th>
                                                    <th>File</th>
                                                    <th>Expiry Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employee->employeeDocuments as $doc)
                                                    <tr>
                                                        <td class="ps-3">
                                                            @can('delete.employees')
                                                                <form method="POST" action="{{ route('employee-documents.destroy', $doc->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove document?');">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Document"><i class="fa-solid fa-trash"></i></button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1" @if($doc->documentType && $doc->documentType->company) title="Company: {{ $doc->documentType->company->name }}" @endif>
                                                                <i class="fa-solid fa-file-lines me-1"></i>{{ $doc->documentType->document_type ?? 'General Document' }}
                                                            </span>
                                                        </td>

                                                        <td class="fw-semibold text-body-emphasis">{{ $doc->title }}</td>
                                                        <td>
                                                            @if($doc->document_file)
                                                                <a href="{{ asset('uploads/documents/' . $doc->document_file) }}" target="_blank" class="btn btn-sm btn-outline-primary px-2.5 rounded-2 fw-semibold">
                                                                    <i class="fa-solid fa-file-arrow-down me-1"></i> Download
                                                                </a>
                                                            @else
                                                                <span class="text-body-secondary">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-body-secondary">{{ $doc->date_of_expiry ? date('M d, Y', strtotime($doc->date_of_expiry)) : 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="p-0">
                                                            <x-empty-state 
                                                                icon="fa-solid fa-folder-open" 
                                                                title="No Documents" 
                                                                description="No documents registered."
                                                            />
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Contracts List -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-file-contract text-warning me-2"></i>Employment Contracts</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0 fs-8">
                                            <thead class="table-light border-bottom">
                                                <tr>
                                                    <th class="ps-3">Contract Title</th>
                                                    <th>Duration</th>
                                                    <th>Designation</th>
                                                    <th class="pe-3 text-end">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employee->employeeContracts as $contract)
                                                    <tr>
                                                        <td class="fw-semibold text-body-emphasis ps-3">{{ $contract->title ?? 'Employment Agreement' }}</td>
                                                        <td class="text-body-secondary">{{ $contract->start_date ? date('M Y', strtotime($contract->start_date)) : 'N/A' }} - {{ $contract->end_date ? date('M Y', strtotime($contract->end_date)) : 'Present' }}</td>
                                                        <td class="text-body-secondary">{{ $contract->designation->designation_name ?? 'Staff' }}</td>
                                                        <td class="text-end pe-3"><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5">Active</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="p-0">
                                                            <x-empty-state 
                                                                icon="fa-solid fa-file-contract" 
                                                                title="No Contracts" 
                                                                description="No active contracts found."
                                                            />
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: Qualifications & Work History Pane -->
                <div class="tab-pane fade" id="qualifications-pane" role="tabpanel">
                    <div class="row g-4">
                        <!-- Qualifications -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-graduation-cap text-primary me-2"></i>Educational Qualifications</h6>
                                    @can('edit.employees')
                                        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#addQualificationModal">
                                            <i class="fa-solid fa-plus me-1"></i> Add Degree
                                        </button>
                                    @endcan
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0 fs-8">
                                            <thead class="table-light border-bottom">
                                                <tr>
                                                    <th class="ps-3 text-nowrap" style="width: 80px;">Action</th>
                                                    <th>Degree / Qualification</th>
                                                    <th>Specialization</th>
                                                    <th>Year Range</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employee->employeeQualifications as $qual)
                                                    <tr>
                                                        <td class="ps-3">
                                                            @can('delete.employees')
                                                                <form method="POST" action="{{ route('employee-qualifications.destroy', $qual->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove qualification?');">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Qualification"><i class="fa-solid fa-trash"></i></button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                        <td class="fw-semibold text-body-emphasis">{{ $qual->name }}</td>
                                                        <td class="text-body-secondary">{{ $qual->specialization ?? 'General' }}</td>
                                                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5">{{ $qual->from_year ?? 'N/A' }} - {{ $qual->to_year ?? 'N/A' }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="p-0">
                                                            <x-empty-state 
                                                                icon="fa-solid fa-graduation-cap" 
                                                                title="No Qualifications" 
                                                                description="No educational records added."
                                                            />
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Prior Work History -->
                        <div class="col-lg-6">
                            <div class="card border border-subtle rounded-3 bg-body h-100">
                                <div class="card-header border-bottom border-subtle bg-transparent py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-clock-rotate-left text-success me-2"></i>Prior Work Experience</h6>
                                    @can('edit.employees')
                                        <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                                            <i class="fa-solid fa-plus me-1"></i> Add Experience
                                        </button>
                                    @endcan
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0 fs-8">
                                            <thead class="table-light border-bottom">
                                                <tr>
                                                    <th class="ps-3 text-nowrap" style="width: 80px;">Action</th>
                                                    <th>Company Name</th>
                                                    <th>Designation / Post</th>
                                                    <th>Duration</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employee->employeeWorkExperiences as $exp)
                                                    <tr>
                                                        <td class="ps-3">
                                                            @can('delete.employees')
                                                                <form method="POST" action="{{ route('employee-experiences.destroy', $exp->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove experience?');">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Experience"><i class="fa-solid fa-trash"></i></button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                        <td class="fw-semibold text-body-emphasis">{{ $exp->company_name }}</td>
                                                        <td class="text-body-secondary">{{ $exp->post }}</td>
                                                        <td class="small text-body-secondary">
                                                            {{ date('M d, Y', strtotime($exp->from_date)) }} - 
                                                            {{ $exp->to_date ? date('M d, Y', strtotime($exp->to_date)) : 'Present' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="p-0">
                                                            <x-empty-state 
                                                                icon="fa-solid fa-briefcase" 
                                                                title="No Prior Experiences" 
                                                                description="No prior work experience added."
                                                            />
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 6: Emergency Contacts Pane -->
                <div class="tab-pane fade" id="contacts-pane" role="tabpanel">
                    <div class="card border border-subtle rounded-3 bg-body">
                        <div class="card-header border-bottom border-subtle bg-transparent py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-users text-danger me-2"></i>Emergency Contacts & Next of Kin</h6>
                            @can('edit.employees')
                                <button type="button" class="btn btn-sm btn-primary fw-semibold px-3 rounded-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#addContactModal">
                                    <i class="fa-solid fa-user-plus me-1"></i> Add Contact
                                </button>
                            @endcan
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 fs-8">
                                    <thead class="table-light border-bottom">
                                        <tr>
                                            <th class="ps-3 text-nowrap" style="width: 80px;">Action</th>
                                            <th>Contact Name</th>
                                            <th>Relationship</th>
                                            <th>Mobile Phone</th>
                                            <th class="pe-3 text-end">Contact Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employee->employeeContacts as $contact)
                                            <tr>
                                                <td class="ps-3">
                                                    @can('delete.employees')
                                                        <form method="POST" action="{{ route('employee-contacts.destroy', $contact->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove contact?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Contact"><i class="fa-solid fa-trash"></i></button>
                                                        </form>
                                                    @endcan
                                                </td>
                                                <td class="fw-semibold text-body-emphasis">{{ $contact->contact_name }}</td>
                                                <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold fs-9 px-2 py-0.5">{{ $contact->relation }}</span></td>
                                                <td><i class="fa-solid fa-phone text-success me-1"></i><a href="tel:{{ $contact->mobile_phone }}" class="text-body-emphasis text-decoration-none">{{ $contact->mobile_phone }}</a></td>
                                                <td class="text-end pe-3">
                                                    @if($contact->is_primary)
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill me-1 fs-9 px-2 py-0.5"><i class="fa-solid fa-star me-1"></i>Primary</span>
                                                    @endif
                                                    @if($contact->is_dependent)
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill fs-9 px-2 py-0.5"><i class="fa-solid fa-child me-1"></i>Dependent</span>
                                                    @endif
                                                    @if(!$contact->is_primary && !$contact->is_dependent)
                                                        <span class="text-body-secondary small">Standard Contact</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="p-0">
                                                    <x-empty-state 
                                                        icon="fa-solid fa-phone-slash" 
                                                        title="No Emergency Contacts" 
                                                        description="No emergency contacts registered."
                                                    />
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Add Document -->
<x-form-modal id="addDocumentModal" title="Upload Employee Document" :action="route('employee-documents.store')" submitText="Upload Document">
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Document Type <span class="text-danger">*</span></label>
        <select name="document_type_id" class="form-select form-select-sm select-search" required>
            <option value="">Select Document Type...</option>
            @foreach($documentTypes ?? [] as $type)
                <option value="{{ $type->document_type_id ?? $type->id }}">
                    {{ $type->name_with_company }}
                </option>
            @endforeach

        </select>
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Document Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control form-control-sm" required placeholder="e.g. Passport Copy / Degree Certificate">
    </div>

    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Expiry Date</label>
        <input type="date" name="date_of_expiry" class="form-control form-control-sm">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Alert Email</label>
        <input type="email" name="notification_email" class="form-control form-control-sm" value="{{ $employee->email }}">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Select File (PDF, Image, Doc) <span class="text-danger">*</span></label>
        <input type="file" name="document_file" class="form-control form-control-sm" required>
    </div>
</x-form-modal>

<!-- Modal 2: Add Emergency Contact -->
<x-form-modal id="addContactModal" title="Add Emergency Contact" :action="route('employee-contacts.store')" submitText="Save Contact" submitVariant="primary">
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Contact Name <span class="text-danger">*</span></label>
        <input type="text" name="contact_name" class="form-control form-control-sm" required placeholder="Full Name">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Relation <span class="text-danger">*</span></label>
        <input type="text" name="relation" class="form-control form-control-sm" required placeholder="e.g. Spouse / Parent / Sibling">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Mobile Phone <span class="text-danger">*</span></label>
        <input type="text" name="mobile_phone" class="form-control form-control-sm" required placeholder="+1 (555) 000-0000">
    </div>
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="primaryCheck">
        <label class="form-check-label fs-8 text-body-emphasis" for="primaryCheck">Set as Primary Emergency Contact</label>
    </div>
</x-form-modal>

<!-- Modal 3: Add Bank Account -->
<x-form-modal id="addBankModal" title="Add Direct Deposit Bank Account" :action="route('employee-bankaccounts.store')" submitText="Save Bank Account">
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Account Title <span class="text-danger">*</span></label>
        <input type="text" name="account_title" class="form-control form-control-sm" required value="{{ $employee->first_name }} {{ $employee->last_name }}">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Bank Name <span class="text-danger">*</span></label>
        <input type="text" name="bank_name" class="form-control form-control-sm" required placeholder="e.g. Chase / HSBC">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Account Number <span class="text-danger">*</span></label>
        <input type="text" name="account_number" class="form-control form-control-sm" required placeholder="Account Number">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Bank Branch / IFSC Code</label>
        <input type="text" name="bank_branch" class="form-control form-control-sm" placeholder="Branch Name or Swift Code">
    </div>
</x-form-modal>

<!-- Modal 4: Add Qualification -->
<x-form-modal id="addQualificationModal" title="Add Educational Qualification" :action="route('employee-qualifications.store')" submitText="Save Qualification">
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Degree / Qualification Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. B.Sc. Computer Science">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Specialization</label>
        <input type="text" name="specialization" class="form-control form-control-sm" placeholder="e.g. Software Engineering">
    </div>
    <div class="row g-2">
        <div class="col-6">
            <label class="form-label fs-8 fw-semibold">From Year</label>
            <input type="text" name="from_year" class="form-control form-control-sm" placeholder="2018">
        </div>
        <div class="col-6">
            <label class="form-label fs-8 fw-semibold">To Year</label>
            <input type="text" name="to_year" class="form-control form-control-sm" placeholder="2022">
        </div>
    </div>
</x-form-modal>

<!-- Modal 5: Add Experience -->
<x-form-modal id="addExperienceModal" title="Add Work Experience" :action="route('employee-experiences.store')" submitText="Save Experience" submitVariant="primary">
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Company Name <span class="text-danger">*</span></label>
        <input type="text" name="company_name" class="form-control form-control-sm" required placeholder="Previous Employer">
    </div>
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Post / Job Title <span class="text-danger">*</span></label>
        <input type="text" name="post" class="form-control form-control-sm" required placeholder="e.g. Senior Developer">
    </div>
    <div class="row g-2">
        <div class="col-6">
            <label class="form-label fs-8 fw-semibold">From Date <span class="text-danger">*</span></label>
            <input type="date" name="from_date" class="form-control form-control-sm" required>
        </div>
        <div class="col-6">
            <label class="form-label fs-8 fw-semibold">To Date</label>
            <input type="date" name="to_date" class="form-control form-control-sm">
        </div>
    </div>
</x-form-modal>

<!-- Modal 6: Upload KRA Document -->
<x-form-modal id="uploadKraModal" title="Upload Key Responsibility Areas (KRA)" :action="route('employees.upload-kra', $employee->id)" submitText="Upload KRA Document" submitVariant="primary">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Select KRA Document (PDF, DOC, DOCX) <span class="text-danger">*</span></label>
        <input type="file" name="kra_doc" class="form-control form-control-sm" required accept=".pdf,.doc,.docx,.jpg,.png,.webp">
        <div class="form-text fs-9 text-body-secondary mt-1">Upload the formal job description and key responsibility areas document for this employee (max 10MB).</div>
    </div>
    @if($employee->kra_doc)
        <div class="alert alert-info py-2 px-3 fs-8 mb-0 rounded-2">
            <i class="fa-solid fa-circle-info me-1"></i> Current file: <strong>{{ $employee->kra_doc }}</strong>. Uploading a new file will replace this document.
        </div>
    @endif
</x-form-modal>

<!-- Modal 7: Upload KPI Document -->
<x-form-modal id="uploadKpiModal" title="Upload Performance Indicators (KPI)" :action="route('employees.upload-kpi', $employee->id)" submitText="Upload KPI Document" submitVariant="warning">
    <div class="mb-3">
        <label class="form-label fs-8 fw-semibold">Select KPI Document (PDF, DOC, DOCX) <span class="text-danger">*</span></label>
        <input type="file" name="kpi_doc" class="form-control form-control-sm" required accept=".pdf,.doc,.docx,.jpg,.png,.webp">
        <div class="form-text fs-9 text-body-secondary mt-1">Upload the performance evaluation metrics and key indicators document for this employee (max 10MB).</div>
    </div>
    @if($employee->kpi_doc)
        <div class="alert alert-info py-2 px-3 fs-8 mb-0 rounded-2">
            <i class="fa-solid fa-circle-info me-1"></i> Current file: <strong>{{ $employee->kpi_doc }}</strong>. Uploading a new file will replace this document.
        </div>
    @endif
</x-form-modal>
@endsection
