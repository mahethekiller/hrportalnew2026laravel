@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title Banner -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-info-subtle text-info fw-semibold px-2.5 py-1 fs-9 rounded-pill">
                    <i class="fa-solid fa-code-compare me-1"></i> Data Diff Studio
                </span>
                <span class="text-body-secondary fs-9">• Employee ID: {{ $employee->employee_id ?? 'N/A' }}</span>
            </div>
            <h4 class="mb-0 text-body-emphasis fw-bolder tracking-tight">
                Compare & Authorize Profile Changes — {{ $employee->first_name }} {{ $employee->last_name }}
            </h4>
            <p class="text-body-secondary fs-8 mb-0">
                Submitted on <x-human-date :value="$update->added_date" /> • Verify and select approved sections to merge into core database.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('manager-portal.profile_approvals.index') }}" class="btn btn-sm btn-body border text-body-emphasis shadow-xs fw-semibold px-3 py-2 rounded-2">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Queue
            </a>
        </div>
    </div>

    <form action="{{ route('manager-portal.profile_approvals.approve', $update->id) }}" method="POST" onsubmit="submitWithLoader(this)">
        @csrf

        <div class="row g-4">
            <!-- Left Side: Diff Sections -->
            <div class="col-lg-8">
                @php
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
                        $modifiedCount = 0;
                        foreach($secData['fields'] as $field => $label) {
                            $current = (string) ($employee->{$field} ?? '');
                            $proposed = (string) ($update->{$field} ?? '');
                            if ($proposed !== '' && $proposed !== $current) {
                                $modifiedCount++;
                            }
                        }
                    @endphp

                    <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary mb-4" id="card_{{ $secKey }}">
                        <div class="card-header border-0 bg-transparent pt-3 pb-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="{{ $secData['icon'] }} fs-6"></i>
                                <h5 class="fw-bold text-body-emphasis fs-7 mb-0">{{ $secData['title'] }}</h5>
                                @if($modifiedCount > 0)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-9 ms-1 fw-bold">
                                        {{ $modifiedCount }} {{ Str::plural('Change', $modifiedCount) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-body-secondary fs-9 ms-1">No Changes</span>
                                @endif
                            </div>
                            
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input section-checkbox cursor-pointer" type="checkbox" name="sections[]" value="{{ $secKey }}" id="chk_{{ $secKey }}" {{ $modifiedCount > 0 ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold fs-9 text-body-secondary text-uppercase cursor-pointer" for="chk_{{ $secKey }}">Approve Section</label>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 fs-8 border-top">
                                    <thead class="bg-body-secondary text-body-secondary">
                                        <tr>
                                            <th class="ps-4" style="width: 28%;">Data Field</th>
                                            <th style="width: 36%;">Current Live Value</th>
                                            <th style="width: 36%;" class="pe-4">Proposed Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($secData['fields'] as $field => $label)
                                            @php
                                                $current = $employee->{$field} ?? null;
                                                $proposed = $update->{$field} ?? null;
                                                $isChanged = ($proposed !== '' && $proposed !== null && $proposed !== $current);
                                            @endphp
                                            <tr class="{{ $isChanged ? 'bg-warning-subtle bg-opacity-25' : '' }}">
                                                <td class="ps-4 fw-semibold text-body-emphasis">{{ $label }}</td>
                                                <td class="text-body-secondary font-monospace">{{ $current ?? '—' }}</td>
                                                <td class="pe-4 {{ $isChanged ? 'fw-bold text-warning' : 'text-body-secondary' }} font-monospace">
                                                    {{ $proposed ?? '—' }}
                                                    @if($isChanged)
                                                        <span class="badge bg-warning text-dark fs-9 ms-2 fw-semibold">Modified</span>
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

            <!-- Right Side: Action Control Panel (Sticky) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 bg-body-tertiary sticky-top" style="top: 85px;">
                    <div class="card-header border-0 bg-transparent pt-4 pb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-2 bg-primary-subtle text-primary fs-8">
                                <i class="fa-solid fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-body-emphasis fs-6 mb-0">Approval Action Center</h5>
                                <span class="text-body-secondary fs-9">Authorize or discard employee edits</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="p-3 border rounded-3 bg-body mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input cursor-pointer" type="checkbox" id="selectAllSections" onclick="toggleAllCheckboxes(this)" checked>
                                <label class="form-check-label fw-bold text-body-emphasis fs-8 cursor-pointer" for="selectAllSections">
                                    Select All Modified Sections
                                </label>
                            </div>
                            <p class="text-body-secondary fs-9 mb-0 leading-normal">
                                Checking a section applies only the verified fields in that category into the employee's live record.
                            </p>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="action" value="approve" class="btn btn-primary fw-bold py-2.5 fs-8 shadow-xs rounded-2">
                                <i class="fa-solid fa-circle-check me-2"></i> Approve Selected Sections
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-outline-danger fw-bold py-2.5 fs-8 rounded-2" onclick="return confirm('Are you sure you want to completely reject this update request?');">
                                <i class="fa-solid fa-circle-xmark me-2"></i> Reject Entire Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function toggleAllCheckboxes(master) {
    const checkboxes = document.querySelectorAll('.section-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = master.checked;
    });
}
</script>
@endsection
