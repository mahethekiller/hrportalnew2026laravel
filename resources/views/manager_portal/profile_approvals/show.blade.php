@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-code-compare me-2 text-primary"></i> Compare & Approve Profile Changes</h4>
        <p class="text-muted fs-8 mb-0">Review modifications requested by <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong> on {{ $update->added_date }}.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
        <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-light btn-sm fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Back to Queue</a>
    </div>
</div>

<form action="{{ route('manager-portal.profile_approvals.approve', $update->id) }}" method="POST">
    @csrf

    <div class="row g-4">
        <!-- Diff comparison columns -->
        <div class="col-lg-8">
            
            @php
                // Array of fields with friendly labels grouped by approval section
                $sections = [
                    'basic' => [
                        'title' => 'Personal Bio & Basic Info',
                        'icon' => 'fa-solid fa-address-card text-primary',
                        'fields' => [
                            'first_name' => 'First Name',
                            'last_name' => 'Last Name',
                            'email_personal' => 'Personal Email',
                            'contact_no' => 'Contact Mobile',
                            'date_of_birth' => 'Date of Birth',
                            'gender' => 'Gender',
                            'mother_tongue' => 'Mother Tongue',
                            'place_of_birth' => 'Place of Birth',
                            'blood_group' => 'Blood Group',
                            'marital_status' => 'Marital Status',
                            'pan_number' => 'PAN Card Number',
                            'aadhar_no' => 'Aadhar Number',
                            'category' => 'Social Category',
                            'official_contact_no' => 'Official Contact',
                            'vehicle_type' => 'Vehicle Type',
                            'vehicle_no' => 'Vehicle No',
                            'paytm_no' => 'Paytm Number',
                            'skype_id' => 'Skype ID'
                        ]
                    ],
                    'address' => [
                        'title' => 'Addresses & Communication',
                        'icon' => 'fa-solid fa-location-dot text-success',
                        'fields' => [
                            'address' => 'Permanent Address',
                            'address_com' => 'Communication Address',
                            'city' => 'City',
                            'state' => 'State',
                            'pincode' => 'Pincode'
                        ]
                    ],
                    'father' => [
                        'title' => "Father's Details",
                        'icon' => 'fa-solid fa-male text-primary',
                        'fields' => [
                            'father_name' => 'Father\'s Name',
                            'father_mobile' => 'Father\'s Mobile',
                            'father_address' => 'Father\'s Address'
                        ]
                    ],
                    'mother' => [
                        'title' => "Mother's Details",
                        'icon' => 'fa-solid fa-female text-danger',
                        'fields' => [
                            'mother_name' => 'Mother\'s Name',
                            'mother_mobile' => 'Mother\'s Mobile',
                            'mother_address' => 'Mother\'s Address'
                        ]
                    ],
                    'brother' => [
                        'title' => "Brother's Details",
                        'icon' => 'fa-solid fa-people-arrows text-info',
                        'fields' => [
                            'brother_name' => 'Brother\'s Name',
                            'brother_mobile' => 'Brother\'s Mobile'
                        ]
                    ],
                    'sister' => [
                        'title' => "Sister's Details",
                        'icon' => 'fa-solid fa-people-arrows text-warning',
                        'fields' => [
                            'sister_name' => 'Sister\'s Name',
                            'sister_mobile' => 'Sister\'s Mobile'
                        ]
                    ],
                    'spouse' => [
                        'title' => "Spouse Details",
                        'icon' => 'fa-solid fa-heart text-danger',
                        'fields' => [
                            'spouse_name' => 'Spouse Name',
                            'spouse_mobile' => 'Spouse Mobile',
                            'spouse_address' => 'Spouse Address'
                        ]
                    ],
                    'c1' => [
                        'title' => "Child 1 Details",
                        'icon' => 'fa-solid fa-child text-success',
                        'fields' => [
                            'child1_name' => 'Child 1 Name',
                            'child1_age' => 'Child 1 Age',
                            'child1_gender' => 'Child 1 Gender'
                        ]
                    ],
                    'c2' => [
                        'title' => "Child 2 Details",
                        'icon' => 'fa-solid fa-child text-success',
                        'fields' => [
                            'child2_name' => 'Child 2 Name',
                            'child2_age' => 'Child 2 Age',
                            'child2_gender' => 'Child 2 Gender'
                        ]
                    ],
                    'emer' => [
                        'title' => "Emergency Contact & Next of Kin",
                        'icon' => 'fa-solid fa-truck-medical text-danger',
                        'fields' => [
                            'emergency_contact_name' => 'Contact Name',
                            'emergency_contact_relation' => 'Relationship',
                            'emergency_contact_mobile' => 'Mobile No',
                            'emergency_contact_address' => 'Address'
                        ]
                    ],
                    'social' => [
                        'title' => "Social Network Profiles",
                        'icon' => 'fa-solid fa-share-nodes text-primary',
                        'fields' => [
                            'facebook_link' => 'Facebook',
                            'twitter_link' => 'Twitter/X',
                            'linkdedin_link' => 'LinkedIn',
                            'instagram_link' => 'Instagram'
                        ]
                    ],
                    'benefits' => [
                        'title' => "Statutory & Corporate Benefits",
                        'icon' => 'fa-solid fa-shield-halved text-info',
                        'fields' => [
                            'health_ins_opted' => 'Health Insurance opt-in',
                            'pf_opted' => 'Provident Fund (PF) opt-in'
                        ]
                    ]
                ];
            @endphp

            @foreach($sections as $secKey => $secData)
                @php
                    // Count how many fields in this section were modified
                    $modifiedCount = 0;
                    foreach($secData['fields'] as $field => $label) {
                        $current = (string) $employee->{$field};
                        $proposed = (string) $update->{$field};
                        if ($proposed !== '' && $proposed !== $current) {
                            $modifiedCount++;
                        }
                    }
                @endphp

                <div class="card border-0 shadow-sm rounded-3 bg-white mb-4" id="card_{{ $secKey }}">
                    <div class="card-header border-0 bg-transparent pt-3 d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold text-gray-900 fs-7 mb-0">
                            <i class="{{ $secData['icon'] }} me-2"></i> {{ $secData['title'] }}
                            @if($modifiedCount > 0)
                                <span class="badge bg-light-warning text-warning fs-9 ms-2">{{ $modifiedCount }} Mod</span>
                            @else
                                <span class="badge bg-light-secondary text-secondary fs-9 ms-2">No Changes</span>
                            @endif
                        </h5>
                        
                        <div class="form-check form-switch">
                            <input class="form-check-input section-checkbox" type="checkbox" name="sections[]" value="{{ $secKey }}" id="chk_{{ $secKey }}" {{ $modifiedCount > 0 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold fs-9 text-muted text-uppercase" for="chk_{{ $secKey }}">Approve Section</label>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 fs-8">
                                <thead class="bg-light-light text-muted">
                                    <tr>
                                        <th class="ps-4" style="width: 25%;">Field</th>
                                        <th style="width: 35%;">Current Value</th>
                                        <th style="width: 40%;" class="pe-4">Proposed Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($secData['fields'] as $field => $label)
                                        @php
                                            $current = $employee->{$field};
                                            $proposed = $update->{$field};
                                            $isChanged = ($proposed !== '' && $proposed !== null && $proposed !== $current);
                                        @endphp
                                        <tr class="{{ $isChanged ? 'table-success bg-opacity-10' : '' }}">
                                            <td class="ps-4 fw-semibold text-gray-700">{{ $label }}</td>
                                            <td class="text-muted">{{ $current ?? '—' }}</td>
                                            <td class="pe-4 {{ $isChanged ? 'fw-bold text-success' : 'text-muted' }}">
                                                {{ $proposed ?? '—' }}
                                                @if($isChanged)
                                                    <span class="badge bg-success fs-9 ms-2">Modified</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Approval checklist sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white sticky-top" style="top: 85px;">
                <div class="card-header border-0 bg-transparent pt-4 pb-0">
                    <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-clipboard-check me-2 text-primary"></i> Action Panel</h5>
                </div>
                <div class="card-body">
                    <div class="p-3 border rounded-3 bg-light mb-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="selectAllSections" onclick="toggleAllCheckboxes(this)" checked>
                            <label class="form-check-label fw-bold text-gray-900 fs-8" for="selectAllSections">
                                Select All Sections
                            </label>
                        </div>
                        <p class="text-muted fs-9 mb-0">Toggles the approval status for all sections shown on the left page.</p>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="action" value="approve" class="btn btn-primary fw-bold py-2 fs-8">
                            <i class="fa-solid fa-circle-check me-2"></i> Approve Selected
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-outline-danger fw-bold py-2 fs-8" onclick="return confirm('Are you sure you want to completely reject this update request?');">
                            <i class="fa-solid fa-circle-xmark me-2"></i> Reject Entire Request
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function toggleAllCheckboxes(master) {
    const checkboxes = document.querySelectorAll('.section-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = master.checked;
    });
}
</script>
@endsection
