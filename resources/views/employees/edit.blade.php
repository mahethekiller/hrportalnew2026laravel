@extends('layouts.app')

@section('title', 'Edit Employee - ' . $employee->first_name . ' ' . $employee->last_name)
@section('page_title', 'Edit Employee Profile')

@push('css')
<style>
    /* Tabs styling matching Rule 13 /recruitment-applications benchmark */
    .employee-edit-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        background: transparent;
        color: var(--bs-secondary-color);
        padding: 12px 18px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    .employee-edit-tabs .nav-link:hover {
        color: var(--bs-primary);
        border-bottom-color: var(--bs-border-color-subtle);
    }
    .employee-edit-tabs .nav-link.active {
        color: var(--bs-primary);
        border-bottom-color: var(--bs-primary);
        background: transparent;
    }

    .form-section-title {
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .avatar-preview-box {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner (Rule 13 Benchmark) -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 font-monospace small">
                    <i class="fa-solid fa-user-pen me-1"></i> Talent Dossier Editor
                </span>
                <span class="text-body-secondary small">&bull; Employee Master Record</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">
                Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}
                <span class="badge bg-body border text-body-secondary font-monospace fs-8 ms-2">
                    {{ (!empty($employee->employee_id) && $employee->employee_id !== '0') ? $employee->employee_id : 'EMP-' . sprintf('%04d', $employee->id) }}
                </span>
            </h4>
            <p class="text-body-secondary small mb-0">Update comprehensive credentials, demographics, employment specs, compensation, and profile settings.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5 text-secondary"></i> Directory
            </a>
            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-primary shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-eye me-1.5"></i> View Dossier
            </a>
            @can('edit.employees')
                <button type="submit" form="employeeEditForm" class="btn btn-sm btn-warning fw-semibold px-3 py-2 rounded-2 shadow-xs submit-loader" onclick="submitWithLoader(this)">
                    <i class="fa-solid fa-floppy-disk me-1.5"></i> Update Record
                </button>
            @endcan
        </div>
    </div>

    <!-- Mini Hero Summary Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4 overflow-hidden position-relative">
        <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    @if($employee->profile_picture)
                        <img src="{{ asset('uploads/profile/' . $employee->profile_picture) }}" alt="{{ $employee->first_name }}" class="avatar-preview-box border border-subtle shadow-xs" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="rounded-3 bg-primary-subtle text-primary border border-primary-subtle d-none align-items-center justify-content-center fw-bold fs-4" style="width: 72px; height: 72px;">
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        </div>
                    @else
                        <div class="rounded-3 bg-primary-subtle text-primary border border-primary-subtle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 72px; height: 72px;">
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h5 class="fw-bold text-body-emphasis mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                            @php
                                $statusBadge = match((int)$employee->is_active) {
                                    1 => ['class' => 'bg-success-subtle text-success border-success-subtle', 'label' => 'Active'],
                                    2 => ['class' => 'bg-danger-subtle text-danger border-danger-subtle', 'label' => 'Terminated'],
                                    3 => ['class' => 'bg-secondary-subtle text-secondary border-secondary-subtle', 'label' => 'Left Company'],
                                    4 => ['class' => 'bg-danger-subtle text-danger border-danger-subtle', 'label' => 'Absconded'],
                                    5 => ['class' => 'bg-secondary-subtle text-secondary border-secondary-subtle', 'label' => 'Disabled'],
                                    0 => ['class' => 'bg-warning-subtle text-warning border-warning-subtle', 'label' => 'Resigned'],
                                    default => ['class' => 'bg-secondary-subtle text-secondary border-secondary-subtle', 'label' => 'Unknown']
                                };
                            @endphp
                            <span class="badge {{ $statusBadge['class'] }} border rounded-pill px-2.5 py-0.5 small">
                                <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> {{ $statusBadge['label'] }}
                            </span>
                            @if($employee->employment_type)
                                <span class="badge bg-body border text-body-secondary rounded-pill px-2.5 py-0.5 small">
                                    {{ $employee->employment_type }}
                                </span>
                            @endif
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-0.5 small">
                                <i class="fa-solid fa-user-shield me-1"></i> Role: {{ $employee->userRole->role_name ?? $employee->roles->first()?->name ?? 'Employee' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-3 text-body-secondary small flex-wrap">
                            <span><i class="fa-solid fa-briefcase text-secondary me-1"></i> {{ $employee->designation->designation_name ?? $employee->designation->name ?? 'Staff' }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-sitemap text-secondary me-1"></i> {{ $employee->department->department_name ?? $employee->department->name ?? 'Department' }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-building text-secondary me-1"></i> {{ $employee->company->name ?? 'Primary Entity' }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-envelope text-secondary me-1"></i> {{ $employee->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-md-end text-body-secondary small">
                    <div class="font-monospace"><span class="text-body-tertiary">System ID:</span> #{{ $employee->id }}</div>
                    <div class="font-monospace"><span class="text-body-tertiary">User ID:</span> #{{ $employee->user_id }}</div>
                    <div><span class="text-body-tertiary">Joined:</span> {{ $employee->date_of_joining ? \Carbon\Carbon::parse($employee->date_of_joining)->format('M d, Y') : 'Not Set' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- In-Modal / Page Error Alerts (Rule 10 Compliance) -->
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-start mb-4 p-3 bg-danger-subtle text-danger-emphasis">
            <i class="fa-solid fa-triangle-exclamation fs-5 me-3 mt-1 text-danger"></i>
            <div class="flex-grow-1">
                <div class="fw-bold mb-1">Please correct the validation errors below:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 p-3 bg-danger-subtle text-danger-emphasis">
            <i class="fa-solid fa-circle-xmark fs-5 me-3 text-danger"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 p-3 bg-success-subtle text-success-emphasis">
            <i class="fa-solid fa-circle-check fs-5 me-3 text-success"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form id="employeeEditForm" method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <!-- Navigation Tabs for Form Categorization -->
        <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4 p-1">
            <ul class="nav nav-tabs employee-edit-tabs border-0 flex-nowrap overflow-auto" id="employeeEditTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-nowrap" id="account-tab" data-bs-toggle="tab" data-bs-target="#account-section" type="button" role="tab">
                        <i class="fa-solid fa-key me-2 text-primary"></i>1. Account & Security
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-section" type="button" role="tab">
                        <i class="fa-solid fa-id-card me-2 text-info"></i>2. Demographics & IDs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-section" type="button" role="tab">
                        <i class="fa-solid fa-briefcase me-2 text-success"></i>3. Job Specs & Shifts
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="payroll-tab" data-bs-toggle="tab" data-bs-target="#payroll-section" type="button" role="tab">
                        <i class="fa-solid fa-wallet me-2 text-warning"></i>4. Compensation & Leaves
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="social-tab" data-bs-toggle="tab" data-bs-target="#social-section" type="button" role="tab">
                        <i class="fa-solid fa-address-book me-2 text-danger"></i>5. Contact & Social
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="employeeEditTabContent">
            <!-- Section 1: Account Credentials & Security -->
            <div class="tab-pane fade show active" id="account-section" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary position-relative overflow-hidden">
                    <div class="position-absolute bottom-0 start-0 end-0 bg-primary" style="height: 3px;"></div>
                    <div class="card-header bg-transparent border-bottom border-subtle py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-2 bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <h5 class="form-section-title text-body-emphasis mb-0">System Account Credentials & Authentication</h5>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                            Security & Access
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" name="employee_id" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $employee->employee_id) }}" required placeholder="e.g. EMP-1001">
                                @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text fs-9 text-body-secondary">Unique organizational identifier.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Card / Access Badge No</label>
                                <input type="text" name="card_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('card_no', $employee->card_no) }}" placeholder="e.g. RFID-884920">
                                <div class="form-text fs-9 text-body-secondary">Biometric or RFID keycard number.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Username</label>
                                <input type="text" name="username" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('username') is-invalid @enderror" value="{{ old('username', $employee->username) }}" placeholder="Username for login">
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text fs-9 text-body-secondary">Unique login username handle.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Corporate Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" required placeholder="name@company.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text fs-9 text-body-secondary">Primary login & notification email.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1 d-flex align-items-center justify-content-between">
                                    <span>System Access Role <span class="text-danger">*</span></span>
                                    @if(!empty($canChangeRole))
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace" style="font-size: 0.65rem;">Active</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill font-monospace" style="font-size: 0.65rem;"><i class="fa-solid fa-lock me-1"></i>Restricted</span>
                                    @endif
                                </label>
                                @if(!empty($canChangeRole))
                                    <select name="user_role_id" id="userRoleSelect" class="form-select form-select-sm select-search bg-body text-body-emphasis border-subtle @error('user_role_id') is-invalid @enderror" required>
                                        <option value="">Select Access Role...</option>
                                        @foreach($roles as $role)
                                            @php
                                                $isSelected = (int)old('user_role_id', $employee->user_role_id) === (int)$role->id 
                                                    || (!old('user_role_id') && ($employee->hasRole($role->role_name) || ($employee->user && $employee->user->hasRole($role->role_name))));
                                            @endphp
                                            <option value="{{ $role->id }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $role->role_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text fs-9 text-body-secondary">Determines security permissions and module accessibility.</div>
                                @else
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm bg-body-tertiary text-body-secondary border-subtle" value="{{ $employee->userRole->role_name ?? $employee->roles->first()?->name ?? 'Standard Employee' }}" readonly disabled>
                                        <span class="input-group-text bg-body-tertiary border-subtle text-body-tertiary" title="Contact Super Admin to modify role permissions"><i class="fa-solid fa-lock"></i></span>
                                    </div>
                                    <div class="form-text fs-9 text-body-secondary">Role cannot be modified on your own account.</div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Reset Password</label>
                                <input type="password" name="password" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('password') is-invalid @enderror" placeholder="Leave blank to preserve current password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text fs-9 text-body-secondary">Enter at least 6 characters to reset.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Profile Photo</label>
                                <input type="file" name="profile_picture" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('profile_picture') is-invalid @enderror" accept="image/*">
                                @if($employee->profile_picture)
                                    <div class="form-text fs-9 text-success mt-1">
                                        <i class="fa-solid fa-image me-1"></i> Current file: <span class="font-monospace">{{ $employee->profile_picture }}</span>
                                    </div>
                                @endif
                                @error('profile_picture')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Personal Demographics & Identification -->
            <div class="tab-pane fade" id="personal-section" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary position-relative overflow-hidden">
                    <div class="position-absolute bottom-0 start-0 end-0 bg-info" style="height: 3px;"></div>
                    <div class="card-header bg-transparent border-bottom border-subtle py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-2 bg-info-subtle text-info p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <h5 class="form-section-title text-body-emphasis mb-0">Personal Demographics & Government Identifiers</h5>
                        </div>
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 small">
                            Identification & KYC
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name) }}" required placeholder="Legal first name">
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name) }}" required placeholder="Legal last name">
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Gender</label>
                                <select name="gender" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="">Select Gender...</option>
                                    <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('date_of_birth', $employee->date_of_birth) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Place of Birth</label>
                                <input type="text" name="place_of_birth" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('place_of_birth', $employee->place_of_birth) }}" placeholder="City, State / Country">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Marital Status</label>
                                <select name="marital_status" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="" {{ empty(old('marital_status', $employee->marital_status)) ? 'selected' : '' }}>Select Status...</option>
                                    <option value="Single" {{ old('marital_status', $employee->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('marital_status', $employee->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('marital_status', $employee->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Mother Tongue</label>
                                <input type="text" name="mother_tongue" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('mother_tongue', $employee->mother_tongue) }}" placeholder="e.g. English, Hindi">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Blood Group</label>
                                <select name="blood_group" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="" {{ empty(old('blood_group', $employee->blood_group)) ? 'selected' : '' }}>Select Blood Group...</option>
                                    <option value="O+" {{ old('blood_group', $employee->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="A+" {{ old('blood_group', $employee->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="B+" {{ old('blood_group', $employee->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="AB+" {{ old('blood_group', $employee->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="O-" {{ old('blood_group', $employee->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="A-" {{ old('blood_group', $employee->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B-" {{ old('blood_group', $employee->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB-" {{ old('blood_group', $employee->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">PAN Card Number</label>
                                <input type="text" name="pan_number" class="form-control form-control-sm bg-body text-body-emphasis border-subtle font-monospace text-uppercase" value="{{ old('pan_number', $employee->pan_number) }}" placeholder="ABCDE1234F">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Aadhar Card Number</label>
                                <input type="text" name="aadhar_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle font-monospace" value="{{ old('aadhar_no', $employee->aadhar_no) }}" placeholder="12-digit number">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Job Specs & Shifts -->
            <div class="tab-pane fade" id="job-section" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary position-relative overflow-hidden">
                    <div class="position-absolute bottom-0 start-0 end-0 bg-success" style="height: 3px;"></div>
                    <div class="card-header bg-transparent border-bottom border-subtle py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-2 bg-success-subtle text-success p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <h5 class="form-section-title text-body-emphasis mb-0">Organizational Hierarchy & Employment Specifications</h5>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">
                            Employment & Hierarchy
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Department</label>
                                <select name="department_id" id="employeeDepartmentSelect" class="form-select form-select-sm select-search bg-body text-body-emphasis border-subtle">
                                    <option value="" {{ empty(old('department_id', $employee->department_id)) ? 'selected' : '' }}>Select Department...</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ (int)old('department_id', $employee->department_id) === (int)$dept->id ? 'selected' : '' }}>
                                            {{ $dept->name_with_company ?? $dept->department_name ?? $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Designation</label>
                                <select name="designation_id" id="employeeDesignationSelect" class="form-select form-select-sm select-search bg-body text-body-emphasis border-subtle">
                                    <option value="" {{ empty(old('designation_id', $employee->designation_id)) ? 'selected' : '' }}>Select Designation...</option>
                                    @foreach($designations as $desig)
                                        <option value="{{ $desig->id }}" data-department-id="{{ $desig->department_id }}" {{ (int)old('designation_id', $employee->designation_id) === (int)$desig->id ? 'selected' : '' }}>
                                            {{ $desig->name_with_company ?? $desig->designation_name ?? $desig->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Company Entity</label>
                                <select name="company_id" class="form-select form-select-sm select-search bg-body text-body-emphasis border-subtle">
                                    <option value="" {{ empty(old('company_id', $employee->company_id)) ? 'selected' : '' }}>Select Company Entity...</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ (int)old('company_id', $employee->company_id) === (int)$company->id ? 'selected' : '' }}>
                                            {{ $company->name ?? $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Primary Reporting Manager</label>
                                <select name="manager_id" class="form-select form-select-sm select-search bg-body text-body-emphasis border-subtle">
                                    <option value="0" {{ empty(old('manager_id', $employee->manager_id)) || (int) old('manager_id', $employee->manager_id) === 0 ? 'selected' : '' }}>-- No Manager (Top Executive / Level 1) --</option>
                                    @foreach($managers as $mgr)
                                        @if((int)$mgr->user_id !== (int)$employee->user_id)
                                            <option value="{{ $mgr->user_id }}" {{ !empty($employee->manager_id) && (int) old('manager_id', $employee->manager_id) === (int) $mgr->user_id ? 'selected' : '' }}>
                                                {{ $mgr->first_name }} {{ $mgr->last_name }} ({{ (!empty($mgr->employee_id) && $mgr->employee_id !== '0') ? $mgr->employee_id : 'EMP-' . sprintf('%04d', $mgr->id) }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Secondary / Sub Manager</label>
                                <select name="sub_manager_id" class="form-select form-select-sm select-search bg-body text-body-emphasis border-subtle">
                                    <option value="0" {{ empty(old('sub_manager_id', $employee->sub_manager_id)) || (int) old('sub_manager_id', $employee->sub_manager_id) === 0 ? 'selected' : '' }}>-- None --</option>
                                    @foreach($managers as $mgr)
                                        @if((int)$mgr->user_id !== (int)$employee->user_id)
                                            <option value="{{ $mgr->user_id }}" {{ !empty($employee->sub_manager_id) && (int) old('sub_manager_id', $employee->sub_manager_id) === (int) $mgr->user_id ? 'selected' : '' }}>
                                                {{ $mgr->first_name }} {{ $mgr->last_name }} ({{ (!empty($mgr->employee_id) && $mgr->employee_id !== '0') ? $mgr->employee_id : 'EMP-' . sprintf('%04d', $mgr->id) }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Employment Type</label>
                                <select name="employment_type" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="" {{ empty(old('employment_type', $employee->employment_type)) ? 'selected' : '' }}>Select Employment Type...</option>
                                    <option value="Full Time" {{ old('employment_type', $employee->employment_type) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="Part Time" {{ old('employment_type', $employee->employment_type) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="Contract" {{ old('employment_type', $employee->employment_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="Intern" {{ old('employment_type', $employee->employment_type) == 'Intern' ? 'selected' : '' }}>Intern</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Date of Joining</label>
                                <input type="date" name="date_of_joining" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('date_of_joining', $employee->date_of_joining) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Reporting Office Location</label>
                                <input type="text" name="reporting_location" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('reporting_location', $employee->reporting_location) }}" placeholder="e.g. Headquarters / Remote">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Probation Status</label>
                                <select name="probation_status" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="0" {{ old('probation_status', $employee->probation_status) == 0 ? 'selected' : '' }}>Confirmed / Permanent (No Probation)</option>
                                    <option value="1" {{ old('probation_status', $employee->probation_status) == 1 ? 'selected' : '' }}>Under Probation Period</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Probation End Date</label>
                                <input type="date" name="probation_end_date" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('probation_end_date', $employee->probation_end_date) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Recruitment Source</label>
                                <input type="text" name="employee_source" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('employee_source', $employee->employee_source) }}" placeholder="e.g. LinkedIn, Referral, Direct">
                            </div>

                            <!-- KRA & KPI Document Uploads -->
                            <div class="col-12 mt-2">
                                <div class="p-3 rounded-3 bg-body border border-subtle">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fa-solid fa-file-signature text-primary"></i>
                                        <h6 class="fw-bold mb-0 fs-8 text-body-emphasis">Performance & Accountability Documentation (KRA & KPI)</h6>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Key Responsibility Areas (KRA) Document</label>
                                            <input type="file" name="kra_doc" class="form-control form-control-sm bg-body text-body border-subtle" accept=".pdf,.doc,.docx,.jpg,.png,.webp">
                                            @if($employee->kra_doc)
                                                <div class="mt-1 d-flex align-items-center gap-2 small">
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Uploaded</span>
                                                    <a href="{{ asset('uploads/kra/' . $employee->kra_doc) }}" target="_blank" class="text-primary text-decoration-none">
                                                        <i class="fa-solid fa-file-arrow-down me-1"></i>{{ $employee->kra_doc }}
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-body-secondary fs-9 mt-1">Upload official KRA specification document (PDF, DOCX, max 10MB)</div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Performance Indicators (KPI) Document</label>
                                            <input type="file" name="kpi_doc" class="form-control form-control-sm bg-body text-body border-subtle" accept=".pdf,.doc,.docx,.jpg,.png,.webp">
                                            @if($employee->kpi_doc)
                                                <div class="mt-1 d-flex align-items-center gap-2 small">
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Uploaded</span>
                                                    <a href="{{ asset('uploads/kpi/' . $employee->kpi_doc) }}" target="_blank" class="text-primary text-decoration-none">
                                                        <i class="fa-solid fa-file-arrow-down me-1"></i>{{ $employee->kpi_doc }}
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-body-secondary fs-9 mt-1">Upload official KPI metrics document (PDF, DOCX, max 10MB)</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Compensation, Financials & Leaves -->
            <div class="tab-pane fade" id="payroll-section" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary position-relative overflow-hidden">
                    <div class="position-absolute bottom-0 start-0 end-0 bg-warning" style="height: 3px;"></div>
                    <div class="card-header bg-transparent border-bottom border-subtle py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-2 bg-warning-subtle text-warning p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <h5 class="form-section-title text-body-emphasis mb-0">Compensation, Statutory Benefits & Employment Status</h5>
                        </div>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small">
                            Payroll & Leave Balances
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Monthly Basic Salary</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-body text-body-secondary border-subtle">$</span>
                                    <input type="number" step="0.01" name="salary" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('salary', $employee->salary) }}" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Corporate Salary Account No</label>
                                <input type="text" name="corporate_bank_account" class="form-control form-control-sm bg-body text-body-emphasis border-subtle font-monospace" value="{{ old('corporate_bank_account', $employee->corporate_bank_account) }}" placeholder="Account number">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Employee Status (Lifecycle)</label>
                                <select name="is_active" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="1" {{ old('is_active', $employee->is_active) == 1 ? 'selected' : '' }}>Active (Regular)</option>
                                    <option value="2" {{ old('is_active', $employee->is_active) == 2 ? 'selected' : '' }}>Terminated</option>
                                    <option value="3" {{ old('is_active', $employee->is_active) == 3 ? 'selected' : '' }}>Left Company</option>
                                    <option value="4" {{ old('is_active', $employee->is_active) == 4 ? 'selected' : '' }}>Absconded</option>
                                    <option value="5" {{ old('is_active', $employee->is_active) == 5 ? 'selected' : '' }}>Disabled / Suspended</option>
                                    <option value="0" {{ old('is_active', $employee->is_active) == 0 ? 'selected' : '' }}>Resigned</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Earned Leave Balance (Days)</label>
                                <input type="number" step="0.5" name="earned_leave" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('earned_leave', $employee->earned_leave) }}" placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Casual Leave Balance (Days)</label>
                                <input type="number" step="0.5" name="casual_leave" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('casual_leave', $employee->casual_leave) }}" placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Provident Fund (PF) Enrolled</label>
                                <select name="pf_opted" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="1" {{ old('pf_opted', $employee->pf_opted) == 1 ? 'selected' : '' }}>Yes - Enrolled</option>
                                    <option value="0" {{ old('pf_opted', $employee->pf_opted) == 0 ? 'selected' : '' }}>No - Exempted</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Health Insurance Enrolled</label>
                                <select name="health_ins_opted" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                    <option value="1" {{ old('health_ins_opted', $employee->health_ins_opted) == 1 ? 'selected' : '' }}>Yes - Covered</option>
                                    <option value="0" {{ old('health_ins_opted', $employee->health_ins_opted) == 0 ? 'selected' : '' }}>No - Not Covered</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Contact, Address & Social Profiles -->
            <div class="tab-pane fade" id="social-section" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary position-relative overflow-hidden">
                    <div class="position-absolute bottom-0 start-0 end-0 bg-danger" style="height: 3px;"></div>
                    <div class="card-header bg-transparent border-bottom border-subtle py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-2 bg-danger-subtle text-danger p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa-solid fa-address-book"></i>
                            </div>
                            <h5 class="form-section-title text-body-emphasis mb-0">Contact Information, Residential Address & Social Profiles</h5>
                        </div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small">
                            Contact & Social
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Primary Phone Number</label>
                                <input type="text" name="contact_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('contact_no', $employee->contact_no) }}" placeholder="+1 (555) 000-0000">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Official Mobile Number</label>
                                <input type="text" name="official_contact_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('official_contact_no', $employee->official_contact_no) }}" placeholder="Corporate phone">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Personal Email Address</label>
                                <input type="email" name="email_personal" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('email_personal', $employee->email_personal) }}" placeholder="personal@domain.com">
                            </div>
                            <!-- Permanent Address Subheader -->
                            <div class="col-12 mt-4 pt-2 border-top border-subtle">
                                <h6 class="fs-7 fw-bold text-body-emphasis mb-0">
                                    <i class="fa-solid fa-house-chimney text-primary me-1.5"></i> Permanent Address
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Permanent Street Address</label>
                                <input type="text" name="address" id="perm_address" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('address', $employee->address) }}" placeholder="House/Apartment, Street Name">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">City</label>
                                <input type="text" name="city" id="perm_city" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('city', $employee->city) }}" placeholder="City">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">State / Province</label>
                                <input type="text" name="state" id="perm_state" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('state', $employee->state) }}" placeholder="State">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Postal Code / Zip</label>
                                <input type="text" name="pincode" id="perm_pincode" class="form-control form-control-sm bg-body text-body-emphasis border-subtle font-monospace" value="{{ old('pincode', $employee->pincode) }}" placeholder="ZIP / PIN">
                            </div>

                            <!-- Communication Address Subheader -->
                            <div class="col-12 mt-4 pt-2 border-top border-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h6 class="fs-7 fw-bold text-body-emphasis mb-0">
                                    <i class="fa-solid fa-house-laptop text-info me-1.5"></i> Communication / Current Address
                                </h6>
                                <div class="form-check form-check-sm mb-0">
                                    <input class="form-check-input" type="checkbox" id="sameAsPermanent" onchange="copyPermanentAddress(this)">
                                    <label class="form-check-label fs-8 text-body-secondary fw-semibold" for="sameAsPermanent">
                                        Same as Permanent Address
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Communication Street Address</label>
                                <input type="text" name="address_com" id="com_address" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('address_com', $employee->address_com) }}" placeholder="Current flat/house/street address">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">City</label>
                                <input type="text" name="city_temp" id="com_city" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('city_temp', $employee->city_temp) }}" placeholder="Current City">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">State / Province</label>
                                <input type="text" name="state_temp" id="com_state" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('state_temp', $employee->state_temp) }}" placeholder="Current State">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1">Postal Code / Zip</label>
                                <input type="text" name="pin_temp" id="com_pincode" class="form-control form-control-sm bg-body text-body-emphasis border-subtle font-monospace" value="{{ old('pin_temp', $employee->pin_temp) }}" placeholder="ZIP / PIN">
                            </div>

                            <!-- Social Profiles Subheader -->
                            <div class="col-12 mt-4 pt-2 border-top border-subtle">
                                <h6 class="fs-7 fw-bold text-body-emphasis mb-0">
                                    <i class="fa-solid fa-share-nodes text-danger me-1.5"></i> Social Profiles
                                </h6>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1"><i class="fa-brands fa-skype text-info me-1"></i> Skype ID</label>
                                <input type="text" name="skype_id" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('skype_id', $employee->skype_id) }}" placeholder="live:username">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1"><i class="fa-brands fa-linkedin text-primary me-1"></i> LinkedIn URL</label>
                                <input type="text" name="linkdedin_link" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('linkdedin_link', $employee->linkdedin_link) }}" placeholder="https://linkedin.com/in/...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1"><i class="fa-brands fa-x-twitter me-1"></i> Twitter / X Profile</label>
                                <input type="text" name="twitter_link" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('twitter_link', $employee->twitter_link) }}" placeholder="https://twitter.com/...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 fw-semibold text-body-secondary mb-1"><i class="fa-brands fa-facebook text-primary me-1"></i> Facebook URL</label>
                                <input type="text" name="facebook_link" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('facebook_link', $employee->facebook_link) }}" placeholder="https://facebook.com/...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Form Action Dock (Rule 12 Compliance) -->
        <div class="position-sticky bottom-0 bg-body-tertiary border-top border-subtle shadow-lg p-3 rounded-3 mt-4 d-flex align-items-center justify-content-between z-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-body border text-body-secondary font-monospace fs-9 py-2 px-3 rounded-pill">
                    <i class="fa-solid fa-user-shield me-1.5 text-primary"></i> Editing Record #{{ $employee->id }} &bull; {{ $employee->first_name }} {{ $employee->last_name }}
                </span>
            </div>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-body border text-body-emphasis fw-semibold px-3 py-2 rounded-2">
                    Cancel
                </a>
                @can('edit.employees')
                    <button type="submit" class="btn btn-sm btn-warning fw-semibold px-4 py-2 rounded-2 shadow-xs submit-loader" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-floppy-disk me-1.5"></i> Update Employee Record
                    </button>
                @endcan
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Initialize Select2 dropdowns
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select-search').select2({
                width: '100%',
                placeholder: 'Search & Select...',
                allowClear: true
            });

            // Re-render Select2 properly when switching tabs
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('.select-search').select2({
                    width: '100%',
                    placeholder: 'Search & Select...',
                    allowClear: true
                });
            });
        }

        // Dynamic Department -> Designation filtering (Rule 9)
        const $deptSelect = $('#employeeDepartmentSelect');
        const $desigSelect = $('#employeeDesignationSelect');

        function filterDesignations() {
            const selectedDept = $deptSelect.val();
            if (!selectedDept) {
                $desigSelect.find('option').prop('disabled', false);
            } else {
                $desigSelect.find('option').each(function() {
                    const deptId = $(this).data('department-id');
                    if (!$(this).val() || !deptId || deptId == selectedDept) {
                        $(this).prop('disabled', false);
                    } else {
                        $(this).prop('disabled', true);
                    }
                });
            }
            if (typeof $.fn.select2 !== 'undefined') {
                $desigSelect.select2({
                    width: '100%',
                    placeholder: 'Search & Select...'
                });
            }
        }

        $deptSelect.on('change', filterDesignations);

        // Auto-switch to tab containing invalid input on validation failure
        const $firstInvalid = $('.is-invalid').first();
        if ($firstInvalid.length) {
            const $tabPane = $firstInvalid.closest('.tab-pane');
            if ($tabPane.length) {
                const tabId = $tabPane.attr('id');
                const $targetTab = $('button[data-bs-target="#' + tabId + '"]');
                if ($targetTab.length) {
                    $targetTab.tab('show');
                    $firstInvalid.focus();
                }
            }
        }
    });

    window.copyPermanentAddress = function(checkbox) {
        if (checkbox.checked) {
            document.getElementById('com_address').value = document.getElementById('perm_address').value;
            document.getElementById('com_city').value = document.getElementById('perm_city').value;
            document.getElementById('com_state').value = document.getElementById('perm_state').value;
            document.getElementById('com_pincode').value = document.getElementById('perm_pincode').value;
        }
    };
</script>
@endpush
