@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-ticket-simple me-2 text-primary"></i> Open Admin Support Ticket</h4>
        <p class="text-muted fs-8 mb-0">Submit a support request to administration.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="{{ route('admin-tickets.index') }}" class="btn btn-light btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 col-lg-8">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin-tickets.store') }}">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label fs-8 fw-semibold">Subject / Inquiry Title <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control form-control-sm" required placeholder="e.g. IT Equipment Request / Office Space issue" value="{{ old('subject') }}">
                    @error('subject')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-8 fw-semibold">Target Company <span class="text-danger">*</span></label>
                    <select name="company_id" class="form-select form-select-sm" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-8 fw-semibold">Ticket Priority <span class="text-danger">*</span></label>
                    <select name="ticket_priority" class="form-select form-select-sm" required>
                        <option value="low" {{ old('ticket_priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('ticket_priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('ticket_priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('ticket_priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                    @error('ticket_priority')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fs-8 fw-semibold">Description / Message Detail <span class="text-danger">*</span></label>
                    <textarea name="description" rows="6" class="form-control form-control-sm" required placeholder="Describe your question or issue in detail...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin-tickets.index') }}" class="btn btn-light btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm fw-bold">Submit Admin Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
