@extends('layouts.app')

@section('title', 'Instructors & Trainers Directory')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Instructors & Trainers Directory</h1>
            <p class="text-muted fs-7 mb-0">Register internal subject matter experts and external training vendors.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('training-sessions.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-graduation-cap me-1"></i> Training Sessions
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTrainerModal">
                <i class="fa-solid fa-user-plus me-1"></i> Register New Trainer
            </button>
        </div>
    </div>

    <!-- Trainers Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Instructor Name</th>
                            <th>Email Address</th>
                            <th>Contact Phone</th>
                            <th>Expertise Domain</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trainers as $tr)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900"><i class="fa-solid fa-user-tie me-2 text-primary"></i>{{ $tr->full_name }}</div>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $tr->email }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $tr->contact_number ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-info fs-8 fw-semibold">{{ $tr->expertise ?? 'General Technical' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $tr->status_badge_class }}">
                                        {{ ucfirst($tr->status ?? 'Active') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-chalkboard-user fs-2 mb-2 d-block text-muted"></i>
                                    No instructors or trainers registered yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Register New Trainer -->
<div class="modal fade" id="createTrainerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('trainers.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-user-plus me-2 text-primary"></i> Register Instructor / Trainer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control form-control-sm" required placeholder="First Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control form-control-sm" required placeholder="Last Name">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" required placeholder="trainer@example.com">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Contact Phone</label>
                            <input type="text" name="contact_number" class="form-control form-control-sm" placeholder="+91 9876543210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold">Expertise Domain</label>
                            <input type="text" name="expertise" class="form-control form-control-sm" placeholder="e.g. Cloud Security / PHP & Laravel">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Register Instructor</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
