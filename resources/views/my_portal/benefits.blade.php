@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-shield-heart me-2 text-success"></i> Corporate Benefits & Policy Handbooks</h4>
        <p class="text-body-secondary fs-8 mb-0">Explore health insurance coverage, wellness perks, and download policy handbooks.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header border-0 pt-3 bg-body">
        <h5 class="fw-bold text-body-emphasis fs-6 mb-0"><i class="fa-solid fa-file-pdf me-2 text-danger"></i> Corporate Policy Handbooks & Guides</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-body-tertiary">
                    <tr>
                        <th class="ps-4">Document Title</th>
                        <th>Category</th>
                        <th>File Size</th>
                        <th class="text-end pe-4">Download</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr>
                            <td class="ps-4 fw-bold text-body-emphasis">{{ $doc->file_desc ?? $doc->file_name }}</td>
                            <td><span class="badge bg-body-secondary text-body-emphasis border">{{ $doc->file_type ?? 'Policy' }}</span></td>
                            <td>{{ $doc->file_size ?? 'N/A' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ Str::startsWith($doc->file_name, 'uploads/') ? asset($doc->file_name) : asset('uploads/documents/' . $doc->file_name) }}" class="btn btn-light-primary btn-sm py-1 px-3 fs-9 fw-bold" target="_blank">
                                    <i class="fa-solid fa-download me-1"></i> Download
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-body-secondary">No policy documents available at this time.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
