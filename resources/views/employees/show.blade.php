@extends('layouts.app')

@section('title', $employee->first_name . ' ' . $employee->last_name . ' - Profile')
@section('page_title', 'Employee Profile Details')

@push('css')
<style>
    /* Premium Profile Design System */
    .profile-hero-card {
        background: linear-gradient(135deg, var(--card-bg) 60%, rgba(27, 132, 255, 0.05) 100%);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }
    
    .profile-hero-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(27, 132, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .detail-card {
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background-color: var(--card-bg);
    }
    
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .detail-card .card-header {
        background-color: rgba(0, 0, 0, 0.01);
        border-bottom: 1px solid var(--card-border);
        padding: 12px 18px;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .info-value.empty-val {
        color: var(--text-muted);
        font-weight: 400;
        font-style: italic;
    }

    /* Social Badge Styling */
    .social-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: white !important;
        font-size: 1.1rem;
        transition: transform 0.2s ease, filter 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .social-btn:hover {
        transform: scale(1.1);
        filter: brightness(1.1);
    }
    .social-linkedin { background-color: #0077b5; }
    .social-skype { background-color: #00aff0; }
    .social-twitter { background-color: #1da1f2; }
    .social-facebook { background-color: #1877f2; }
    .social-instagram { background-color: #c13584; }
    .social-youtube { background-color: #ff0000; }
    .social-pinterest { background-color: #bd081c; }
    .social-blogger { background-color: #f57d00; }
    .social-google { background-color: #dd4b39; }

    /* Custom Line Tabs */
    .nav-line-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        background: none;
        color: var(--text-muted);
        padding: 10px 16px;
        transition: all 0.2s ease;
    }
    .nav-line-tabs .nav-link:hover {
        color: var(--bs-primary);
        border-bottom-color: var(--card-border);
    }
    .nav-line-tabs .nav-link.active {
        color: var(--bs-primary);
        border-bottom-color: var(--bs-primary);
        background: none;
    }
</style>
@endpush

@section('content')
<!-- Page Header Banner -->
<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
        <p class="text-body-secondary small mb-0">Employee Code: <span class="fw-bold text-primary">{{ (!empty($employee->employee_id) && $employee->employee_id !== '0') ? $employee->employee_id : 'EMP-' . sprintf('%04d', $employee->id) }}</span></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="{{ route('leaves.index', ['employee_id' => $employee->id]) }}" class="btn btn-light-info btn-sm me-2">
            <i class="fa-solid fa-calendar-plus me-1"></i>Request Leave
        </a>
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-light-warning btn-sm me-2">
            <i class="fa-solid fa-pen-to-square me-1"></i>Edit Profile
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-light-primary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to Directory
        </a>
    </div>
</div>

<!-- Employee Profile Hero Card -->
<div class="profile-hero-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <div class="position-relative">
            @if($employee->profile_picture && file_exists(public_path('uploads/profile/' . $employee->profile_picture)))
                <img src="{{ asset('uploads/profile/' . $employee->profile_picture) }}" alt="{{ $employee->first_name }}" class="rounded-circle border border-3 border-white shadow" style="width: 100px; height: 100px; object-fit: cover;">
            @else
                <div class="btn btn-light-primary rounded-circle p-3 fs-1 shadow-sm d-flex align-items-center justify-content-center border border-3 border-white" style="width: 100px; height: 100px;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            @endif
            @if($employee->is_active == 1)
                <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 18px; height: 18px; display: inline-block;" title="Active Status"></span>
            @endif
        </div>
        <div class="flex-grow-1 text-center text-md-start">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                <h3 class="card-title fs-4 mb-0 fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                @switch((int)$employee->is_active)
                    @case(1)<span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>@break
                    @case(2)<span class="badge badge-light-danger"><i class="fa-solid fa-ban me-1"></i>Terminated</span>@break
                    @case(3)<span class="badge badge-light-warning"><i class="fa-solid fa-person-walking-arrow-right me-1"></i>Left</span>@break
                    @case(4)<span class="badge bg-dark text-white"><i class="fa-solid fa-user-ninja me-1"></i>Abscond</span>@break
                    @case(5)<span class="badge badge-light-secondary"><i class="fa-solid fa-user-slash me-1"></i>Disable</span>@break
                    @case(0)<span class="badge badge-light-info"><i class="fa-solid fa-file-signature me-1"></i>Resigned</span>@break
                    @default<span class="badge badge-light-secondary">Unknown</span>
                @endswitch
                
                @if($employee->probation_status == 1)
                    <span class="badge badge-light-warning"><i class="fa-solid fa-user-clock me-1"></i>Under Probation</span>
                @else
                    <span class="badge badge-light-success"><i class="fa-solid fa-shield-halved me-1"></i>Permanent</span>
                @endif
            </div>
            <div class="text-body-secondary fw-medium mb-2 fs-6">
                <span class="text-primary fw-bold">{{ $employee->designation->designation_name ?? $employee->designation->name ?? 'Staff Member' }}</span> 
                <span class="text-muted">•</span> 
                <span>{{ $employee->department->department_name ?? $employee->department->name ?? 'General Department' }}</span>
                @if(!empty($employee->sub_department))
                    <span class="text-muted">({{ $employee->sub_department }})</span>
                @endif
            </div>
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3 small text-body-secondary">
                <span><i class="fa-solid fa-envelope me-1 text-primary"></i>{{ $employee->email }}</span>
                @if($employee->contact_no)
                    <span><i class="fa-solid fa-phone me-1 text-success"></i>{{ $employee->contact_no }}</span>
                @endif
                @if($employee->date_of_joining)
                    <span><i class="fa-solid fa-calendar-check me-1 text-warning"></i>Joined {{ date('M d, Y', strtotime($employee->date_of_joining)) }}</span>
                @endif
                @if($employee->reporting_location)
                    <span><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $employee->reporting_location }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Profile Navigation Tabs -->
<ul class="nav nav-tabs nav-line-tabs mb-4" id="profileTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-pane" type="button">
            <i class="fa-solid fa-id-card me-1"></i>Personal Profile
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-pane" type="button">
            <i class="fa-solid fa-briefcase me-1"></i>Job & Shift Specs
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="compensation-tab" data-bs-toggle="tab" data-bs-target="#compensation-pane" type="button">
            <i class="fa-solid fa-wallet me-1"></i>Compensation & Leaves
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-pane" type="button">
            <i class="fa-solid fa-folder-open me-1"></i>Documents & Contracts ({{ $employee->documents->count() + $employee->employeeContracts->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="qualifications-tab" data-bs-toggle="tab" data-bs-target="#qualifications-pane" type="button">
            <i class="fa-solid fa-graduation-cap me-1"></i>Qualifications & History ({{ $employee->employeeQualifications->count() + $employee->employeeWorkExperiences->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts-pane" type="button">
            <i class="fa-solid fa-phone-flip me-1"></i>Emergency Contacts ({{ $employee->employeeContacts->count() }})
        </button>
    </li>
</ul>

<div class="tab-content" id="profileTabsContent">
    <!-- Tab 1: Personal Profile Pane -->
    <div class="tab-pane fade show active" id="personal-pane" role="tabpanel">
        <div class="row g-4">
            <!-- Col 1: Personal Details & Demographics -->
            <div class="col-lg-6">
                <div class="card detail-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-user me-2 text-primary"></i>Personal Demographics</h4>
                        <i class="fa-solid fa-shield-halved text-muted" title="Authorized Access Only"></i>
                    </div>
                    <div class="card-body">
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
                                <div class="info-value"><span class="badge bg-danger-light text-danger fw-bold"><i class="fa-solid fa-droplet me-1"></i>{{ $employee->blood_group ?? 'O+' }}</span></div>
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
                                <div class="info-value"><code>{{ $employee->pan_number ?? 'N/A' }}</code></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Aadhar Card Number</div>
                                <div class="info-value"><code>{{ $employee->aadhar_no ?? 'N/A' }}</code></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: Contacts & Addresses -->
            <div class="col-lg-6 d-flex flex-column gap-4">
                <!-- Card 1: Contact Details -->
                <div class="card detail-card flex-grow-1">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-address-book text-success me-2"></i>Contact Details</h4></div>
                    <div class="card-body">
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
                                <div class="info-label">Corporate Account Email</div>
                                <div class="info-value"><i class="fa-solid fa-envelope text-primary me-1"></i>{{ $employee->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Addresses -->
                <div class="card detail-card flex-grow-1">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-map-location-dot text-danger me-2"></i>Addresses</h4></div>
                    <div class="card-body">
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

                <!-- Card 3: Social Profile Links -->
                <div class="card detail-card">
                    <div class="card-body d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        @if($employee->linkdedin_link)
                            <a href="{{ $employee->linkdedin_link }}" target="_blank" class="social-btn social-linkedin" title="LinkedIn Profile"><i class="fa-brands fa-linkedin-in"></i></a>
                        @endif
                        @if($employee->skype_id)
                            <a href="skype:{{ $employee->skype_id }}?chat" class="social-btn social-skype" title="Skype Chat"><i class="fa-brands fa-skype"></i></a>
                        @endif
                        @if($employee->twitter_link)
                            <a href="{{ $employee->twitter_link }}" target="_blank" class="social-btn social-twitter" title="Twitter Handle"><i class="fa-brands fa-x-twitter"></i></a>
                        @endif
                        @if($employee->facebook_link)
                            <a href="{{ $employee->facebook_link }}" target="_blank" class="social-btn social-facebook" title="Facebook profile"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        @if($employee->instagram_link)
                            <a href="{{ $employee->instagram_link }}" target="_blank" class="social-btn social-instagram" title="Instagram profile"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                        @if($employee->youtube_link)
                            <a href="{{ $employee->youtube_link }}" target="_blank" class="social-btn social-youtube" title="YouTube Channel"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if($employee->pinterest_link)
                            <a href="{{ $employee->pinterest_link }}" target="_blank" class="social-btn social-pinterest" title="Pinterest profile"><i class="fa-brands fa-pinterest"></i></a>
                        @endif
                        @if($employee->blogger_link)
                            <a href="{{ $employee->blogger_link }}" target="_blank" class="social-btn social-blogger" title="Blogger profile"><i class="fa-solid fa-blog"></i></a>
                        @endif
                        @if($employee->google_plus_link)
                            <a href="{{ $employee->google_plus_link }}" target="_blank" class="social-btn social-google" title="Google Plus Profile"><i class="fa-brands fa-google-plus-g"></i></a>
                        @endif
                        @if(!$employee->linkdedin_link && !$employee->skype_id && !$employee->twitter_link && !$employee->facebook_link && !$employee->instagram_link && !$employee->youtube_link && !$employee->pinterest_link)
                            <span class="text-muted small italic"><i class="fa-solid fa-address-card me-1"></i>No social media handles registered.</span>
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
                <div class="card detail-card h-100">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-sitemap text-primary me-2"></i>Job Specifications</h4></div>
                    <div class="card-body">
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
                                <div class="info-value"><span class="badge bg-primary-light text-primary fw-bold">{{ $employee->employment_type ?? 'Full Time' }}</span></div>
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
                                <div class="info-label">Referral / Source ID</div>
                                <div class="info-value">
                                    @if($employee->ref_emp_id)
                                        <a href="{{ route('employees.show', $employee->ref_emp_id) }}" class="fw-bold text-decoration-none">EMP-{{ sprintf('%04d', $employee->ref_emp_id) }}</a>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Rejoined Employee</div>
                                <div class="info-value">
                                    @if($employee->has_rejoined)
                                        <span class="badge badge-light-success"><i class="fa-solid fa-arrow-rotate-right me-1"></i>Yes (Rejoined)</span>
                                    @else
                                        No
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Vehicle Registered</div>
                                <div class="info-value">
                                    @if($employee->vehicle_no)
                                        <span class="fw-semibold">{{ $employee->vehicle_no }}</span> <span class="text-muted">({{ $employee->vehicle_type ?? 'Private' }})</span>
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
                <div class="card detail-card flex-grow-1">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-users-gear text-info me-2"></i>Reporting Line</h4></div>
                    <div class="card-body">
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
                <div class="card detail-card flex-grow-1">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-clock text-warning me-2"></i>Shift Specifications</h4></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="info-label">Assigned Shift Name</div>
                                <div class="info-value"><span class="badge badge-light-warning fw-bold fs-7"><i class="fa-solid fa-business-time me-1"></i>{{ $employee->officeShift->shift_name ?? 'Standard Office Shift' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Milestones & Dates -->
                <div class="card detail-card flex-grow-1">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-business-time text-danger me-2"></i>Milestones & Dates</h4></div>
                    <div class="card-body">
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
                                <div class="info-label">Resignation Date</div>
                                <div class="info-value">{{ $employee->resign_date ? date('M d, Y', strtotime($employee->resign_date)) : 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Date of Leaving</div>
                                <div class="info-value">{{ $employee->date_of_leaving ? date('M d, Y', strtotime($employee->date_of_leaving)) : 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Notice Period Required</div>
                                <div class="info-value">{{ $employee->notice_period ? $employee->notice_period . ' Days' : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 3: Compensation & statutory Benefits Pane -->
    <div class="tab-pane fade" id="compensation-pane" role="tabpanel">
        <div class="row g-4">
            <!-- Financial Details Card -->
            <div class="col-lg-6">
                <div class="card detail-card h-100">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-coins text-warning me-2"></i>Financial & Statutory Details</h4></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="info-label">Basic Monthly Salary</div>
                                <div class="info-value fs-5 text-success fw-bold">
                                    {{ $employee->salary ? '$' . number_format((float)$employee->salary, 2) : '$0.00' }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Corporate Bank Account</div>
                                <div class="info-value">
                                    <code>{{ $employee->corporate_bank_account ?? 'N/A' }}</code>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Provident Fund (PF) Opted</div>
                                <div class="info-value">
                                    @if($employee->pf_opted == 1)
                                        <span class="badge badge-light-success"><i class="fa-solid fa-check-double me-1"></i>Opted In</span>
                                    @else
                                        <span class="badge badge-light-secondary">Opted Out</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Health Insurance Opted</div>
                                <div class="info-value">
                                    @if($employee->health_ins_opted == 1)
                                        <span class="badge badge-light-success"><i class="fa-solid fa-check-double me-1"></i>Opted In</span>
                                    @else
                                        <span class="badge badge-light-secondary">Opted Out</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Earned Leave Balance</div>
                                <div class="info-value"><span class="badge badge-light-primary fw-bold"><i class="fa-solid fa-calendar-check me-1"></i>{{ $employee->earned_leave ?? 0 }} Days</span></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Casual Leave Balance</div>
                                <div class="info-value"><span class="badge badge-light-primary fw-bold"><i class="fa-solid fa-calendar-check me-1"></i>{{ $employee->casual_leave ?? 0 }} Days</span></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Other Leaves Taken</div>
                                <div class="info-value"><span class="badge badge-light-secondary fw-semibold">{{ $employee->other_leaves_taken_days ?? 0 }} Days</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Direct Deposit Accounts Card -->
            <div class="col-lg-6">
                <div class="card detail-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-building-columns text-primary me-2"></i>Direct Deposit Bank Accounts</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBankModal">
                            <i class="fa-solid fa-plus me-1"></i>Add Bank
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Account Title</th>
                                        <th>Bank Name</th>
                                        <th>Account Number</th>
                                        <th>Branch / Code</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->employeeBankaccounts as $bank)
                                        <tr>
                                            <td class="fw-semibold text-body-emphasis ps-3">{{ $bank->account_title }}</td>
                                            <td>{{ $bank->bank_name }}</td>
                                            <td><code class="text-primary fw-bold">{{ $bank->account_number }}</code></td>
                                            <td>{{ $bank->bank_branch ?? 'N/A' }} ({{ $bank->bank_code ?? 'N/A' }})</td>
                                            <td class="text-end pe-3">
                                                <form method="POST" action="{{ route('employee-bankaccounts.destroy', $bank->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove bank account?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4 text-muted"><i class="fa-solid fa-building-columns fs-3 mb-2 d-block"></i>No deposit accounts registered.</td></tr>
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
        <!-- Specialized/Standard Docs Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card detail-card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="btn btn-light-danger p-3 rounded-circle fs-3"><i class="fa-solid fa-file-pdf"></i></div>
                    <div class="flex-grow-1">
                        <div class="info-label">Curriculum Vitae (Resume)</div>
                        <div class="pt-1">
                            @if($employee->resume)
                                <a href="{{ asset('uploads/resumes/' . $employee->resume) }}" target="_blank" class="btn btn-light-primary btn-sm fw-bold">
                                    <i class="fa-solid fa-file-arrow-down me-1"></i>Download Resume
                                </a>
                            @else
                                <span class="text-muted small italic">No resume uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card detail-card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="btn btn-light-primary p-3 rounded-circle fs-3"><i class="fa-solid fa-file-contract"></i></div>
                    <div class="flex-grow-1">
                        <div class="info-label">Key Responsibility Areas (KRA)</div>
                        <div class="pt-1">
                            @if($employee->kra_doc)
                                <a href="{{ asset('uploads/kra/' . $employee->kra_doc) }}" target="_blank" class="btn btn-light-primary btn-sm fw-bold">
                                    <i class="fa-solid fa-file-arrow-down me-1"></i>Download KRA
                                </a>
                            @else
                                <span class="text-muted small italic">No KRA doc uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card detail-card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="btn btn-light-warning p-3 rounded-circle fs-3"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="flex-grow-1">
                        <div class="info-label">Performance Indicators (KPI)</div>
                        <div class="pt-1">
                            @if($employee->kpi_doc)
                                <a href="{{ asset('uploads/kpi/' . $employee->kpi_doc) }}" target="_blank" class="btn btn-light-primary btn-sm fw-bold">
                                    <i class="fa-solid fa-file-arrow-down me-1"></i>Download KPI
                                </a>
                            @else
                                <span class="text-muted small italic">No KPI doc uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Documents List -->
            <div class="col-lg-6">
                <div class="card detail-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-folder-open text-primary me-2"></i>General Document Repository</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                            <i class="fa-solid fa-plus me-1"></i>Upload Document
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Title</th>
                                        <th>File</th>
                                        <th>Expiry Date</th>
                                        <th>Alert Email</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->documents as $doc)
                                        <tr>
                                            <td class="fw-semibold text-body-emphasis ps-3">{{ $doc->title }}</td>
                                            <td>
                                                @if($doc->document_file)
                                                    <a href="{{ asset('uploads/documents/' . $doc->document_file) }}" target="_blank" class="btn btn-light-primary btn-sm py-1 px-2 fw-semibold">
                                                        <i class="fa-solid fa-file-arrow-down me-1"></i>Download
                                                    </a>
                                                @else
                                                    <span class="text-muted small">No file</span>
                                                @endif
                                            </td>
                                            <td>{{ $doc->date_of_expiry ? date('M d, Y', strtotime($doc->date_of_expiry)) : 'N/A' }}</td>
                                            <td>{{ $doc->notification_email ?? 'N/A' }}</td>
                                            <td class="text-end pe-3">
                                                <form method="POST" action="{{ route('employee-documents.destroy', $doc->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove this document?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4 text-muted"><i class="fa-solid fa-folder-open fs-3 mb-2 d-block"></i>No uploaded documents.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contracts List -->
            <div class="col-lg-6">
                <div class="card detail-card h-100">
                    <div class="card-header"><h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-file-signature text-success me-2"></i>Employment Contracts</h4></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Contract Title</th>
                                        <th>Designation</th>
                                        <th>Contract Type</th>
                                        <th>From - To Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->employeeContracts as $contract)
                                        <tr>
                                            <td class="fw-semibold text-body-emphasis ps-3">{{ $contract->title }}</td>
                                            <td>{{ $contract->designation->designation_name ?? $contract->designation->name ?? 'N/A' }}</td>
                                            <td><span class="badge badge-light-primary">{{ $contract->contractType->name ?? $contract->contractType->contract_type ?? 'N/A' }}</span></td>
                                            <td class="small text-muted">
                                                {{ date('M d, Y', strtotime($contract->from_date)) }} - 
                                                {{ $contract->to_date ? date('M d, Y', strtotime($contract->to_date)) : 'Ongoing' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa-solid fa-file-contract fs-3 mb-2 d-block"></i>No active contracts.</td></tr>
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
                <div class="card detail-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-graduation-cap text-primary me-2"></i>Educational Qualifications</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addQualificationModal">
                            <i class="fa-solid fa-plus me-1"></i>Add Education
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Degree / Qualification</th>
                                        <th>Specialization</th>
                                        <th>Year Range</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->employeeQualifications as $qual)
                                        <tr>
                                            <td class="fw-semibold text-body-emphasis ps-3">{{ $qual->name }}</td>
                                            <td>{{ $qual->specialization ?? 'General' }}</td>
                                            <td><span class="badge badge-light-primary">{{ $qual->from_year ?? 'N/A' }} - {{ $qual->to_year ?? 'N/A' }}</span></td>
                                            <td class="text-end pe-3">
                                                <form method="POST" action="{{ route('employee-qualifications.destroy', $qual->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove qualification?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa-solid fa-graduation-cap fs-3 mb-2 d-block"></i>No qualifications added.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prior Work History -->
            <div class="col-lg-6">
                <div class="card detail-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-history text-success me-2"></i>Prior Work Experience</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                            <i class="fa-solid fa-plus me-1"></i>Add Experience
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Company Name</th>
                                        <th>Designation / Post</th>
                                        <th>Duration</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->employeeWorkExperiences as $exp)
                                        <tr>
                                            <td class="fw-semibold text-body-emphasis ps-3">{{ $exp->company_name }}</td>
                                            <td>{{ $exp->post }}</td>
                                            <td class="small text-muted">
                                                {{ date('M d, Y', strtotime($exp->from_date)) }} - 
                                                {{ $exp->to_date ? date('M d, Y', strtotime($exp->to_date)) : 'Present' }}
                                            </td>
                                            <td class="text-end pe-3">
                                                <form method="POST" action="{{ route('employee-experiences.destroy', $exp->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove experience?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa-solid fa-briefcase fs-3 mb-2 d-block"></i>No prior work experiences added.</td></tr>
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
        <div class="card detail-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="headline-md mb-0 text-body-emphasis"><i class="fa-solid fa-users text-danger me-2"></i>Emergency Contacts & Next of Kin</h4>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContactModal">
                    <i class="fa-solid fa-user-plus me-1"></i>Add Contact
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Relation</th>
                                <th>Mobile Phone</th>
                                <th>Primary / Dependent Status</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->employeeContacts as $contact)
                                <tr>
                                    <td class="fw-semibold text-body-emphasis ps-3">{{ $contact->contact_name }}</td>
                                    <td><span class="badge badge-light-primary fw-semibold">{{ $contact->relation }}</span></td>
                                    <td><i class="fa-solid fa-phone text-success me-1"></i>{{ $contact->mobile_phone }}</td>
                                    <td>
                                        @if($contact->is_primary)
                                            <span class="badge badge-light-danger me-1"><i class="fa-solid fa-star me-1"></i>Primary Contact</span>
                                        @endif
                                        @if($contact->is_dependent)
                                            <span class="badge badge-light-info"><i class="fa-solid fa-child me-1"></i>Dependent</span>
                                        @endif
                                        @if(!$contact->is_primary && !$contact->is_dependent)
                                            <span class="text-muted small">Standard Contact</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <form method="POST" action="{{ route('employee-contacts.destroy', $contact->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove contact?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted"><i class="fa-solid fa-phone-slash fs-3 mb-2 d-block"></i>No emergency contacts registered.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Add Document -->
<div class="modal fade" id="addDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee-documents.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fa-solid fa-folder-open me-2 text-primary"></i>Upload Document</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Document Title *</label><input type="text" name="title" class="form-control" required placeholder="e.g. Passport Copy"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Expiry Date</label><input type="date" name="date_of_expiry" class="form-control"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Alert Email</label><input type="email" name="notification_email" class="form-control" value="{{ $employee->email }}"></div>
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Select File (PDF, Image, Doc) *</label><input type="file" name="document_file" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Upload Document</button></div>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Add Emergency Contact -->
<div class="modal fade" id="addContactModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee-contacts.store') }}">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2 text-danger"></i>Add Emergency Contact</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Contact Name *</label><input type="text" name="contact_name" class="form-control" required placeholder="Full Name"></div>
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Relation *</label><input type="text" name="relation" class="form-control" required placeholder="e.g. Spouse / Parent / Sibling"></div>
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Mobile Phone *</label><input type="text" name="mobile_phone" class="form-control" required placeholder="+1 (555) 000-0000"></div>
                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_primary" value="1" id="primaryCheck"><label class="form-check-label" for="primaryCheck">Set as Primary Emergency Contact</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Save Contact</button></div>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Add Bank Account -->
<div class="modal fade" id="addBankModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee-bankaccounts.store') }}">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fa-solid fa-building-columns me-2 text-primary"></i>Add Direct Deposit Bank Account</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Account Title *</label><input type="text" name="account_title" class="form-control" required value="{{ $employee->first_name }} {{ $employee->last_name }}"></div>
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Bank Name *</label><input type="text" name="bank_name" class="form-control" required placeholder="e.g. Chase / HSBC"></div>
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Account Number *</label><input type="text" name="account_number" class="form-control" required placeholder="Account Number"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Bank Branch / IFSC Code</label><input type="text" name="bank_branch" class="form-control" placeholder="Branch Name or Swift Code"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Save Bank Account</button></div>
            </div>
        </form>
    </div>
</div>

<!-- Modal 4: Add Qualification -->
<div class="modal fade" id="addQualificationModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee-qualifications.store') }}">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fa-solid fa-graduation-cap me-2 text-primary"></i>Add Qualification</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Degree / Qualification Name *</label><input type="text" name="name" class="form-control" required placeholder="e.g. B.Sc. Computer Science"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Specialization</label><input type="text" name="specialization" class="form-control" placeholder="e.g. Software Engineering"></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="label-sm mb-1">From Year</label><input type="text" name="from_year" class="form-control" placeholder="2018"></div>
                        <div class="col-6"><label class="label-sm mb-1">To Year</label><input type="text" name="to_year" class="form-control" placeholder="2022"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Save Qualification</button></div>
            </div>
        </form>
    </div>
</div>

<!-- Modal 5: Add Experience -->
<div class="modal fade" id="addExperienceModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee-experiences.store') }}">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fa-solid fa-briefcase me-2 text-success"></i>Add Work Experience</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Company Name *</label><input type="text" name="company_name" class="form-control" required placeholder="Previous Employer"></div>
                    <div class="mb-3"><label class="label-sm mb-1 fw-bold">Post / Job Title *</label><input type="text" name="post" class="form-control" required placeholder="e.g. Senior Developer"></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="label-sm mb-1 fw-bold">From Date *</label><input type="date" name="from_date" class="form-control" required></div>
                        <div class="col-6"><label class="label-sm mb-1">To Date</label><input type="date" name="to_date" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Save Experience</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
