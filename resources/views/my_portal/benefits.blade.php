@extends('layouts.app')

@section('title', 'Corporate Benefits & Policies')

@section('content')
@php
    $canManagePolicies = auth()->check() && (
        auth()->user()->can('edit.employees') ||
        auth()->user()->user_role_id == 1 ||
        in_array(strtolower(auth()->user()->roleRelation->role_name ?? ''), ['administrator', 'super admin', 'hr'])
    );
    $empCompanyId = $empCompanyId ?? 1;
@endphp

<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-regular fa-building me-1"></i> {{ $currentCompanyName }}
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Governance & People Operations</span>
                @if($isSuperAdmin ?? false)
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5 rounded-pill fs-9 fw-bold ms-1">
                        <i class="fa-solid fa-shield me-1"></i> Super-Admin Master View
                    </span>
                @endif
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Corporate Benefits & Policy Handbooks</h1>
            <p class="text-body-secondary fs-7 mb-0">Browse official company policy handbooks, group health insurance plans, and statutory compliance guidelines.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($isSuperAdmin ?? false)
                <!-- Super Admin Filter By Company Switcher -->
                <div class="d-flex align-items-center gap-1.5 me-2">
                    <label class="fs-9 fw-semibold text-body-secondary mb-0 text-nowrap">Filter Company:</label>
                    <select class="form-select form-select-sm select-search bg-body" style="min-width: 220px;" onchange="window.location.href = (this.value && this.value !== 'all') ? '{{ route('my-portal.benefits') }}?company_id=' + this.value : '{{ route('my-portal.benefits') }}'">
                        <option value="all" {{ !request('company_id') || request('company_id') === 'all' ? 'selected' : '' }}>All Companies (Master View)</option>
                        @foreach($companies ?? [] as $comp)
                            <option value="{{ $comp->company_id }}" {{ request('company_id') == $comp->company_id ? 'selected' : '' }}>
                                {{ $comp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($canManagePolicies)
                <button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#uploadPolicyModal">
                    <i class="fa-solid fa-file-circle-plus me-1.5"></i> Upload Policy Document
                </button>
            @endif
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

    <!-- Metric KPI Cards (Airy & Elevated: DENSITY: 3, MOTION: 4, VARIANCE: 6) -->
    <div class="row g-3 mb-4">
        <!-- Total Documents Available -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Active Documents</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-folder-open fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-emphasis mb-1">{{ $stats['total'] ?? $documents->count() }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Policy files in your corporate scope</p>
                </div>
            </div>
        </div>

        <!-- Handbooks & Codes of Conduct -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Handbooks & Conduct</span>
                        <div class="stat-icon-wrapper rounded-2 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-book-bookmark fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-info mb-1">{{ $stats['handbooks'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Employment handbooks & code rules</p>
                </div>
            </div>
        </div>

        <!-- Health & Insurance -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Health & Insurance</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-shield-heart fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-success mb-1">{{ $stats['insurance'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Medical insurance & clinic tie-ups</p>
                </div>
            </div>
        </div>

        <!-- Latest Governance Update -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Last Updated</span>
                        <div class="stat-icon-wrapper rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-clock-rotate-left fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h4 fw-bold text-warning mb-1 text-truncate">{{ $stats['latest'] ?? 'Active' }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Most recent policy revision</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Corporate Policy Documents Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body mb-4">
        <!-- Card Header with Search & Filter Tabs -->
        <div class="card-header bg-transparent border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-shield text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis">Policy Documents Repository</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 fs-9 fw-normal ms-1">
                        {{ $documents->count() }} Available
                    </span>
                </div>

                <!-- Table Quick Category Filters -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="btn-group btn-group-sm" role="group" id="policyCategoryTabs">
                        <button type="button" class="btn btn-outline-secondary active fw-semibold" data-doc-category="all">All</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-doc-category="handbook">Handbooks</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-doc-category="insurance">Health & Insurance</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-doc-category="leave">Holidays & Leave</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-doc-category="general">General</button>
                    </div>
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <span class="input-group-text bg-body border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass fs-9"></i></span>
                        <input type="text" id="policyTableSearch" class="form-control bg-body border-start-0 fs-8" placeholder="Search documents...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8" id="policyDocumentsTable">
                    <thead class="table-light border-bottom">
                        <tr>
                            <!-- Rule 8: First-Column Icon-Only Actions -->
                            <th class="ps-4" style="width: 110px;">Actions</th>
                            <th style="width: 280px;">Document Title</th>
                            <th style="width: 210px;">Applicable Entity</th>
                            <th style="width: 160px;">Category</th>
                            <th style="width: 120px;">File Size</th>
                            <th class="pe-4 text-end" style="width: 130px;">Published Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            @php
                                $docId = $doc->file_id ?? $doc->document_id ?? $doc->id;
                                $downloadUrl = Str::startsWith($doc->file_name, 'uploads/') ? asset($doc->file_name) : asset('uploads/documents/' . $doc->file_name);
                                $rawCompany = trim((string)($doc->company_id ?? ''));
                                $title = $doc->file_desc ?: basename($doc->file_name);
                                $category = $doc->file_type ?: 'Policy Handbook';
                                $ext = strtolower($doc->file_extension ?: pathinfo($doc->file_name, PATHINFO_EXTENSION) ?: 'pdf');

                                // Infer filter category
                                $lowerText = strtolower($title . ' ' . $category);
                                $filterCat = 'general';
                                if (str_contains($lowerText, 'handbook') || str_contains($lowerText, 'conduct') || str_contains($lowerText, 'coi')) {
                                    $filterCat = 'handbook';
                                } elseif (str_contains($lowerText, 'insurance') || str_contains($lowerText, 'health') || str_contains($lowerText, 'medical') || str_contains($lowerText, 'hospital')) {
                                    $filterCat = 'insurance';
                                } elseif (str_contains($lowerText, 'holiday') || str_contains($lowerText, 'leave')) {
                                    $filterCat = 'leave';
                                }
                            @endphp
                            <tr class="policy-doc-row transition-colors" data-doc-category="{{ $filterCat }}">
                                <!-- Rule 8: First-Column Icon-Only Actions with explicit 6px spacing -->
                                <td class="ps-4 text-nowrap">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- Detail Dossier Trigger -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDocModal{{ $docId }}" 
                                                title="View Document Details">
                                            <i class="fa-solid fa-eye fs-8"></i>
                                        </button>

                                        <!-- Download Button -->
                                        <a href="{{ $downloadUrl }}" 
                                           class="btn btn-sm btn-outline-success px-2.5 rounded-2" 
                                           target="_blank" 
                                           title="Download Document ({{ strtoupper($ext) }})">
                                            <i class="fa-solid fa-download fs-8"></i>
                                        </a>

                                        @if($canManagePolicies)
                                            <!-- Delete Button for HR / Admins -->
                                            <form method="POST" action="{{ route('my-portal.benefits.destroy', $docId) }}" onsubmit="return confirm('Are you sure you want to delete this policy document?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Policy Document">
                                                    <i class="fa-solid fa-trash-can fs-8"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                                <!-- Document Title with File Type Icon -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center {{ in_array($ext, ['pdf']) ? 'bg-danger-subtle text-danger' : (in_array($ext, ['doc', 'docx']) ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success') }}" style="width: 34px; height: 34px; min-width: 34px;">
                                            @if(in_array($ext, ['pdf']))
                                                <i class="fa-solid fa-file-pdf fs-7"></i>
                                            @elseif(in_array($ext, ['doc', 'docx']))
                                                <i class="fa-solid fa-file-word fs-7"></i>
                                            @elseif(in_array($ext, ['png', 'jpg', 'jpeg']))
                                                <i class="fa-solid fa-file-image fs-7"></i>
                                            @else
                                                <i class="fa-solid fa-file-lines fs-7"></i>
                                            @endif
                                        </div>
                                        <div class="overflow-hidden">
                                            <span class="fw-bold text-body-emphasis d-block text-truncate" title="{{ $title }}">{{ $title }}</span>
                                            <span class="fs-9 text-body-secondary text-uppercase">{{ $ext }} DOCUMENT</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Applicable Entity / Company -->
                                <td>
                                    @if(empty($rawCompany) || $rawCompany === '0')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fs-9 fw-medium">
                                            <i class="fa-solid fa-globe me-1"></i> All Companies
                                        </span>
                                    @else
                                        @php
                                            $cids = array_filter(explode(',', $rawCompany));
                                        @endphp
                                        @if(count($cids) <= 1)
                                            @php $singleId = reset($cids); @endphp
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                                <i class="fa-regular fa-building me-1"></i> {{ $companiesMap[$singleId] ?? 'Company #' . $singleId }}
                                            </span>
                                        @else
                                            @php
                                                $firstId = $cids[0];
                                                $allNames = array_map(fn($id) => $companiesMap[$id] ?? 'Company #'.$id, $cids);
                                            @endphp
                                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold" title="{{ implode(', ', $allNames) }}">
                                                <i class="fa-regular fa-building me-1"></i> {{ $companiesMap[$firstId] ?? 'Company #'.$firstId }}
                                                <span class="ms-1 opacity-75">+{{ count($cids) - 1 }} more</span>
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                <!-- Category -->
                                <td>
                                    <span class="badge bg-body-tertiary text-body-emphasis border rounded-pill px-2.5 py-1 fs-9 fw-medium">
                                        {{ $category }}
                                    </span>
                                </td>

                                <!-- File Size -->
                                <td>
                                    <span class="font-monospace fs-9 text-body-secondary">
                                        {{ $doc->file_size ?: '--' }}
                                    </span>
                                </td>

                                <!-- Published Date -->
                                <td class="pe-4 text-end">
                                    <span class="text-body-secondary fs-9">
                                        <x-human-date :value="$doc->created_at ?? $doc->added_date" />
                                    </span>
                                </td>
                            </tr>

                            <!-- Document Dossier Detail Modal -->
                            <div class="modal fade" id="viewDocModal{{ $docId }}" tabindex="-1" aria-labelledby="viewDocModalLabel{{ $docId }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content border-0 shadow-lg bg-body">
                                        <div class="modal-header border-bottom py-3 px-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-2 p-1.5 {{ in_array($ext, ['pdf']) ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }}">
                                                    <i class="fa-solid fa-file-lines fs-7"></i>
                                                </div>
                                                <h5 class="modal-title fw-bold text-body-emphasis fs-7 mb-0">Policy Dossier</h5>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <span class="fs-9 text-body-secondary fw-semibold text-uppercase d-block mb-1">Document Title</span>
                                                <h6 class="fw-bold text-body-emphasis mb-0">{{ $title }}</h6>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Category</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">{{ $category }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">File Size</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis font-monospace">{{ $doc->file_size ?: 'Standard' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Target Scope</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">
                                                            @if(empty($rawCompany) || $rawCompany === '0')
                                                                Organization-Wide
                                                            @else
                                                                {{ count($cids) }} Company {{ count($cids) > 1 ? 'Entities' : 'Entity' }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2.5 rounded-2 bg-body-tertiary border">
                                                        <span class="fs-9 text-body-secondary d-block">Uploaded Date</span>
                                                        <span class="fs-8 fw-semibold text-body-emphasis">
                                                            <x-human-date :value="$doc->created_at ?? $doc->added_date" />
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 rounded-2 bg-body-tertiary border mb-3">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <span class="fs-9 text-body-secondary fw-medium">Applicable Companies</span>
                                                    <i class="fa-solid fa-building text-primary fs-9"></i>
                                                </div>
                                                <p class="fs-8 text-body-emphasis mb-0">
                                                    @if(empty($rawCompany) || $rawCompany === '0')
                                                        All group entities and branch offices.
                                                    @else
                                                        {{ implode(', ', array_map(fn($id) => $companiesMap[$id] ?? 'Company #'.$id, $cids)) }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between pt-2">
                                                <span class="fs-9 text-body-secondary">Published by {{ $doc->added_by ?: 'HR Administration' }}</span>
                                                <a href="{{ $downloadUrl }}" target="_blank" class="btn btn-primary btn-sm fw-semibold px-3">
                                                    <i class="fa-solid fa-download me-1.5"></i> Download File
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                        <div class="rounded-circle bg-body-tertiary d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                            <i class="fa-solid fa-folder-open fs-3 text-body-tertiary"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No policy documents found</h6>
                                        <p class="fs-8 text-body-secondary mb-0">There are no corporate policies published for your company entity at this moment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<!-- Modal: Upload Corporate Policy Document (Rules 4, 9, 10, 11, 12) -->
<div class="modal fade @if(isset($errors) && $errors->any()) show d-block @endif" id="uploadPolicyModal" tabindex="-1" aria-labelledby="uploadPolicyModalLabel" aria-hidden="true" @if(isset($errors) && $errors->any()) style="background: rgba(0,0,0,0.5);" @endif>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content border-0 shadow-lg bg-body" method="POST" action="{{ route('my-portal.benefits.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-2 bg-primary-subtle text-primary p-2">
                        <i class="fa-solid fa-file-circle-plus fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-body-emphasis fs-6 mb-0">Upload Corporate Policy Document</h5>
                        <span class="fs-9 text-body-secondary">Publish guidelines, benefit handbooks, or statutory forms</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                        <ul class="mb-0 ps-3 fs-8">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Document Title / Description <span class="text-danger">*</span></label>
                    <input type="text" name="file_desc" value="{{ old('file_desc') }}" class="form-control fs-8 bg-body @error('file_desc') is-invalid @enderror" placeholder="e.g. Employee Handbook 2026 - ROI Mantra" required>
                    @error('file_desc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <!-- Rule 9: Searchable Select for Company -->
                    <div class="col-md-6">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Applicable Corporate Entity <span class="text-danger">*</span></label>
                        <select name="company_id" class="form-select form-select-sm select-search bg-body" data-control="select2" required>
                            @if($isSuperAdmin ?? false)
                                <option value="0" {{ old('company_id', '0') == '0' ? 'selected' : '' }}>All Companies (Organization-Wide)</option>
                                @foreach($companies ?? [] as $comp)
                                    <option value="{{ $comp->company_id }}" {{ old('company_id') == $comp->company_id ? 'selected' : '' }}>
                                        {{ $comp->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="{{ $empCompanyId }}" selected>
                                    {{ $currentCompanyName }} (Your Company)
                                </option>
                                <option value="0" {{ old('company_id') == '0' ? 'selected' : '' }}>All Companies (Organization-Wide)</option>
                            @endif
                        </select>
                        <span class="fs-9 text-body-secondary mt-1 d-block">
                            @if($isSuperAdmin ?? false)
                                Select specific entity or choose All Companies for group-wide policies.
                            @else
                                Document will be published under {{ $currentCompanyName }}.
                            @endif
                        </span>
                    </div>

                    <!-- Category / Document Type -->
                    <div class="col-md-6">
                        <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Document Category / Classification <span class="text-danger">*</span></label>
                        <select name="file_type" class="form-select form-select-sm select-search bg-body" data-control="select2" required>
                            <option value="Policy Handbook" {{ old('file_type') == 'Policy Handbook' ? 'selected' : '' }}>Policy Handbook</option>
                            <option value="Health Insurance" {{ old('file_type') == 'Health Insurance' ? 'selected' : '' }}>Health & Medical Insurance</option>
                            <option value="Code of Conduct" {{ old('file_type') == 'Code of Conduct' ? 'selected' : '' }}>Code of Conduct & Ethics</option>
                            <option value="Leave & Holiday Policy" {{ old('file_type') == 'Leave & Holiday Policy' ? 'selected' : '' }}>Leave & Holiday Calendar</option>
                            <option value="Standard Operating Procedure (SOP)" {{ old('file_type') == 'Standard Operating Procedure (SOP)' ? 'selected' : '' }}>Standard Operating Procedure (SOP)</option>
                            <option value="Compliance & Legal" {{ old('file_type') == 'Compliance & Legal' ? 'selected' : '' }}>Compliance & Statutory Legal</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Upload File (PDF, DOCX, DOC, PNG, JPG) <span class="text-danger">*</span></label>
                    <input type="file" name="document" class="form-control fs-8 bg-body @error('document') is-invalid @enderror" required accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                    <span class="fs-9 text-body-secondary mt-1 d-block">Maximum upload size: 10 MB. Human-readable naming enforced automatically on storage.</span>
                    @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner State -->
            <div class="modal-footer border-top py-2.5 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                    <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> Publish Document
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('policyTableSearch');
    const tabButtons = document.querySelectorAll('#policyCategoryTabs button');
    const tableRows = document.querySelectorAll('#policyDocumentsTable tbody tr.policy-doc-row');

    let currentCategory = 'all';

    function filterTable() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

        tableRows.forEach(row => {
            const rowCat = row.getAttribute('data-doc-category') || 'general';
            const textContent = row.textContent.toLowerCase();

            const matchesCategory = (currentCategory === 'all' || rowCat === currentCategory);
            const matchesSearch = !query || textContent.includes(query);

            if (matchesCategory && matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-doc-category');
            filterTable();
        });
    });
});
</script>
@endpush

@endsection
