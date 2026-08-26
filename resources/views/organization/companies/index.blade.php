@extends('layouts.app')

@section('title', 'Companies Management')
@section('page_title', 'Company Entities & Subsidiaries')

@section('content')
<!-- Page Header Banner -->
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Company Entities</h2>
        <p class="text-body-secondary small mb-0">Manage corporate entities, legal registration IDs, tax specs, and logos.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
            <i class="fa-solid fa-plus me-1"></i>Add Company Entity
        </button>
    </div>
</div>

<!-- Companies Data Table -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Company Directory</h3>
        <span class="badge badge-light-primary">{{ $companies->total() }} Entities</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="min-width: 80px;">Actions</th>
                        <th>Company Name</th>
                        <th>Registration No</th>
                        <th>Email Contact</th>
                        <th>Phone</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $comp)
                        <tr>
                            <td class="ps-4">
                                <form method="POST" action="{{ route('companies.destroy', $comp->id) }}" class="d-inline" onsubmit="return confirm('Delete this company entity?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light-danger btn-sm" title="Delete Entity"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($comp->logo && file_exists(public_path('uploads/logo/' . $comp->logo)))
                                        <img src="{{ asset('uploads/logo/' . $comp->logo) }}" alt="{{ $comp->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: contain;">
                                    @else
                                        <div class="btn btn-light-primary btn-sm rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-body-emphasis">{{ $comp->name }}</div>
                                        <div class="small text-body-secondary">{{ $comp->trading_name ?? $comp->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="text-primary fw-bold">{{ $comp->registration_no ?? 'N/A' }}</code></td>
                            <td>{{ $comp->email }}</td>
                            <td>{{ $comp->contact_number ?? 'N/A' }}</td>
                            <td class="pe-4">
                                @if($comp->is_active ?? true)
                                    <span class="badge badge-light-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                                @else
                                    <span class="badge badge-light-secondary">Disabled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No company entities registered.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($companies->hasPages())
        <div class="card-footer py-3">
            {{ $companies->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal: Create Company Entity -->
<div class="modal fade" id="createCompanyModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Register Company Entity</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="label-sm mb-1">Company Legal Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Antigravity Global Ltd">
                    </div>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Registration No</label>
                        <input type="text" name="registration_no" class="form-control" placeholder="e.g. REG-2026-99">
                    </div>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Corporate Email *</label>
                        <input type="email" name="email" class="form-control" required placeholder="contact@antigravity.io">
                    </div>
                    <div class="mb-3">
                        <label class="label-sm mb-1">Company Logo</label>
                        <input type="file" name="logo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Company Entity</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
