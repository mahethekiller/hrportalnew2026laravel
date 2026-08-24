@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-user-plus me-2 text-primary"></i> Refer a Friend / Candidate</h4>
        <p class="text-muted fs-8 mb-0">Refer talented candidates for open job positions and earn referral bonuses.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#referralModal">
            <i class="fa-solid fa-plus me-1"></i> Submit Referral
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header border-0 pt-3 bg-white">
        <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-list me-2 text-primary"></i> My Submitted Referrals</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Candidate Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $ref)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">{{ $ref->clean_subject ?: $ref->name }}</td>
                            <td>{{ $ref->email }}</td>
                            <td>{{ $ref->contact_number ?? $ref->contact_no ?? '--' }}</td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $ref->status ?? 'Pending' }}</span></td>
                            <td>{{ $ref->created_at ?? $ref->added_date ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">You haven't submitted any candidate referrals yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="referralModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('my-portal.referrals.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-gray-900">Submit Candidate Referral</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Target Job Requisition <span class="text-danger">*</span></label>
                        <select name="job_id" class="form-select form-select-sm" required>
                            @foreach($openJobs as $job)
                                <option value="{{ $job->job_id }}">{{ $job->job_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Candidate Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. John Smith">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Candidate Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" required placeholder="john@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_number" class="form-control form-control-sm" required placeholder="+1 555-0199">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Resume / CV Attachment (Optional)</label>
                        <input type="file" name="resume" class="form-control form-control-sm" accept=".pdf,.doc,.docx">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Submit Referral</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
