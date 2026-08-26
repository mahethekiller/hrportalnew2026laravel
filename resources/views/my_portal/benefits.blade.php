@extends('layouts.app')

@section('content')
@php
    $canManagePolicies = auth()->check() && (
        auth()->user()->can('edit.employees') ||
        auth()->user()->user_role_id == 1 ||
        in_array(strtolower(auth()->user()->roleRelation->role_name ?? ''), ['administrator', 'super admin', 'hr'])
    );
@endphp
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900"><i class="fa-solid fa-shield-heart me-2 text-success"></i> Corporate Benefits & Policy Handbooks</h1>
            <p class="text-muted fs-7 mb-0">Explore health insurance coverage, wellness perks, and download policy handbooks.</p>
        </div>
        @if($canManagePolicies)
            <div>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadPolicyModal">
                    <i class="fa-solid fa-file-circle-plus me-1"></i> Upload Policy Document
                </button>
            </div>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Policy Handbooks Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header border-0 pt-4 bg-body d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-file-pdf me-2 text-danger"></i> Corporate Policy Handbooks & Guides</h5>
            <span class="badge bg-light-primary text-primary fw-bold fs-8 px-3 py-2 rounded-pill">{{ count($documents) }} Published Documents</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4">Actions</th>
                            <th>Document Title</th>
                            <th>Category</th>
                            <th>File Size</th>
                            <th>Uploaded On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            @php
                                $docId = $doc->file_id ?? $doc->document_id ?? $doc->id;
                                $downloadUrl = Str::startsWith($doc->file_name, 'uploads/') ? asset($doc->file_name) : asset('uploads/documents/' . $doc->file_name);
                            @endphp
                            <tr>
                                <td class="ps-4 text-nowrap">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <!-- Download Button -->
                                        <a href="{{ $downloadUrl }}" class="btn btn-sm btn-outline-primary px-2.5 rounded-2" target="_blank" title="Download Document">
                                            <i class="fa-solid fa-download"></i>
                                        </a>

                                        @if($canManagePolicies)
                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('my-portal.benefits.destroy', $docId) }}" onsubmit="return confirm('Are you sure you want to delete this policy document?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-2" title="Delete Policy Document">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                <td class="fw-bold text-body-emphasis">{{ $doc->file_desc ?? $doc->file_name }}</td>
                                <td><span class="badge bg-body-secondary text-body-emphasis border">{{ $doc->file_type ?? 'Policy Handbook' }}</span></td>
                                <td>{{ $doc->file_size ?? 'N/A' }}</td>
                                <td class="text-muted">{{ $doc->added_date ? \Carbon\Carbon::parse($doc->added_date)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-file-pdf fs-1 text-muted mb-3 d-block opacity-50"></i>
                                    No corporate policy documents available at this time.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Upload Policy Document -->
<div class="modal fade @if($errors->any()) show d-block @endif" id="uploadPolicyModal" tabindex="-1" aria-hidden="true" @if($errors->any()) style="background: rgba(0,0,0,0.5);" @endif>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" method="POST" action="{{ route('my-portal.benefits.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-900"><i class="fa-solid fa-file-circle-plus text-primary me-2"></i> Upload Corporate Policy Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold">Document Title / Description <span class="text-danger">*</span></label>
                    <input type="text" name="file_desc" value="{{ old('file_desc') }}" class="form-control form-control-sm @error('file_desc') is-invalid @enderror" placeholder="e.g. Employee Health Insurance Policy 2026" required>
                    @error('file_desc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold">Document Category / Type</label>
                    <select name="file_type" class="form-select form-select-sm select-search" data-control="select2" data-placeholder="Select Document Type...">
                        <option value="Policy Handbook" {{ old('file_type') == 'Policy Handbook' ? 'selected' : '' }}>Policy Handbook</option>
                        <option value="Health Insurance" {{ old('file_type') == 'Health Insurance' ? 'selected' : '' }}>Health Insurance</option>
                        <option value="Code of Conduct" {{ old('file_type') == 'Code of Conduct' ? 'selected' : '' }}>Code of Conduct</option>
                        <option value="Leave & Holiday Policy" {{ old('file_type') == 'Leave & Holiday Policy' ? 'selected' : '' }}>Leave & Holiday Policy</option>
                        <option value="Standard Operating Procedure (SOP)" {{ old('file_type') == 'Standard Operating Procedure (SOP)' ? 'selected' : '' }}>Standard Operating Procedure (SOP)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fs-8 fw-semibold">Upload Document File (PDF, DOCX, PNG, JPG) <span class="text-danger">*</span></label>
                    <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" required accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                    <div class="form-text fs-9 text-muted">Max file size: 10MB. Allowed formats: PDF, DOCX, PNG, JPG.</div>
                    @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm px-4" onclick="submitWithLoader(this)">
                    <i class="fa-solid fa-upload me-1"></i> Upload Document
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
