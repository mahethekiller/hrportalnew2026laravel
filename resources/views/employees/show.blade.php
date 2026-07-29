@extends('layouts.app')

@section('title', $employee->first_name . ' ' . $employee->last_name . ' - Profile')
@section('page_title', 'Employee Profile & Sub-Resource Specs')

@section('content')
<!-- Page Header Banner -->
<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
        <p class="text-body-secondary small mb-0">Employee ID: <span class="fw-bold text-primary">{{ (!empty($employee->employee_id) && $employee->employee_id !== '0') ? $employee->employee_id : 'EMP-' . sprintf('%04d', $employee->id) }}</span></p>
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
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            @if($employee->profile_picture && file_exists(public_path('uploads/profile/' . $employee->profile_picture)))
                <img src="{{ asset('uploads/profile/' . $employee->profile_picture) }}" alt="{{ $employee->first_name }}" class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <div class="btn btn-light-primary rounded-circle p-3 fs-1" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            @endif
            <div class="flex-grow-1 text-center text-md-start">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                    <h3 class="card-title fs-4 mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                    @switch((int)$employee->is_active)
                        @case(1)<span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>@break
                        @case(2)<span class="badge badge-light-danger"><i class="fa-solid fa-ban me-1"></i>Terminated</span>@break
                        @case(3)<span class="badge badge-light-warning"><i class="fa-solid fa-person-walking-arrow-right me-1"></i>Left</span>@break
                        @case(4)<span class="badge bg-dark text-white"><i class="fa-solid fa-user-ninja me-1"></i>Abscond</span>@break
                        @case(5)<span class="badge badge-light-secondary"><i class="fa-solid fa-user-slash me-1"></i>Disable</span>@break
                        @case(0)<span class="badge badge-light-info"><i class="fa-solid fa-file-signature me-1"></i>Resigned</span>@break
                        @default<span class="badge badge-light-secondary">Unknown</span>
                    @endswitch
                </div>
                <div class="text-body-secondary fw-medium mb-2">
                    {{ $employee->designation->designation_name ?? $employee->designation->name ?? 'Staff Member' }} • {{ $employee->department->department_name ?? $employee->department->name ?? 'General Department' }}
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3 small text-body-secondary">
                    <span><i class="fa-solid fa-envelope me-1 text-primary"></i>{{ $employee->email }}</span>
                    @if($employee->contact_no)
                        <span><i class="fa-solid fa-phone me-1 text-success"></i>{{ $employee->contact_no }}</span>
                    @endif
                    @if($employee->date_of_joining)
                        <span><i class="fa-solid fa-calendar me-1 text-warning"></i>Joined {{ date('M d, Y', strtotime($employee->date_of_joining)) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profile Navigation Tabs -->
<ul class="nav nav-tabs nav-line-tabs mb-4" id="profileTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-pane" type="button"><i class="fa-solid fa-id-card me-1"></i>Overview Specs</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-pane" type="button"><i class="fa-solid fa-folder-open me-1"></i>Documents ({{ $employee->documents->count() }})</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts-pane" type="button"><i class="fa-solid fa-phone-flip me-1"></i>Emergency Contacts ({{ $employee->employeeContacts->count() }})</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank-pane" type="button"><i class="fa-solid fa-building-columns me-1"></i>Bank Accounts ({{ $employee->employeeBankaccounts->count() }})</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="qualifications-tab" data-bs-toggle="tab" data-bs-target="#qualifications-pane" type="button"><i class="fa-solid fa-graduation-cap me-1"></i>Qualifications ({{ $employee->employeeQualifications->count() }})</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="experiences-tab" data-bs-toggle="tab" data-bs-target="#experiences-pane" type="button"><i class="fa-solid fa-clock-rotate-left me-1"></i>Work History ({{ $employee->employeeWorkExperiences->count() }})</button>
    </li>
</ul>

<div class="tab-content" id="profileTabsContent">
    <!-- Tab 1: Overview Specs -->
    <div class="tab-pane fade show active" id="overview-pane" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><h3 class="card-title">Personal Demographics</h3></div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr><td class="text-body-secondary label-sm ps-0" style="width: 35%;">Full Name</td><td class="fw-semibold text-body-emphasis pe-0">{{ $employee->first_name }} {{ $employee->last_name }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Username</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->username ? '@' . $employee->username : 'N/A' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Gender</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->gender ?? 'Not Specified' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Date of Birth</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->date_of_birth ? date('M d, Y', strtotime($employee->date_of_birth)) : 'N/A' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Place of Birth</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->place_of_birth ?? 'N/A' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Marital Status</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->marital_status ?? 'N/A' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Blood Group / Tongue</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->blood_group ?? 'O+' }} ({{ $employee->mother_tongue ?? 'English' }})</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">PAN / Aadhar</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->pan_number ?? 'N/A' }} / {{ $employee->aadhar_no ?? 'N/A' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><h3 class="card-title">Employment & Payroll Specs</h3></div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr><td class="text-body-secondary label-sm ps-0" style="width: 35%;">Department</td><td class="fw-semibold text-body-emphasis pe-0">{{ $employee->department->department_name ?? $employee->department->name ?? 'General' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Designation</td><td class="fw-semibold text-body-emphasis pe-0">{{ $employee->designation->designation_name ?? $employee->designation->name ?? 'Staff Member' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Company Entity</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->company->name ?? $employee->company->company_name ?? 'Antigravity Corp' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Employment Type</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->employment_type ?? 'Full Time' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Date of Joining</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->date_of_joining ? date('M d, Y', strtotime($employee->date_of_joining)) : 'N/A' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Monthly Salary</td><td class="fw-bold text-success pe-0">{{ $employee->salary ? '$' . number_format((float)$employee->salary, 2) : 'N/A' }}</td></tr>
                                <tr><td class="text-body-secondary label-sm ps-0">Earned / Casual Leave</td><td class="fw-medium text-body-emphasis pe-0">{{ $employee->earned_leave ?? 12 }} Days / {{ $employee->casual_leave ?? 12 }} Days</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Employee Documents Sub-Resource -->
    <div class="tab-pane fade" id="documents-pane" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Employee Document Repository</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                    <i class="fa-solid fa-plus me-1"></i>Upload Document
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Document File</th>
                                <th>Expiry Date</th>
                                <th>Alert Email</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->documents as $doc)
                                <tr>
                                    <td class="fw-semibold text-body-emphasis">{{ $doc->title }}</td>
                                    <td>
                                        @if($doc->document_file)
                                            <a href="{{ asset('uploads/documents/' . $doc->document_file) }}" target="_blank" class="btn btn-light-primary btn-sm">
                                                <i class="fa-solid fa-file-arrow-down me-1"></i>Download File
                                            </a>
                                        @else
                                            <span class="text-muted">No file</span>
                                        @endif
                                    </td>
                                    <td>{{ $doc->date_of_expiry ? date('M d, Y', strtotime($doc->date_of_expiry)) : 'N/A' }}</td>
                                    <td>{{ $doc->notification_email ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('employee-documents.destroy', $doc->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove this document?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No uploaded documents found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 3: Emergency Contacts Sub-Resource -->
    <div class="tab-pane fade" id="contacts-pane" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Emergency Contacts & Next of Kin</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContactModal">
                    <i class="fa-solid fa-user-plus me-1"></i>Add Emergency Contact
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Contact Name</th>
                                <th>Relation</th>
                                <th>Mobile Phone</th>
                                <th>Primary / Dependent</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->employeeContacts as $contact)
                                <tr>
                                    <td class="fw-semibold text-body-emphasis">{{ $contact->contact_name }}</td>
                                    <td><span class="badge badge-light-primary">{{ $contact->relation }}</span></td>
                                    <td>{{ $contact->mobile_phone }}</td>
                                    <td>
                                        @if($contact->is_primary)<span class="badge badge-light-success">Primary Contact</span>@endif
                                        @if($contact->is_dependent)<span class="badge badge-light-info">Dependent</span>@endif
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('employee-contacts.destroy', $contact->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove contact?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No emergency contacts registered.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 4: Bank Accounts Sub-Resource -->
    <div class="tab-pane fade" id="bank-pane" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Direct Deposit & Bank Accounts</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBankModal">
                    <i class="fa-solid fa-building-columns me-1"></i>Add Bank Account
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Account Title</th>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>Branch / Code</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->employeeBankaccounts as $bank)
                                <tr>
                                    <td class="fw-semibold text-body-emphasis">{{ $bank->account_title }}</td>
                                    <td>{{ $bank->bank_name }}</td>
                                    <td><code class="text-primary fw-bold">{{ $bank->account_number }}</code></td>
                                    <td>{{ $bank->bank_branch ?? 'N/A' }} ({{ $bank->bank_code ?? 'N/A' }})</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('employee-bankaccounts.destroy', $bank->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove bank account?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No bank accounts registered.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 5: Qualifications Sub-Resource -->
    <div class="tab-pane fade" id="qualifications-pane" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Education & Qualifications</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addQualificationModal">
                    <i class="fa-solid fa-graduation-cap me-1"></i>Add Qualification
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Degree / Title</th>
                                <th>Specialization</th>
                                <th>Year Range</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->employeeQualifications as $qual)
                                <tr>
                                    <td class="fw-semibold text-body-emphasis">{{ $qual->name }}</td>
                                    <td>{{ $qual->specialization ?? 'General' }}</td>
                                    <td>{{ $qual->from_year ?? 'N/A' }} - {{ $qual->to_year ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('employee-qualifications.destroy', $qual->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove qualification?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No qualifications recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 6: Work Experiences Sub-Resource -->
    <div class="tab-pane fade" id="experiences-pane" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Prior Work Experience History</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                    <i class="fa-solid fa-briefcase me-1"></i>Add Experience
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Post / Designation</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->employeeWorkExperiences as $exp)
                                <tr>
                                    <td class="fw-semibold text-body-emphasis">{{ $exp->company_name }}</td>
                                    <td>{{ $exp->post }}</td>
                                    <td>{{ date('M d, Y', strtotime($exp->from_date)) }}</td>
                                    <td>{{ $exp->to_date ? date('M d, Y', strtotime($exp->to_date)) : 'Present' }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('employee-experiences.destroy', $exp->getKey()) }}" class="d-inline" onsubmit="return confirm('Remove experience?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No prior work experience history recorded.</td></tr>
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
                <div class="modal-header"><h5 class="modal-title">Upload Employee Document</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1">Document Title *</label><input type="text" name="title" class="form-control" required placeholder="e.g. Passport Copy"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Expiry Date</label><input type="date" name="date_of_expiry" class="form-control"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Alert Email</label><input type="email" name="notification_email" class="form-control" value="{{ $employee->email }}"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Select File (PDF, Image, Doc) *</label><input type="file" name="document_file" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Upload Document</button></div>
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
                <div class="modal-header"><h5 class="modal-title">Add Emergency Contact</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1">Contact Name *</label><input type="text" name="contact_name" class="form-control" required placeholder="Full Name"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Relation *</label><input type="text" name="relation" class="form-control" required placeholder="e.g. Spouse / Parent / Sibling"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Mobile Phone *</label><input type="text" name="mobile_phone" class="form-control" required placeholder="+1 (555) 000-0000"></div>
                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_primary" value="1" id="primaryCheck"><label class="form-check-label" for="primaryCheck">Set as Primary Emergency Contact</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Contact</button></div>
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
                <div class="modal-header"><h5 class="modal-title">Add Direct Deposit Bank Account</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1">Account Title *</label><input type="text" name="account_title" class="form-control" required value="{{ $employee->first_name }} {{ $employee->last_name }}"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Bank Name *</label><input type="text" name="bank_name" class="form-control" required placeholder="e.g. Chase / HSBC"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Account Number *</label><input type="text" name="account_number" class="form-control" required placeholder="Account Number"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Bank Branch / IFSC Code</label><input type="text" name="bank_branch" class="form-control" placeholder="Branch Name or Swift Code"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Bank Account</button></div>
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
                <div class="modal-header"><h5 class="modal-title">Add Qualification</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1">Degree / Qualification Name *</label><input type="text" name="name" class="form-control" required placeholder="e.g. B.Sc. Computer Science"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Specialization</label><input type="text" name="specialization" class="form-control" placeholder="e.g. Software Engineering"></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="label-sm mb-1">From Year</label><input type="text" name="from_year" class="form-control" placeholder="2018"></div>
                        <div class="col-6"><label class="label-sm mb-1">To Year</label><input type="text" name="to_year" class="form-control" placeholder="2022"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Qualification</button></div>
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
                <div class="modal-header"><h5 class="modal-title">Add Work Experience</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="label-sm mb-1">Company Name *</label><input type="text" name="company_name" class="form-control" required placeholder="Previous Employer"></div>
                    <div class="mb-3"><label class="label-sm mb-1">Post / Job Title *</label><input type="text" name="post" class="form-control" required placeholder="e.g. Senior Developer"></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="label-sm mb-1">From Date *</label><input type="date" name="from_date" class="form-control" required></div>
                        <div class="col-6"><label class="label-sm mb-1">To Date</label><input type="date" name="to_date" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Experience</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
