@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-user-pen me-2 text-primary"></i> Request Profile Update</h4>
        <p class="text-muted fs-8 mb-0">Modify your personal, contact, family, and emergency information. Submitted edits will be staged for HR approval.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Back to Profile</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    </div>
@endif

@if($pendingUpdate)
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
        <i class="fa-solid fa-clock-rotate-left fs-4 me-3 text-warning"></i>
        <div>
            <h6 class="fw-bold mb-1 text-gray-900">Pending Update Awaiting Approval</h6>
            <p class="fs-9 mb-0 text-muted">You submitted a profile update request on <strong>{{ $pendingUpdate->added_date }}</strong>. You can edit your proposed changes below and resubmit them.</p>
        </div>
    </div>
@endif

<form action="{{ route('my-portal.profile-update.store') }}" method="POST">
    @csrf

    <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
        <div class="card-header border-0 bg-transparent pt-4 pb-0">
            <ul class="nav nav-line-tabs nav-stretch fs-6 border-bottom" id="profileUpdateTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-gray-700 fw-bold pb-4 me-6" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basicTabContent" type="button" role="tab"><i class="fa-solid fa-address-card me-2 text-primary"></i> Personal Bio</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-gray-700 fw-bold pb-4 me-6" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contactTabContent" type="button" role="tab"><i class="fa-solid fa-phone me-2 text-success"></i> Contact & Address</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-gray-700 fw-bold pb-4 me-6" id="family-tab" data-bs-toggle="tab" data-bs-target="#familyTabContent" type="button" role="tab"><i class="fa-solid fa-users me-2 text-warning"></i> Family Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-gray-700 fw-bold pb-4 me-6" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergencyTabContent" type="button" role="tab"><i class="fa-solid fa-truck-medical me-2 text-danger"></i> Emergency Contact</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-gray-700 fw-bold pb-4" id="statutory-tab" data-bs-toggle="tab" data-bs-target="#statutoryTabContent" type="button" role="tab"><i class="fa-solid fa-shield-halved me-2 text-info"></i> Benefits Opt-in</button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="profileUpdateTabsContent">
                
                <!-- 1. Personal Bio Tab -->
                <div class="tab-pane fade show active" id="basicTabContent" role="tabpanel">
                    <h5 class="fw-bold text-gray-900 mb-4 fs-7">Basic Personal Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">First Name</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="first_name" value="{{ old('first_name', $pendingUpdate ? $pendingUpdate->first_name : $employee->first_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Last Name</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="last_name" value="{{ old('last_name', $pendingUpdate ? $pendingUpdate->last_name : $employee->last_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Personal Email Address</label>
                            <input type="email" class="form-control form-control-solid fs-8 py-2" name="email_personal" value="{{ old('email_personal', $pendingUpdate ? $pendingUpdate->email_personal : $employee->email_personal) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Contact Mobile Number</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="contact_no" value="{{ old('contact_no', $pendingUpdate ? $pendingUpdate->contact_no : $employee->contact_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Date of Birth</label>
                            <input type="date" class="form-control form-control-solid fs-8 py-2" name="date_of_birth" value="{{ old('date_of_birth', $pendingUpdate ? $pendingUpdate->date_of_birth : $employee->date_of_birth) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Gender</label>
                            <select class="form-select form-select-solid fs-8 py-2" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Mother Tongue</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="mother_tongue" value="{{ old('mother_tongue', $pendingUpdate ? $pendingUpdate->mother_tongue : $employee->mother_tongue) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Place of Birth</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="place_of_birth" value="{{ old('place_of_birth', $pendingUpdate ? $pendingUpdate->place_of_birth : $employee->place_of_birth) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Blood Group</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="blood_group" value="{{ old('blood_group', $pendingUpdate ? $pendingUpdate->blood_group : $employee->blood_group) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Marital Status</label>
                            <select class="form-select form-select-solid fs-8 py-2" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="Single" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">PAN Card Number</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="pan_number" value="{{ old('pan_number', $pendingUpdate ? $pendingUpdate->pan_number : $employee->pan_number) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Aadhar Card Number</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="aadhar_no" value="{{ old('aadhar_no', $pendingUpdate ? $pendingUpdate->aadhar_no : $employee->aadhar_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Social Category</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="category" value="{{ old('category', $pendingUpdate ? $pendingUpdate->category : $employee->category) }}">
                        </div>
                    </div>
                </div>

                <!-- 2. Contact & Address Tab -->
                <div class="tab-pane fade" id="contactTabContent" role="tabpanel">
                    <h5 class="fw-bold text-gray-900 mb-4 fs-7">Contact Channels & Permanent Address</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Permanent Address</label>
                            <textarea class="form-control form-control-solid fs-8" name="address" rows="3">{{ old('address', $pendingUpdate ? $pendingUpdate->address : $employee->address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Communication / Temporary Address</label>
                            <textarea class="form-control form-control-solid fs-8" name="address_com" rows="3">{{ old('address_com', $pendingUpdate ? $pendingUpdate->address_com : $employee->address_com) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">City</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="city" value="{{ old('city', $pendingUpdate ? $pendingUpdate->city : $employee->city) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">State</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="state" value="{{ old('state', $pendingUpdate ? $pendingUpdate->state : $employee->state) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Pincode</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="pincode" value="{{ old('pincode', $pendingUpdate ? $pendingUpdate->pincode : $employee->pincode) }}">
                        </div>
                    </div>

                    <h5 class="fw-bold text-gray-900 mb-4 fs-7">Statutory, Skype & Transport Info</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Official Contact Number</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="official_contact_no" value="{{ old('official_contact_no', $pendingUpdate ? $pendingUpdate->official_contact_no : $employee->official_contact_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Paytm Registered Number</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="paytm_no" value="{{ old('paytm_no', $pendingUpdate ? $pendingUpdate->paytm_no : $employee->paytm_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Skype Username</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="skype_id" value="{{ old('skype_id', $pendingUpdate ? $pendingUpdate->skype_id : $employee->skype_id) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Vehicle Type</label>
                            <select class="form-select form-select-solid fs-8 py-2" name="vehicle_type">
                                <option value="">Select Vehicle</option>
                                <option value="None" {{ old('vehicle_type', $pendingUpdate ? $pendingUpdate->vehicle_type : $employee->vehicle_type) == 'None' ? 'selected' : '' }}>None</option>
                                <option value="Bike" {{ old('vehicle_type', $pendingUpdate ? $pendingUpdate->vehicle_type : $employee->vehicle_type) == 'Bike' ? 'selected' : '' }}>Motorcycle/Scooter</option>
                                <option value="Car" {{ old('vehicle_type', $pendingUpdate ? $pendingUpdate->vehicle_type : $employee->vehicle_type) == 'Car' ? 'selected' : '' }}>Car/Four Wheeler</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Vehicle Registration Number</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="vehicle_no" value="{{ old('vehicle_no', $pendingUpdate ? $pendingUpdate->vehicle_no : $employee->vehicle_no) }}">
                        </div>
                    </div>
                </div>

                <!-- 3. Family Details Tab -->
                <div class="tab-pane fade" id="familyTabContent" role="tabpanel">
                    <!-- Father & Mother -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6 p-3 border rounded-3 bg-light-light">
                            <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-male me-2 text-primary"></i> Father's Details</h6>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <label class="form-label fs-9 text-muted">Father's Name</label>
                                    <input type="text" class="form-control fs-8 py-1" name="father_name" value="{{ old('father_name', $pendingUpdate ? $pendingUpdate->father_name : $employee->father_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-9 text-muted">Mobile Number</label>
                                    <input type="text" class="form-control fs-8 py-1" name="father_mobile" value="{{ old('father_mobile', $pendingUpdate ? $pendingUpdate->father_mobile : $employee->father_mobile) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Occupation</label>
                                    <input type="text" class="form-control fs-8 py-1" name="father_occupation" value="{{ old('father_occupation', $pendingUpdate ? $pendingUpdate->father_occupation : $employee->father_occupation) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Address</label>
                                    <input type="text" class="form-control fs-8 py-1" name="father_address" value="{{ old('father_address', $pendingUpdate ? $pendingUpdate->father_address : $employee->father_address) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 p-3 border rounded-3 bg-light-light">
                            <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-female me-2 text-danger"></i> Mother's Details</h6>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <label class="form-label fs-9 text-muted">Mother's Name</label>
                                    <input type="text" class="form-control fs-8 py-1" name="mother_name" value="{{ old('mother_name', $pendingUpdate ? $pendingUpdate->mother_name : $employee->mother_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-9 text-muted">Mobile Number</label>
                                    <input type="text" class="form-control fs-8 py-1" name="mother_mobile" value="{{ old('mother_mobile', $pendingUpdate ? $pendingUpdate->mother_mobile : $employee->mother_mobile) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Occupation</label>
                                    <input type="text" class="form-control fs-8 py-1" name="mother_occupation" value="{{ old('mother_occupation', $pendingUpdate ? $pendingUpdate->mother_occupation : $employee->mother_occupation) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Address</label>
                                    <input type="text" class="form-control fs-8 py-1" name="mother_address" value="{{ old('mother_address', $pendingUpdate ? $pendingUpdate->mother_address : $employee->mother_address) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Brother & Sister -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6 p-3 border rounded-3 bg-light-light">
                            <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-people-arrows me-2 text-info"></i> Brother's Details</h6>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <label class="form-label fs-9 text-muted">Brother's Name</label>
                                    <input type="text" class="form-control fs-8 py-1" name="brother_name" value="{{ old('brother_name', $pendingUpdate ? $pendingUpdate->brother_name : $employee->brother_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-9 text-muted">Mobile Number</label>
                                    <input type="text" class="form-control fs-8 py-1" name="brother_mobile" value="{{ old('brother_mobile', $pendingUpdate ? $pendingUpdate->brother_mobile : $employee->brother_mobile) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Occupation</label>
                                    <input type="text" class="form-control fs-8 py-1" name="brother_occupation" value="{{ old('brother_occupation', $pendingUpdate ? $pendingUpdate->brother_occupation : $employee->brother_occupation) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Address</label>
                                    <input type="text" class="form-control fs-8 py-1" name="brother_address" value="{{ old('brother_address', $pendingUpdate ? $pendingUpdate->brother_address : $employee->brother_address) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 p-3 border rounded-3 bg-light-light">
                            <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-people-arrows me-2 text-warning"></i> Sister's Details</h6>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <label class="form-label fs-9 text-muted">Sister's Name</label>
                                    <input type="text" class="form-control fs-8 py-1" name="sister_name" value="{{ old('sister_name', $pendingUpdate ? $pendingUpdate->sister_name : $employee->sister_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-9 text-muted">Mobile Number</label>
                                    <input type="text" class="form-control fs-8 py-1" name="sister_mobile" value="{{ old('sister_mobile', $pendingUpdate ? $pendingUpdate->sister_mobile : $employee->sister_mobile) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Occupation</label>
                                    <input type="text" class="form-control fs-8 py-1" name="sister_occupation" value="{{ old('sister_occupation', $pendingUpdate ? $pendingUpdate->sister_occupation : $employee->sister_occupation) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Address</label>
                                    <input type="text" class="form-control fs-8 py-1" name="sister_address" value="{{ old('sister_address', $pendingUpdate ? $pendingUpdate->sister_address : $employee->sister_address) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spouse & Children -->
                    <div class="row g-4">
                        <div class="col-md-6 p-3 border rounded-3 bg-light-light">
                            <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-heart me-2 text-danger"></i> Spouse Details</h6>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <label class="form-label fs-9 text-muted">Spouse Name</label>
                                    <input type="text" class="form-control fs-8 py-1" name="spouse_name" value="{{ old('spouse_name', $pendingUpdate ? $pendingUpdate->spouse_name : $employee->spouse_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-9 text-muted">Mobile Number</label>
                                    <input type="text" class="form-control fs-8 py-1" name="spouse_mobile" value="{{ old('spouse_mobile', $pendingUpdate ? $pendingUpdate->spouse_mobile : $employee->spouse_mobile) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Occupation</label>
                                    <input type="text" class="form-control fs-8 py-1" name="spouse_occupation" value="{{ old('spouse_occupation', $pendingUpdate ? $pendingUpdate->spouse_occupation : $employee->spouse_occupation) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Address</label>
                                    <input type="text" class="form-control fs-8 py-1" name="spouse_address" value="{{ old('spouse_address', $pendingUpdate ? $pendingUpdate->spouse_address : $employee->spouse_address) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 p-3 border rounded-3 bg-light-light">
                            <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-child me-2 text-success"></i> Children Details</h6>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Child 1 Name</label>
                                    <input type="text" class="form-control fs-8 py-1" name="child1_name" value="{{ old('child1_name', $pendingUpdate ? $pendingUpdate->child1_name : $employee->child1_name) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-9 text-muted">Child 1 Age</label>
                                    <input type="text" class="form-control fs-8 py-1" name="child1_age" value="{{ old('child1_age', $pendingUpdate ? $pendingUpdate->child1_age : $employee->child1_age) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-9 text-muted">Child 1 Gender</label>
                                    <select class="form-select fs-9 py-1" name="child1_gender">
                                        <option value="">Gender</option>
                                        <option value="Male" {{ old('child1_gender', $pendingUpdate ? $pendingUpdate->child1_gender : $employee->child1_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('child1_gender', $pendingUpdate ? $pendingUpdate->child1_gender : $employee->child1_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label fs-9 text-muted">Child 2 Name</label>
                                    <input type="text" class="form-control fs-8 py-1" name="child2_name" value="{{ old('child2_name', $pendingUpdate ? $pendingUpdate->child2_name : $employee->child2_name) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-9 text-muted">Child 2 Age</label>
                                    <input type="text" class="form-control fs-8 py-1" name="child2_age" value="{{ old('child2_age', $pendingUpdate ? $pendingUpdate->child2_age : $employee->child2_age) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-9 text-muted">Child 2 Gender</label>
                                    <select class="form-select fs-9 py-1" name="child2_gender">
                                        <option value="">Gender</option>
                                        <option value="Male" {{ old('child2_gender', $pendingUpdate ? $pendingUpdate->child2_gender : $employee->child2_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('child2_gender', $pendingUpdate ? $pendingUpdate->child2_gender : $employee->child2_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Emergency Contact Tab -->
                <div class="tab-pane fade" id="emergencyTabContent" role="tabpanel">
                    <h5 class="fw-bold text-gray-900 mb-4 fs-7">Emergency Contact / Next of Kin</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Contact Full Name</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="emergency_contact_name" value="{{ old('emergency_contact_name', $pendingUpdate ? $pendingUpdate->emergency_contact_name : $employee->emergency_contact_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Relationship</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $pendingUpdate ? $pendingUpdate->emergency_contact_relation : $employee->emergency_contact_relation) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Mobile Phone Number</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="emergency_contact_mobile" value="{{ old('emergency_contact_mobile', $pendingUpdate ? $pendingUpdate->emergency_contact_mobile : $employee->emergency_contact_mobile) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Contact Occupation</label>
                            <input type="text" class="form-control form-control-solid fs-8 py-2" name="emergency_contact_occupation" value="{{ old('emergency_contact_occupation', $pendingUpdate ? $pendingUpdate->emergency_contact_occupation : $employee->emergency_contact_occupation) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Address</label>
                            <textarea class="form-control form-control-solid fs-8" name="emergency_contact_address" rows="2">{{ old('emergency_contact_address', $pendingUpdate ? $pendingUpdate->emergency_contact_address : $employee->emergency_contact_address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 5. Statutory / Benefits Tab -->
                <div class="tab-pane fade" id="statutoryTabContent" role="tabpanel">
                    <h5 class="fw-bold text-gray-900 mb-4 fs-7">Corporate Statutory & Benefit Subscriptions</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <h6 class="fw-bold text-gray-900 mb-2"><i class="fa-solid fa-hospital-user text-success me-2"></i> Health Insurance Opt-In</h6>
                                <p class="text-muted fs-8 mb-3">Check whether you want to participate in the corporate group health insurance coverage scheme.</p>
                                <select class="form-select fs-8 py-2" name="health_ins_opted">
                                    <option value="No" {{ old('health_ins_opted', $pendingUpdate ? $pendingUpdate->health_ins_opted : $employee->health_ins_opted) == 'No' ? 'selected' : '' }}>No, I do not want health insurance</option>
                                    <option value="Yes" {{ old('health_ins_opted', $pendingUpdate ? $pendingUpdate->health_ins_opted : $employee->health_ins_opted) == 'Yes' ? 'selected' : '' }}>Yes, enroll me in corporate health insurance</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <h6 class="fw-bold text-gray-900 mb-2"><i class="fa-solid fa-piggy-bank text-primary me-2"></i> Provident Fund (PF) Contribution</h6>
                                <p class="text-muted fs-8 mb-3">Choose whether you wish to opt-in for the Employees' Provident Fund deduction and co-contributions.</p>
                                <select class="form-select fs-8 py-2" name="pf_opted">
                                    <option value="No" {{ old('pf_opted', $pendingUpdate ? $pendingUpdate->pf_opted : $employee->pf_opted) == 'No' ? 'selected' : '' }}>No, I opt out of Provident Fund deductions</option>
                                    <option value="Yes" {{ old('pf_opted', $pendingUpdate ? $pendingUpdate->pf_opted : $employee->pf_opted) == 'Yes' ? 'selected' : '' }}>Yes, enroll me in Provident Fund contributions</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer border-0 bg-transparent text-end pb-4 pt-0">
            <button type="submit" class="btn btn-primary fw-bold px-6 py-2 fs-8"><i class="fa-solid fa-circle-check me-2"></i> Submit Update Request</button>
        </div>
    </div>
</form>
@endsection
