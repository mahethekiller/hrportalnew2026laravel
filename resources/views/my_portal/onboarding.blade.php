<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Onboarding - Antigravity</title>
    
    <!-- Local Vendor CSS Assets -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-body-secondary py-5">
    <div class="container" style="max-width: 900px;">
        <div class="text-center mb-5">
            <i class="fa-solid fa-layer-group text-primary fs-1 mb-2"></i>
            <h2 class="fw-bold text-gray-900 mb-0">Antigravity Onboarding Portal</h2>
            <p class="text-muted fs-8">Welcome to the team! Please fill out your profile details to initiate your HR registration.</p>
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
                    <h6 class="fw-bold mb-1 text-gray-900">Registration Details Staged</h6>
                    <p class="fs-9 mb-0 text-muted">You have already submitted your onboarding profile on <strong>{{ $pendingUpdate->added_date }}</strong>. If you want to correct any details, you can edit them below and resubmit.</p>
                </div>
            </div>
        @endif

        <form action="{{ route('onboarding.store', $token) }}" method="POST">
            @csrf

            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-header border-0 bg-transparent pt-4 pb-0">
                    <ul class="nav nav-tabs nav-stretch fs-6 border-bottom" id="onboardingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-gray-700 fw-bold pb-3 me-4 border-0 border-bottom" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basicTabContent" type="button" role="tab"><i class="fa-solid fa-address-card me-1 text-primary"></i> Personal Bio</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-gray-700 fw-bold pb-3 me-4 border-0 border-bottom" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contactTabContent" type="button" role="tab"><i class="fa-solid fa-phone me-1 text-success"></i> Contact & Address</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-gray-700 fw-bold pb-3 me-4 border-0 border-bottom" id="family-tab" data-bs-toggle="tab" data-bs-target="#familyTabContent" type="button" role="tab"><i class="fa-solid fa-users me-1 text-warning"></i> Family Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-gray-700 fw-bold pb-3 me-4 border-0 border-bottom" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergencyTabContent" type="button" role="tab"><i class="fa-solid fa-truck-medical me-1 text-danger"></i> Emergency Contact</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-gray-700 fw-bold pb-3 border-0 border-bottom" id="statutory-tab" data-bs-toggle="tab" data-bs-target="#statutoryTabContent" type="button" role="tab"><i class="fa-solid fa-shield-halved me-1 text-info"></i> Benefits Opt-in</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="onboardingTabsContent">
                        
                        <!-- 1. Personal Bio Tab -->
                        <div class="tab-pane fade show active" id="basicTabContent" role="tabpanel">
                            <h5 class="fw-bold text-gray-900 mb-4 fs-7">Basic Personal Details</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">First Name</label>
                                    <input type="text" class="form-control fs-8 py-2" name="first_name" value="{{ old('first_name', $pendingUpdate ? $pendingUpdate->first_name : $employee->first_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Last Name</label>
                                    <input type="text" class="form-control fs-8 py-2" name="last_name" value="{{ old('last_name', $pendingUpdate ? $pendingUpdate->last_name : $employee->last_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Personal Email Address</label>
                                    <input type="email" class="form-control fs-8 py-2" name="email_personal" value="{{ old('email_personal', $pendingUpdate ? $pendingUpdate->email_personal : $employee->email_personal) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Contact Mobile Number</label>
                                    <input type="text" class="form-control fs-8 py-2" name="contact_no" value="{{ old('contact_no', $pendingUpdate ? $pendingUpdate->contact_no : $employee->contact_no) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Date of Birth</label>
                                    <input type="date" class="form-control fs-8 py-2" name="date_of_birth" value="{{ old('date_of_birth', $pendingUpdate ? $pendingUpdate->date_of_birth : $employee->date_of_birth) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Gender</label>
                                    <select class="form-select fs-8 py-2" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $pendingUpdate ? $pendingUpdate->gender : $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Mother Tongue</label>
                                    <input type="text" class="form-control fs-8 py-2" name="mother_tongue" value="{{ old('mother_tongue', $pendingUpdate ? $pendingUpdate->mother_tongue : $employee->mother_tongue) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Place of Birth</label>
                                    <input type="text" class="form-control fs-8 py-2" name="place_of_birth" value="{{ old('place_of_birth', $pendingUpdate ? $pendingUpdate->place_of_birth : $employee->place_of_birth) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Blood Group</label>
                                    <input type="text" class="form-control fs-8 py-2" name="blood_group" value="{{ old('blood_group', $pendingUpdate ? $pendingUpdate->blood_group : $employee->blood_group) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Marital Status</label>
                                    <select class="form-select fs-8 py-2" name="marital_status">
                                        <option value="">Select Status</option>
                                        <option value="Single" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ old('marital_status', $pendingUpdate ? $pendingUpdate->marital_status : $employee->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">PAN Card Number</label>
                                    <input type="text" class="form-control fs-8 py-2" name="pan_number" value="{{ old('pan_number', $pendingUpdate ? $pendingUpdate->pan_number : $employee->pan_number) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Aadhar Card Number</label>
                                    <input type="text" class="form-control fs-8 py-2" name="aadhar_no" value="{{ old('aadhar_no', $pendingUpdate ? $pendingUpdate->aadhar_no : $employee->aadhar_no) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Social Category</label>
                                    <input type="text" class="form-control fs-8 py-2" name="category" value="{{ old('category', $pendingUpdate ? $pendingUpdate->category : $employee->category) }}">
                                </div>
                            </div>
                        </div>

                        <!-- 2. Contact & Address Tab -->
                        <div class="tab-pane fade" id="contactTabContent" role="tabpanel">
                            <h5 class="fw-bold text-gray-900 mb-4 fs-7">Contact Channels & Permanent Address</h5>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Permanent Address</label>
                                    <textarea class="form-control fs-8" name="address" rows="3">{{ old('address', $pendingUpdate ? $pendingUpdate->address : $employee->address) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Communication / Temporary Address</label>
                                    <textarea class="form-control fs-8" name="address_com" rows="3">{{ old('address_com', $pendingUpdate ? $pendingUpdate->address_com : $employee->address_com) }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">City</label>
                                    <input type="text" class="form-control fs-8 py-2" name="city" value="{{ old('city', $pendingUpdate ? $pendingUpdate->city : $employee->city) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">State</label>
                                    <input type="text" class="form-control fs-8 py-2" name="state" value="{{ old('state', $pendingUpdate ? $pendingUpdate->state : $employee->state) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Pincode</label>
                                    <input type="text" class="form-control fs-8 py-2" name="pincode" value="{{ old('pincode', $pendingUpdate ? $pendingUpdate->pincode : $employee->pincode) }}">
                                </div>
                            </div>

                            <h5 class="fw-bold text-gray-900 mb-4 fs-7">Other Details</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Official Contact Number</label>
                                    <input type="text" class="form-control fs-8 py-2" name="official_contact_no" value="{{ old('official_contact_no', $pendingUpdate ? $pendingUpdate->official_contact_no : $employee->official_contact_no) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Paytm Registered Number</label>
                                    <input type="text" class="form-control fs-8 py-2" name="paytm_no" value="{{ old('paytm_no', $pendingUpdate ? $pendingUpdate->paytm_no : $employee->paytm_no) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Skype Username</label>
                                    <input type="text" class="form-control fs-8 py-2" name="skype_id" value="{{ old('skype_id', $pendingUpdate ? $pendingUpdate->skype_id : $employee->skype_id) }}">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Family Details Tab -->
                        <div class="tab-pane fade" id="familyTabContent" role="tabpanel">
                            <!-- Father & Mother -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6 p-3 border rounded-3 bg-light">
                                    <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-male me-2 text-primary"></i> Father's Details</h6>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-muted">Father's Name</label>
                                            <input type="text" class="form-control fs-8 py-1" name="father_name" value="{{ old('father_name', $pendingUpdate ? $pendingUpdate->father_name : $employee->father_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-muted">Mobile</label>
                                            <input type="text" class="form-control fs-8 py-1" name="father_mobile" value="{{ old('father_mobile', $pendingUpdate ? $pendingUpdate->father_mobile : $employee->father_mobile) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fs-9 text-muted">Address</label>
                                            <input type="text" class="form-control fs-8 py-1" name="father_address" value="{{ old('father_address', $pendingUpdate ? $pendingUpdate->father_address : $employee->father_address) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 p-3 border rounded-3 bg-light">
                                    <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-female me-2 text-danger"></i> Mother's Details</h6>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-muted">Mother's Name</label>
                                            <input type="text" class="form-control fs-8 py-1" name="mother_name" value="{{ old('mother_name', $pendingUpdate ? $pendingUpdate->mother_name : $employee->mother_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-muted">Mobile</label>
                                            <input type="text" class="form-control fs-8 py-1" name="mother_mobile" value="{{ old('mother_mobile', $pendingUpdate ? $pendingUpdate->mother_mobile : $employee->mother_mobile) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fs-9 text-muted">Address</label>
                                            <input type="text" class="form-control fs-8 py-1" name="mother_address" value="{{ old('mother_address', $pendingUpdate ? $pendingUpdate->mother_address : $employee->mother_address) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Spouse & Children -->
                            <div class="row g-4">
                                <div class="col-md-6 p-3 border rounded-3 bg-light">
                                    <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-heart me-2 text-danger"></i> Spouse Details</h6>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label fs-9 text-muted">Spouse Name</label>
                                            <input type="text" class="form-control fs-8 py-1" name="spouse_name" value="{{ old('spouse_name', $pendingUpdate ? $pendingUpdate->spouse_name : $employee->spouse_name) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-9 text-muted">Mobile</label>
                                            <input type="text" class="form-control fs-8 py-1" name="spouse_mobile" value="{{ old('spouse_mobile', $pendingUpdate ? $pendingUpdate->spouse_mobile : $employee->spouse_mobile) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 p-3 border rounded-3 bg-light">
                                    <h6 class="fw-bold text-gray-900 mb-3"><i class="fa-solid fa-child me-2 text-success"></i> Children Details</h6>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label fs-9 text-muted">Child 1 Name</label>
                                            <input type="text" class="form-control fs-8 py-1" name="child1_name" value="{{ old('child1_name', $pendingUpdate ? $pendingUpdate->child1_name : $employee->child1_name) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fs-9 text-muted">Age</label>
                                            <input type="text" class="form-control fs-8 py-1" name="child1_age" value="{{ old('child1_age', $pendingUpdate ? $pendingUpdate->child1_age : $employee->child1_age) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fs-9 text-muted">Gender</label>
                                            <select class="form-select fs-9 py-1" name="child1_gender">
                                                <option value="">Gender</option>
                                                <option value="Male" {{ old('child1_gender', $pendingUpdate ? $pendingUpdate->child1_gender : $employee->child1_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('child1_gender', $pendingUpdate ? $pendingUpdate->child1_gender : $employee->child1_gender) == 'Female' ? 'selected' : '' }}>Female</option>
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
                                    <input type="text" class="form-control fs-8 py-2" name="emergency_contact_name" value="{{ old('emergency_contact_name', $pendingUpdate ? $pendingUpdate->emergency_contact_name : $employee->emergency_contact_name) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Relationship</label>
                                    <input type="text" class="form-control fs-8 py-2" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $pendingUpdate ? $pendingUpdate->emergency_contact_relation : $employee->emergency_contact_relation) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Mobile Phone Number</label>
                                    <input type="text" class="form-control fs-8 py-2" name="emergency_contact_mobile" value="{{ old('emergency_contact_mobile', $pendingUpdate ? $pendingUpdate->emergency_contact_mobile : $employee->emergency_contact_mobile) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Contact Occupation</label>
                                    <input type="text" class="form-control fs-8 py-2" name="emergency_contact_occupation" value="{{ old('emergency_contact_occupation', $pendingUpdate ? $pendingUpdate->emergency_contact_occupation : $employee->emergency_contact_occupation) }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-9 text-muted text-uppercase">Address</label>
                                    <textarea class="form-control fs-8" name="emergency_contact_address" rows="2">{{ old('emergency_contact_address', $pendingUpdate ? $pendingUpdate->emergency_contact_address : $employee->emergency_contact_address) }}</textarea>
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
                                        <select class="form-select fs-8 py-2" name="health_ins_opted">
                                            <option value="No" {{ old('health_ins_opted', $pendingUpdate ? $pendingUpdate->health_ins_opted : $employee->health_ins_opted) == 'No' ? 'selected' : '' }}>No, I do not want health insurance</option>
                                            <option value="Yes" {{ old('health_ins_opted', $pendingUpdate ? $pendingUpdate->health_ins_opted : $employee->health_ins_opted) == 'Yes' ? 'selected' : '' }}>Yes, enroll me in corporate health insurance</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light">
                                        <h6 class="fw-bold text-gray-900 mb-2"><i class="fa-solid fa-piggy-bank text-primary me-2"></i> Provident Fund (PF) Contribution</h6>
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
                    <button type="submit" class="btn btn-primary fw-bold px-6 py-2 fs-8"><i class="fa-solid fa-paper-plane me-2"></i> Submit Onboarding Profile</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Vendor Scripts -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form');
            if (form) {
                form.addEventListener('invalid', function(e) {
                    var invalidEl = e.target;
                    var tabPane = invalidEl.closest('.tab-pane');
                    if (tabPane) {
                        var tabId = tabPane.getAttribute('id');
                        var tabTrigger = document.querySelector('[data-bs-target="#' + tabId + '"]');
                        if (tabTrigger) {
                            bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                            setTimeout(function() { invalidEl.focus(); }, 150);
                        }
                    }
                }, true);
            }
        });
    </script>
</body>
</html>
