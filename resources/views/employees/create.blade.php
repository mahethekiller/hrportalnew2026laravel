@extends('layouts.app')

@section('title', 'Add Employee')
@section('page_title', 'Create Full Employee Profile')

@section('content')
<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Add New Employee</h2>
        <p class="text-body-secondary small mb-0">Fill in comprehensive employee credentials, demographics, job parameters, and payroll specs.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="{{ route('employees.index') }}" class="btn btn-light-primary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to Employee List
        </a>
    </div>
</div>

<form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Nav Tabs for Form Categorization -->
    <ul class="nav nav-tabs nav-line-tabs mb-4" id="employeeFormTabs" role="tablist">
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

    <div class="tab-content" id="employeeFormTabContent">
        <!-- Section 1: Account Credentials & Security -->
        <div class="tab-pane fade show active" id="account-section" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">System Account Credentials</h3>
                    <span class="label-sm">Security</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id') }}" required placeholder="e.g. EMP-2026-001">
                            @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Card / Access Badge No</label>
                            <input type="text" name="card_no" class="form-control" value="{{ old('card_no') }}" placeholder="e.g. CARD-1001">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="e.g. jdoe">
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="e.g. employee@antigravity.io">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Account Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 6 characters">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Profile Photo Upload</label>
                            <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                            <div class="form-text small">Uploaded to <code>public/uploads/profile/</code></div>
                            @error('profile_picture')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Personal Demographics & Identification -->
        <div class="tab-pane fade" id="personal-section" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Personal Demographics & Government IDs</h3>
                    <span class="label-sm">Identification</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="label-sm mb-1">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required placeholder="First name">
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required placeholder="Last name">
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender...</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth') }}" placeholder="City / State">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Marital Status</label>
                            <select name="marital_status" class="form-select">
                                <option value="">Select Status...</option>
                                <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Mother Tongue</label>
                            <input type="text" name="mother_tongue" class="form-control" value="{{ old('mother_tongue', 'English') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Blood Group</label>
                            <select name="blood_group" class="form-select">
                                <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">PAN Card Number</label>
                            <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number') }}" placeholder="ABCDE1234F">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Aadhar Card Number</label>
                            <input type="text" name="aadhar_no" class="form-control" value="{{ old('aadhar_no') }}" placeholder="1234 5678 9012">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Job Specs & Shifts -->
        <div class="tab-pane fade" id="job-section" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Job & Organizational Specifications</h3>
                    <span class="label-sm">Employment</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Department</label>
                            <select name="department_id" class="form-select select-search">
                                <option value="">Select Department...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->department_name ?? $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Designation</label>
                            <select name="designation_id" class="form-select select-search">
                                <option value="">Select Designation...</option>
                                @foreach($designations as $desig)
                                    <option value="{{ $desig->id }}" {{ old('designation_id') == $desig->id ? 'selected' : '' }}>
                                        {{ $desig->designation_name ?? $desig->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Company Entity</label>
                            <select name="company_id" class="form-select select-search">
                                <option value="">Select Company...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name ?? $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Reporting Manager</label>
                            <select name="manager_id" class="form-select select-search">
                                <option value="0" {{ (int) old('manager_id') === 0 ? 'selected' : '' }}>-- No Manager (Top Level) --</option>
                                @foreach($managers as $mgr)
                                    <option value="{{ $mgr->user_id }}" {{ (int) old('manager_id') === (int) $mgr->user_id ? 'selected' : '' }}>
                                        {{ $mgr->first_name }} {{ $mgr->last_name }} ({{ $mgr->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Secondary / Sub Manager</label>
                            <select name="sub_manager_id" class="form-select select-search">
                                <option value="0" {{ (int) old('sub_manager_id') === 0 ? 'selected' : '' }}>-- None --</option>
                                @foreach($managers as $mgr)
                                    <option value="{{ $mgr->user_id }}" {{ (int) old('sub_manager_id') === (int) $mgr->user_id ? 'selected' : '' }}>
                                        {{ $mgr->first_name }} {{ $mgr->last_name }} ({{ $mgr->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Employment Type</label>
                            <select name="employment_type" class="form-select">
                                <option value="Full Time" {{ old('employment_type') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time" {{ old('employment_type') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Contract" {{ old('employment_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Intern" {{ old('employment_type') == 'Intern' ? 'selected' : '' }}>Intern</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Date of Joining</label>
                            <input type="date" name="date_of_joining" class="form-control" value="{{ old('date_of_joining', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Reporting Location</label>
                            <input type="text" name="reporting_location" class="form-control" value="{{ old('reporting_location', 'Headquarters') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Probation Status</label>
                            <select name="probation_status" class="form-select">
                                <option value="0" {{ old('probation_status') == '0' ? 'selected' : '' }}>Confirmed / No Probation</option>
                                <option value="1" {{ old('probation_status') == '1' ? 'selected' : '' }}>Under Probation</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Probation End Date</label>
                            <input type="date" name="probation_end_date" class="form-control" value="{{ old('probation_end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Employee Source</label>
                            <input type="text" name="employee_source" class="form-control" value="{{ old('employee_source', 'Direct Hire') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Compensation, Financials & Leaves -->
        <div class="tab-pane fade" id="payroll-section" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Compensation, Financials & Statutory Benefits</h3>
                    <span class="label-sm">Payroll</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Monthly Basic Salary ($)</label>
                            <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary') }}" placeholder="e.g. 6000.00">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Corporate Bank Account No</label>
                            <input type="text" name="corporate_bank_account" class="form-control" value="{{ old('corporate_bank_account') }}" placeholder="Account Number">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Employee Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>1: Active</option>
                                <option value="2" {{ old('is_active') == '2' ? 'selected' : '' }}>2: Terminated</option>
                                <option value="3" {{ old('is_active') == '3' ? 'selected' : '' }}>3: Left</option>
                                <option value="4" {{ old('is_active') == '4' ? 'selected' : '' }}>4: Abscond</option>
                                <option value="5" {{ old('is_active') == '5' ? 'selected' : '' }}>5: Disable</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>0: Resigned</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Earned Leave Balance (Days)</label>
                            <input type="number" name="earned_leave" class="form-control" value="{{ old('earned_leave', 12) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Casual Leave Balance (Days)</label>
                            <input type="number" name="casual_leave" class="form-control" value="{{ old('casual_leave', 12) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Provident Fund (PF) Opted</label>
                            <select name="pf_opted" class="form-select">
                                <option value="1" {{ old('pf_opted', '1') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('pf_opted') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Health Insurance Opted</label>
                            <select name="health_ins_opted" class="form-select">
                                <option value="1" {{ old('health_ins_opted', '1') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('health_ins_opted') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Contact, Address & Social Profiles -->
        <div class="tab-pane fade" id="social-section" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Contact Information & Social Profiles</h3>
                    <span class="label-sm">Contact</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Primary Phone Number</label>
                            <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Official Mobile Number</label>
                            <input type="text" name="official_contact_no" class="form-control" value="{{ old('official_contact_no') }}" placeholder="+1 (555) 111-2222">
                        </div>
                        <div class="col-md-4">
                            <label class="label-sm mb-1">Personal Email Address</label>
                            <input type="email" name="email_personal" class="form-control" value="{{ old('email_personal') }}" placeholder="personal@gmail.com">
                        </div>
                        <div class="col-md-6">
                            <label class="label-sm mb-1">Street Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="123 Corporate Way, Suite 400">
                        </div>
                        <div class="col-md-2">
                            <label class="label-sm mb-1">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="label-sm mb-1">State / Province</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="label-sm mb-1">Pincode / Zip</label>
                            <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Skype ID</label>
                            <input type="text" name="skype_id" class="form-control" value="{{ old('skype_id') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">LinkedIn Profile</label>
                            <input type="text" name="linkdedin_link" class="form-control" value="{{ old('linkdedin_link') }}" placeholder="https://linkedin.com/in/username">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Twitter Handle</label>
                            <input type="text" name="twitter_link" class="form-control" value="{{ old('twitter_link') }}" placeholder="https://twitter.com/username">
                        </div>
                        <div class="col-md-3">
                            <label class="label-sm mb-1">Facebook Link</label>
                            <input type="text" name="facebook_link" class="form-control" value="{{ old('facebook_link') }}" placeholder="https://facebook.com/username">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mb-5">
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk me-1"></i>Save Full Employee Record</button>
    </div>
</form>
@endsection
