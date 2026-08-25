@extends('layouts.app')

@section('title', 'Edit Employee - ' . $employee->first_name)
@section('page_title', 'Edit Employee Profile')

@section('content')
<!-- Page Header Banner -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="headline-lg text-body-emphasis mb-1"><i class="fa-solid fa-user-pen me-2 text-primary"></i> Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h2>
        <p class="text-body-secondary small mb-0">Update comprehensive credentials, demographics, employment specs, and payroll parameters.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex flex-wrap align-items-center justify-content-md-end gap-2">
        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-primary-subtle text-primary btn-sm fw-bold">
            <i class="fa-solid fa-eye me-1"></i> View Profile
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Directory
        </a>
        @can('edit.employees')
            <button type="submit" form="employeeEditForm" class="btn btn-primary btn-sm fw-bold shadow-sm">
                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
            </button>
        @endcan
    </div>
</div>

<form id="employeeEditForm" method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Nav Tabs for Form Categorization -->
    <ul class="nav nav-tabs nav-line-tabs mb-4" id="employeeEditTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="account-tab" data-bs-toggle="tab" data-bs-target="#account-section" type="button" role="tab">
                <i class="fa-solid fa-key me-1"></i>1. Account & Security
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-section" type="button" role="tab">
                <i class="fa-solid fa-user me-1"></i>2. Demographics & ID
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-section" type="button" role="tab">
                <i class="fa-solid fa-briefcase me-1"></i>3. Job Specs & Shifts
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="payroll-tab" data-bs-toggle="tab" data-bs-target="#payroll-section" type="button" role="tab">
                <i class="fa-solid fa-wallet me-1"></i>4. Compensation & Leaves
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="social-tab" data-bs-toggle="tab" data-bs-target="#social-section" type="button" role="tab">
                <i class="fa-solid fa-address-book me-1"></i>5. Contact & Social
            </button>
        </li>
    </ul>

    <div class="tab-content mb-5" id="employeeEditTabContent">
        <!-- Section 1: Account Credentials & Security -->
        <div class="tab-pane fade show active" id="account-section" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
                <div class="card-header bg-transparent border-bottom border-subtle pt-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-key text-primary me-2"></i>System Account Credentials</h5>
                    <span class="badge bg-primary-subtle text-primary fw-bold fs-9">Security & Access</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" name="employee_id" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $employee->employee_id) }}" required>
                            @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Card / Access Badge No</label>
                            <input type="text" name="card_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('card_no', $employee->card_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('username') is-invalid @enderror" value="{{ old('username', $employee->username) }}">
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">New Password (Leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('password') is-invalid @enderror" placeholder="Enter new password to reset">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Change Profile Photo</label>
                            <input type="file" name="profile_picture" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('profile_picture') is-invalid @enderror" accept="image/*">
                            @if($employee->profile_picture)
                                <div class="form-text fs-9 text-success"><i class="fa-solid fa-image me-1"></i>Current File: {{ $employee->profile_picture }}</div>
                            @endif
                            @error('profile_picture')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Personal Demographics & Identification -->
        <div class="tab-pane fade" id="personal-section" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
                <div class="card-header bg-transparent border-bottom border-subtle pt-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-id-card text-info me-2"></i>Personal Demographics & Government IDs</h5>
                    <span class="badge bg-info-subtle text-info fw-bold fs-9">Identification</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name) }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control form-control-sm bg-body text-body-emphasis border-subtle @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name) }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Gender</label>
                            <select name="gender" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="">Select Gender...</option>
                                <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('date_of_birth', $employee->date_of_birth) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('place_of_birth', $employee->place_of_birth) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Marital Status</label>
                            <select name="marital_status" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="">Select Status...</option>
                                <option value="Single" {{ old('marital_status', $employee->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status', $employee->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status', $employee->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Mother Tongue</label>
                            <input type="text" name="mother_tongue" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('mother_tongue', $employee->mother_tongue) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Blood Group</label>
                            <select name="blood_group" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="O+" {{ old('blood_group', $employee->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="A+" {{ old('blood_group', $employee->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="B+" {{ old('blood_group', $employee->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="AB+" {{ old('blood_group', $employee->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="O-" {{ old('blood_group', $employee->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">PAN Card Number</label>
                            <input type="text" name="pan_number" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('pan_number', $employee->pan_number) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Aadhar Card Number</label>
                            <input type="text" name="aadhar_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('aadhar_no', $employee->aadhar_no) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Job Specs & Shifts -->
        <div class="tab-pane fade" id="job-section" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
                <div class="card-header bg-transparent border-bottom border-subtle pt-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-briefcase text-success me-2"></i>Job & Organizational Specifications</h5>
                    <span class="badge bg-success-subtle text-success fw-bold fs-9">Employment</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Department</label>
                            <select name="department_id" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="">Select Department...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->department_name ?? $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Designation</label>
                            <select name="designation_id" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="">Select Designation...</option>
                                @foreach($designations as $desig)
                                    <option value="{{ $desig->id }}" {{ old('designation_id', $employee->designation_id) == $desig->id ? 'selected' : '' }}>
                                        {{ $desig->designation_name ?? $desig->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Company Entity</label>
                            <select name="company_id" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="">Select Company...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name ?? $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Reporting Manager <span class="text-primary">*</span></label>
                            <select name="manager_id" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="0" {{ (int) old('manager_id', $employee->manager_id) === 0 ? 'selected' : '' }}>-- No Manager (Top Level) --</option>
                                @foreach($managers as $mgr)
                                    @if((int)$mgr->user_id !== (int)$employee->user_id)
                                        <option value="{{ $mgr->user_id }}" {{ (int) old('manager_id', $employee->manager_id) === (int) $mgr->user_id ? 'selected' : '' }}>
                                            {{ $mgr->first_name }} {{ $mgr->last_name }} ({{ $mgr->employee_id }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Secondary / Sub Manager</label>
                            <select name="sub_manager_id" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="0" {{ (int) old('sub_manager_id', $employee->sub_manager_id) === 0 ? 'selected' : '' }}>-- None --</option>
                                @foreach($managers as $mgr)
                                    @if((int)$mgr->user_id !== (int)$employee->user_id)
                                        <option value="{{ $mgr->user_id }}" {{ (int) old('sub_manager_id', $employee->sub_manager_id) === (int) $mgr->user_id ? 'selected' : '' }}>
                                            {{ $mgr->first_name }} {{ $mgr->last_name }} ({{ $mgr->employee_id }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Employment Type</label>
                            <select name="employment_type" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="Full Time" {{ old('employment_type', $employee->employment_type) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time" {{ old('employment_type', $employee->employment_type) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Contract" {{ old('employment_type', $employee->employment_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Intern" {{ old('employment_type', $employee->employment_type) == 'Intern' ? 'selected' : '' }}>Intern</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Date of Joining</label>
                            <input type="date" name="date_of_joining" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('date_of_joining', $employee->date_of_joining) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Reporting Location</label>
                            <input type="text" name="reporting_location" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('reporting_location', $employee->reporting_location) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Probation Status</label>
                            <select name="probation_status" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="0" {{ old('probation_status', $employee->probation_status) == 0 ? 'selected' : '' }}>Confirmed / No Probation</option>
                                <option value="1" {{ old('probation_status', $employee->probation_status) == 1 ? 'selected' : '' }}>Under Probation</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Probation End Date</label>
                            <input type="date" name="probation_end_date" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('probation_end_date', $employee->probation_end_date) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Employee Source</label>
                            <input type="text" name="employee_source" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('employee_source', $employee->employee_source) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Compensation, Financials & Leaves -->
        <div class="tab-pane fade" id="payroll-section" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
                <div class="card-header bg-transparent border-bottom border-subtle pt-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-wallet text-warning me-2"></i>Compensation, Financials & Statutory Benefits</h5>
                    <span class="badge bg-warning-subtle text-warning fw-bold fs-9">Payroll & Status</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Monthly Basic Salary ($)</label>
                            <input type="number" step="0.01" name="salary" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('salary', $employee->salary) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Corporate Bank Account No</label>
                            <input type="text" name="corporate_bank_account" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('corporate_bank_account', $employee->corporate_bank_account) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Employee Status (is_active)</label>
                            <select name="is_active" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="1" {{ old('is_active', $employee->is_active) == 1 ? 'selected' : '' }}>1: Active</option>
                                <option value="2" {{ old('is_active', $employee->is_active) == 2 ? 'selected' : '' }}>2: Terminated</option>
                                <option value="3" {{ old('is_active', $employee->is_active) == 3 ? 'selected' : '' }}>3: Left</option>
                                <option value="4" {{ old('is_active', $employee->is_active) == 4 ? 'selected' : '' }}>4: Abscond</option>
                                <option value="5" {{ old('is_active', $employee->is_active) == 5 ? 'selected' : '' }}>5: Disabled</option>
                                <option value="0" {{ old('is_active', $employee->is_active) == 0 ? 'selected' : '' }}>0: Resigned</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Earned Leave Balance (Days)</label>
                            <input type="number" name="earned_leave" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('earned_leave', $employee->earned_leave) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Casual Leave Balance (Days)</label>
                            <input type="number" name="casual_leave" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('casual_leave', $employee->casual_leave) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Provident Fund (PF) Opted</label>
                            <select name="pf_opted" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="1" {{ old('pf_opted', $employee->pf_opted) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('pf_opted', $employee->pf_opted) == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Health Insurance Opted</label>
                            <select name="health_ins_opted" class="form-select form-select-sm bg-body text-body-emphasis border-subtle">
                                <option value="1" {{ old('health_ins_opted', $employee->health_ins_opted) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('health_ins_opted', $employee->health_ins_opted) == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Contact, Address & Social Profiles -->
        <div class="tab-pane fade" id="social-section" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-body-tertiary dashboard-card">
                <div class="card-header bg-transparent border-bottom border-subtle pt-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-address-book text-danger me-2"></i>Contact Information & Social Profiles</h5>
                    <span class="badge bg-danger-subtle text-danger fw-bold fs-9">Contact & Links</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Primary Phone Number</label>
                            <input type="text" name="contact_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('contact_no', $employee->contact_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Official Mobile Number</label>
                            <input type="text" name="official_contact_no" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('official_contact_no', $employee->official_contact_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-8 fw-semibold">Personal Email Address</label>
                            <input type="email" name="email_personal" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('email_personal', $employee->email_personal) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Street Address</label>
                            <input type="text" name="address" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('address', $employee->address) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fs-8 fw-semibold">City</label>
                            <input type="text" name="city" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('city', $employee->city) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fs-8 fw-semibold">State / Province</label>
                            <input type="text" name="state" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('state', $employee->state) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fs-8 fw-semibold">Pincode / Zip</label>
                            <input type="text" name="pincode" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('pincode', $employee->pincode) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Skype ID</label>
                            <input type="text" name="skype_id" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('skype_id', $employee->skype_id) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">LinkedIn Profile</label>
                            <input type="text" name="linkdedin_link" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('linkdedin_link', $employee->linkdedin_link) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Twitter Handle</label>
                            <input type="text" name="twitter_link" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('twitter_link', $employee->twitter_link) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-8 fw-semibold">Facebook Link</label>
                            <input type="text" name="facebook_link" class="form-control form-control-sm bg-body text-body-emphasis border-subtle" value="{{ old('facebook_link', $employee->facebook_link) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Bottom Form Action Dock -->
    <div class="position-sticky bottom-0 bg-body-tertiary border-top border-subtle shadow-lg p-3 rounded-top-3 mt-4 d-flex align-items-center justify-content-between z-3">
        <div class="d-none d-sm-block">
            <span class="fs-9 text-body-secondary"><i class="fa-solid fa-shield-halved me-1 text-primary"></i>Editing Employee Record #{{ $employee->id }}</span>
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm fw-bold">Cancel</a>
            @can('edit.employees')
                <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Update Employee Record
                </button>
            @endcan
        </div>
    </div>
</form>
@endsection
