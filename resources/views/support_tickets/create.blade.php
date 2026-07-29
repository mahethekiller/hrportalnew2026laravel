@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-headset me-2 text-primary"></i> Open Support Ticket</h4>
        <p class="text-muted fs-8 mb-0">Submit a support request to company departments.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="{{ route('support-tickets.index') }}" class="btn btn-light btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Helpdesk
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 col-lg-8">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('support-tickets.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label fs-8 fw-semibold">Subject / Issue Title <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control form-control-sm" required placeholder="e.g. Printer is not responding / Salary slip error" value="{{ old('subject') }}">
                    @error('subject')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-8 fw-semibold">Target Department <span class="text-danger">*</span></label>
                    <select name="department_id" class="form-select form-select-sm" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
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
                    <textarea name="description" rows="6" class="form-control form-control-sm" required placeholder="Please describe the issue or inquiry in detail...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fs-8 fw-semibold">Attachment File (Optional)</label>
                    <input type="file" name="attachment" class="form-control form-control-sm">
                    <div class="form-text fs-9 text-muted">Upload screenshot or document (Max 5MB).</div>
                    @error('attachment')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('support-tickets.index') }}" class="btn btn-light btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm fw-bold">Submit Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
