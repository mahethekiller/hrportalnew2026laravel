@extends('layouts.app')

@section('title', 'Request Profile Update')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-solid fa-user-pen me-1"></i> Self-Service Portal
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Employee Master Data</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Request Profile Update</h1>
            <p class="text-body-secondary fs-7 mb-0">Modify personal bio, residential addresses, family dependents, emergency contacts, and corporate statutory opt-ins.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Profile
            </a>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
            <div class="flex-grow-1 fs-8">
                <strong>Attention required:</strong> {{ $errors->first() }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Pending Request Status Notice (Airy & Elevated: DENSITY: 3, MOTION: 4) -->
    @if($pendingUpdate)
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-warning-subtle border border-warning-subtle transition-all hover-lift">
            <div class="card-body p-3.5 d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; min-width: 42px;">
                    <i class="fa-solid fa-clock-rotate-left fs-6"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-0.5">
                        <h6 class="fw-bold mb-0 text-body-emphasis">Pending Profile Update Awaiting HR Approval</h6>
                        <span class="badge bg-warning text-dark font-monospace fs-9 fw-bold">STAGED</span>
                    </div>
                    <p class="fs-8 text-body-secondary mb-0">
                        You submitted proposed changes on <strong>{{ $pendingUpdate->added_date }}</strong>. Any additional modifications saved below will update your pending submission.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('my-portal.profile-update.store') }}" method="POST" id="profileUpdateForm">
        @csrf

        <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
            <!-- Modern Nav Tabs Header (Airy Pill Tabs) -->
            <div class="card-header bg-transparent border-bottom pt-3 pb-0 px-4">
                <ul class="nav nav-tabs border-0 flex-nowrap overflow-x-auto gap-2" id="profileUpdateTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold fs-8 border-0 border-bottom border-3 border-primary py-3 px-3 text-body-emphasis d-flex align-items-center gap-2" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basicTabContent" type="button" role="tab">
                            <i class="fa-solid fa-address-card text-primary fs-7"></i>
                            <span>Personal Bio</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold fs-8 border-0 border-bottom border-3 border-transparent py-3 px-3 text-body-secondary d-flex align-items-center gap-2" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contactTabContent" type="button" role="tab">
                            <i class="fa-solid fa-location-dot text-success fs-7"></i>
                            <span>Contact & Address</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold fs-8 border-0 border-bottom border-3 border-transparent py-3 px-3 text-body-secondary d-flex align-items-center gap-2" id="family-tab" data-bs-toggle="tab" data-bs-target="#familyTabContent" type="button" role="tab">
                            <i class="fa-solid fa-people-roof text-warning fs-7"></i>
                            <span>Family Dependents</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold fs-8 border-0 border-bottom border-3 border-transparent py-3 px-3 text-body-secondary d-flex align-items-center gap-2" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergencyTabContent" type="button" role="tab">
                            <i class="fa-solid fa-truck-medical text-danger fs-7"></i>
                            <span>Emergency Contact</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold fs-8 border-0 border-bottom border-3 border-transparent py-3 px-3 text-body-secondary d-flex align-items-center gap-2" id="statutory-tab" data-bs-toggle="tab" data-bs-target="#statutoryTabContent" type="button" role="tab">
                            <i class="fa-solid fa-shield-halved text-info fs-7"></i>
                            <span>Benefits & Statutory</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="profileUpdateTabsContent">
                    
                    <!-- 1. Personal Bio Tab -->
                    <div class="tab-pane fade show active" id="basicTabContent" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                            <div>
                                <h5 class="fw-bold text-body-emphasis mb-1">Primary Personal Identity</h5>
                                <p class="text-body-secondary fs-8 mb-0">Legal names, date of birth, identity records, and personal demographics.</p>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                                Section 1 of 5
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control fs-8 bg-body" name="first_name" value="{{ old('first_name', $pendingUpdate ? $pendingUpdate->first_name : $employee->first_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control fs-8 bg-body" name="last_name" value="{{ old('last_name', $pendingUpdate ? $pendingUpdate->last_name : $employee->last_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Personal Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control fs-8 bg-body" name="email_personal" value="{{ old('email_personal', $pendingUpdate ? $pendingUpdate->email_personal : $employee->email_personal) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Primary Contact Mobile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace" name="contact_no" value="{{ old('contact_no', $pendingUpdate ? $pendingUpdate->contact_no : $employee->contact_no) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Date of Birth</label>
                                <input type="date" class="form-control fs-8 bg-body" name="date_of_birth" value="{{ old('date_of_birth', $pendingUpdate ? $pendingUpdate->date_of_birth : $employee->date_of_birth) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Gender</label>
                                <select class="form-select fs-8 bg-body" name="gender">
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Marital Status</label>
                                <select class="form-select fs-8 bg-body" name="marital_status">
                                    <option value="">-- Select Status --</option>
                                    <option value="Single" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Mother Tongue</label>
                                <input type="text" class="form-control fs-8 bg-body" name="mother_tongue" value="{{ old('mother_tongue', $pendingUpdate ? $pendingUpdate->mother_tongue : $employee->mother_tongue) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Place of Birth</label>
                                <input type="text" class="form-control fs-8 bg-body" name="place_of_birth" value="{{ old('place_of_birth', $pendingUpdate ? $pendingUpdate->place_of_birth : $employee->place_of_birth) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Blood Group</label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace" name="blood_group" placeholder="e.g. O+, A+, B+" value="{{ old('blood_group', $pendingUpdate ? $pendingUpdate->blood_group : $employee->blood_group) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">PAN Card Number</label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace text-uppercase" name="pan_number" value="{{ old('pan_number', $pendingUpdate ? $pendingUpdate->pan_number : $employee->pan_number) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Aadhar Card Number</label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace" name="aadhar_no" value="{{ old('aadhar_no', $pendingUpdate ? $pendingUpdate->aadhar_no : $employee->aadhar_no) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Social Category</label>
                                <input type="text" class="form-control fs-8 bg-body" name="category" placeholder="General / OBC / SC / ST" value="{{ old('category', $pendingUpdate ? $pendingUpdate->category : $employee->category) }}">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Contact & Address Tab -->
                    <div class="tab-pane fade" id="contactTabContent" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                            <div>
                                <h5 class="fw-bold text-body-emphasis mb-1">Residential Addresses & Work Channels</h5>
                                <p class="text-body-secondary fs-8 mb-0">Permanent address, local communication address, transport, and messenger info.</p>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                                Section 2 of 5
                            </span>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Permanent Residential Address</label>
                                <textarea class="form-control fs-8 bg-body" name="address" rows="3" placeholder="Door No, Street, Landmark...">{{ old('address', $pendingUpdate ? $pendingUpdate->address : $employee->address) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Current Communication / Local Address</label>
                                <textarea class="form-control fs-8 bg-body" name="address_com" rows="3" placeholder="Current residing address...">{{ old('address_com', $pendingUpdate ? $pendingUpdate->address_com : $employee->address_com) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">City</label>
                                <input type="text" class="form-control fs-8 bg-body" name="city" value="{{ old('city', $pendingUpdate ? $pendingUpdate->city : $employee->city) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">State / Province</label>
                                <input type="text" class="form-control fs-8 bg-body" name="state" value="{{ old('state', $pendingUpdate ? $pendingUpdate->state : $employee->state) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Pincode / Postal Code</label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace" name="pincode" value="{{ old('pincode', $pendingUpdate ? $pendingUpdate->pincode : $employee->pincode) }}">
                            </div>
                        </div>

                        <h6 class="fw-bold text-body-emphasis mb-3 fs-7">Official Contact & Transport Details</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Official Desk / Direct Contact</label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace" name="official_contact_no" value="{{ old('official_contact_no', $pendingUpdate ? $pendingUpdate->official_contact_no : $employee->official_contact_no) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Paytm Mobile Number</label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace" name="paytm_no" value="{{ old('paytm_no', $pendingUpdate ? $pendingUpdate->paytm_no : $employee->paytm_no) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Skype / IM ID</label>
                                <input type="text" class="form-control fs-8 bg-body" name="skype_id" value="{{ old('skype_id', $pendingUpdate ? $pendingUpdate->skype_id : $employee->skype_id) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Vehicle Type</label>
                                <select class="form-select fs-8 bg-body" name="vehicle_type">
                                    <option value="None" {{ old('vehicle_type', $pendingUpdate ? $pendingUpdate->vehicle_type : $employee->vehicle_type) == 'None' ? 'selected' : '' }}>None</option>
                                    <option value="Bike" {{ old('vehicle_type', $pendingUpdate ? $pendingUpdate->vehicle_type : $employee->vehicle_type) == 'Bike' ? 'selected' : '' }}>Motorcycle / Scooter</option>
                                    <option value="Car" {{ old('vehicle_type', $pendingUpdate ? $pendingUpdate->vehicle_type : $employee->vehicle_type) == 'Car' ? 'selected' : '' }}>Car / Four Wheeler</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Vehicle Registration Number</label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace text-uppercase" name="vehicle_no" placeholder="e.g. DL 01 AB 1234" value="{{ old('vehicle_no', $pendingUpdate ? $pendingUpdate->vehicle_no : $employee->vehicle_no) }}">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Family Details Tab -->
                    <div class="tab-pane fade" id="familyTabContent" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                            <div>
                                <h5 class="fw-bold text-body-emphasis mb-1">Immediate Family & Dependents</h5>
                                <p class="text-body-secondary fs-8 mb-0">Record parent, sibling, spouse, and child records for corporate insurance and emergency files.</p>
                            </div>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                                Section 3 of 5
                            </span>
                        </div>

                        <!-- Father & Mother -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3.5 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fa-solid fa-person fs-8"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0 fs-8">Father's Information</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Full Name</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="father_name" value="{{ old('father_name', $pendingUpdate ? $pendingUpdate->father_name : $employee->father_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Mobile No</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body font-monospace" name="father_mobile" value="{{ old('father_mobile', $pendingUpdate ? $pendingUpdate->father_mobile : $employee->father_mobile) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Occupation</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="father_occupation" value="{{ old('father_occupation', $pendingUpdate ? $pendingUpdate->father_occupation : $employee->father_occupation) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Address</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="father_address" value="{{ old('father_address', $pendingUpdate ? $pendingUpdate->father_address : $employee->father_address) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3.5 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fa-solid fa-person-dress fs-8"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0 fs-8">Mother's Information</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Full Name</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="mother_name" value="{{ old('mother_name', $pendingUpdate ? $pendingUpdate->mother_name : $employee->mother_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Mobile No</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body font-monospace" name="mother_mobile" value="{{ old('mother_mobile', $pendingUpdate ? $pendingUpdate->mother_mobile : $employee->mother_mobile) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Occupation</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="mother_occupation" value="{{ old('mother_occupation', $pendingUpdate ? $pendingUpdate->mother_occupation : $employee->mother_occupation) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Address</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="mother_address" value="{{ old('mother_address', $pendingUpdate ? $pendingUpdate->mother_address : $employee->mother_address) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Brother & Sister -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3.5 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fa-solid fa-people-arrows fs-8"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0 fs-8">Brother's Information</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Full Name</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="brother_name" value="{{ old('brother_name', $pendingUpdate ? $pendingUpdate->brother_name : $employee->brother_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Mobile No</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body font-monospace" name="brother_mobile" value="{{ old('brother_mobile', $pendingUpdate ? $pendingUpdate->brother_mobile : $employee->brother_mobile) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Occupation</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="brother_occupation" value="{{ old('brother_occupation', $pendingUpdate ? $pendingUpdate->brother_occupation : $employee->brother_occupation) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Address</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="brother_address" value="{{ old('brother_address', $pendingUpdate ? $pendingUpdate->brother_address : $employee->brother_address) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3.5 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fa-solid fa-people-arrows fs-8"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0 fs-8">Sister's Information</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Full Name</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="sister_name" value="{{ old('sister_name', $pendingUpdate ? $pendingUpdate->sister_name : $employee->sister_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Mobile No</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body font-monospace" name="sister_mobile" value="{{ old('sister_mobile', $pendingUpdate ? $pendingUpdate->sister_mobile : $employee->sister_mobile) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Occupation</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="sister_occupation" value="{{ old('sister_occupation', $pendingUpdate ? $pendingUpdate->sister_occupation : $employee->sister_occupation) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Address</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="sister_address" value="{{ old('sister_address', $pendingUpdate ? $pendingUpdate->sister_address : $employee->sister_address) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spouse & Children -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3.5 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fa-solid fa-heart fs-8"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0 fs-8">Spouse Information</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Full Name</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="spouse_name" value="{{ old('spouse_name', $pendingUpdate ? $pendingUpdate->spouse_name : $employee->spouse_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Mobile No</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body font-monospace" name="spouse_mobile" value="{{ old('spouse_mobile', $pendingUpdate ? $pendingUpdate->spouse_mobile : $employee->spouse_mobile) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Occupation</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="spouse_occupation" value="{{ old('spouse_occupation', $pendingUpdate ? $pendingUpdate->spouse_occupation : $employee->spouse_occupation) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Address</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="spouse_address" value="{{ old('spouse_address', $pendingUpdate ? $pendingUpdate->spouse_address : $employee->spouse_address) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3.5 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fa-solid fa-child fs-8"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0 fs-8">Children Details</h6>
                                    </div>
                                    <div class="row g-2 mb-2 pb-2 border-bottom">
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Child 1 Name</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="child1_name" value="{{ old('child1_name', $pendingUpdate ? $pendingUpdate->child1_name : $employee->child1_name) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Age</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body font-monospace" name="child1_age" value="{{ old('child1_age', $pendingUpdate ? $pendingUpdate->child1_age : $employee->child1_age) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Gender</label>
                                            <select class="form-select form-select-sm fs-8 bg-body" name="child1_gender">
                                                <option value="">Gender</option>
                                                <option value="Male" {{ old('child1_gender', $pendingUpdate ? $pendingUpdate->child1_gender : $employee->child1_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('child1_gender', $pendingUpdate ? $pendingUpdate->child1_gender : $employee->child1_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Child 2 Name</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body" name="child2_name" value="{{ old('child2_name', $pendingUpdate ? $pendingUpdate->child2_name : $employee->child2_name) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Age</label>
                                            <input type="text" class="form-control form-control-sm fs-8 bg-body font-monospace" name="child2_age" value="{{ old('child2_age', $pendingUpdate ? $pendingUpdate->child2_age : $employee->child2_age) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fs-9 text-body-secondary mb-1">Gender</label>
                                            <select class="form-select form-select-sm fs-8 bg-body" name="child2_gender">
                                                <option value="">Gender</option>
                                                <option value="Male" {{ old('child2_gender', $pendingUpdate ? $pendingUpdate->child2_gender : $employee->child2_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('child2_gender', $pendingUpdate ? $pendingUpdate->child2_gender : $employee->child2_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Emergency Contact Tab -->
                    <div class="tab-pane fade" id="emergencyTabContent" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                            <div>
                                <h5 class="fw-bold text-body-emphasis mb-1">Emergency Contact & Next of Kin</h5>
                                <p class="text-body-secondary fs-8 mb-0">Designate your primary emergency contact for urgent workplace notifications.</p>
                            </div>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                                Section 4 of 5
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Contact Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control fs-8 bg-body" name="emergency_contact_name" value="{{ old('emergency_contact_name', $pendingUpdate ? $pendingUpdate->emergency_contact_name : $employee->emergency_contact_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Relationship to Employee <span class="text-danger">*</span></label>
                                <input type="text" class="form-control fs-8 bg-body" name="emergency_contact_relation" placeholder="e.g. Spouse, Father, Sibling, Friend" value="{{ old('emergency_contact_relation', $pendingUpdate ? $pendingUpdate->emergency_contact_relation : $employee->emergency_contact_relation) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Emergency Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control fs-8 bg-body font-monospace" name="emergency_contact_mobile" value="{{ old('emergency_contact_mobile', $pendingUpdate ? $pendingUpdate->emergency_contact_mobile : $employee->emergency_contact_mobile) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Occupation</label>
                                <input type="text" class="form-control fs-8 bg-body" name="emergency_contact_occupation" value="{{ old('emergency_contact_occupation', $pendingUpdate ? $pendingUpdate->emergency_contact_occupation : $employee->emergency_contact_occupation) }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold fs-9 text-uppercase text-body-secondary mb-1">Emergency Postal Address</label>
                                <textarea class="form-control fs-8 bg-body" name="emergency_contact_address" rows="2">{{ old('emergency_contact_address', $pendingUpdate ? $pendingUpdate->emergency_contact_address : $employee->emergency_contact_address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Statutory / Benefits Tab -->
                    <div class="tab-pane fade" id="statutoryTabContent" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                            <div>
                                <h5 class="fw-bold text-body-emphasis mb-1">Corporate Benefits & Statutory Programs</h5>
                                <p class="text-body-secondary fs-8 mb-0">Enroll in or adjust participation in organization benefits schemes.</p>
                            </div>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                                Section 5 of 5
                            </span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-4 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2.5 mb-2">
                                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                            <i class="fa-solid fa-hospital-user fs-7"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0">Group Health Insurance Opt-In</h6>
                                    </div>
                                    <p class="text-body-secondary fs-8 mb-3">Participate in the company-sponsored medical and hospitalization insurance coverage program for yourself and eligible dependents.</p>
                                    <select class="form-select fs-8 bg-body" name="health_ins_opted">
                                        <option value="No" {{ old('health_ins_opted', $pendingUpdate ? $pendingUpdate->health_ins_opted : $employee->health_ins_opted) == 'No' ? 'selected' : '' }}>No &bull; Opt out of group health insurance</option>
                                        <option value="Yes" {{ old('health_ins_opted', $pendingUpdate ? $pendingUpdate->health_ins_opted : $employee->health_ins_opted) == 'Yes' ? 'selected' : '' }}>Yes &bull; Enroll me in group health insurance</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-4 border rounded-3 bg-body-tertiary h-100">
                                    <div class="d-flex align-items-center gap-2.5 mb-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                            <i class="fa-solid fa-piggy-bank fs-7"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-0">Provident Fund (PF) Contribution</h6>
                                    </div>
                                    <p class="text-body-secondary fs-8 mb-3">Choose whether you wish to opt in for the Employees' Provident Fund deduction and employer statutory co-contributions.</p>
                                    <select class="form-select fs-8 bg-body" name="pf_opted">
                                        <option value="No" {{ old('pf_opted', $pendingUpdate ? $pendingUpdate->pf_opted : $employee->pf_opted) == 'No' ? 'selected' : '' }}>No &bull; Opt out of Provident Fund deductions</option>
                                        <option value="Yes" {{ old('pf_opted', $pendingUpdate ? $pendingUpdate->pf_opted : $employee->pf_opted) == 'Yes' ? 'selected' : '' }}>Yes &bull; Enroll in Provident Fund contributions</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Actions Bar (Rule 12 Compliant) -->
            <div class="card-footer border-top bg-transparent py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2 text-body-secondary fs-9">
                    <i class="fa-solid fa-lock text-primary"></i>
                    <span>Your edits will be safely staged for HR review without overwriting your live records immediately.</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm fw-semibold">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 py-2 transition-all hover-lift submit-loader" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-circle-check me-1.5"></i> Submit Profile Update Request
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('css')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.08) !important;
    }
    #profileUpdateTabs .nav-link {
        color: var(--bs-body-color, #475569);
        background: transparent;
        transition: all 0.15s ease-in-out;
    }
    #profileUpdateTabs .nav-link.active {
        color: var(--bs-primary, #3b82f6) !important;
        border-bottom-color: var(--bs-primary, #3b82f6) !important;
    }
    [data-bs-theme="dark"] #profileUpdateTabs .nav-link {
        color: #94a3b8;
    }
    [data-bs-theme="dark"] #profileUpdateTabs .nav-link.active {
        color: #60a5fa !important;
        border-bottom-color: #60a5fa !important;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab styling toggler
        const tabs = document.querySelectorAll('#profileUpdateTabs .nav-link');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                tabs.forEach(t => {
                    t.classList.remove('text-body-emphasis');
                    t.classList.add('text-body-secondary');
                });
                this.classList.add('text-body-emphasis');
                this.classList.remove('text-body-secondary');
            });
        });
    });
</script>
@endpush
