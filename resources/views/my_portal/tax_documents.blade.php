@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-file-arrow-up me-2 text-danger"></i> Income & Tax Document Uploads</h4>
        <p class="text-muted fs-8 mb-0">Upload tax saving investment proofs, 80C declarations, HRA receipts, and Form 16.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#taxDocModal">
            <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Tax Proof
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show fs-8" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header border-0 pt-3 bg-white">
        <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-folder-open me-2 text-warning"></i> Uploaded Tax Documents</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Document Title</th>
                        <th>Type / Category</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th>Uploaded Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($docs as $dc)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">{{ $dc->title }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $dc->document_type }}</span></td>
                            <td>{{ $dc->file_size }}</td>
                            <td><span class="badge bg-soft-info text-info">{{ $dc->status }}</span></td>
                            <td>{{ $dc->added_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No tax documents uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="taxDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('my-portal.tax_documents.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-gray-900">Upload Income Tax Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Document Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" required placeholder="e.g. Life Insurance Policy 80C Receipt">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Document Category <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select form-select-sm" required>
                            <option value="Section 80C Investment Proof">Section 80C Investment Proof</option>
                            <option value="HRA Rent Receipt">HRA Rent Receipts</option>
                            <option value="Medical Insurance (80D)">Medical Insurance (80D)</option>
                            <option value="Form 16 / Previous Employer Tax">Form 16 / Income Declaration</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Document File (PDF / Image / Zip) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control form-control-sm" required accept=".pdf,.jpg,.png,.zip">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Description / Notes (Optional)</label>
                        <textarea name="description" rows="2" class="form-control form-control-sm" placeholder="Policy number, provider name, or financial year notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Upload Proof</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
