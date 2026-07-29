@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-bullhorn me-2 text-primary"></i> Post New Announcement</h4>
        <p class="text-muted fs-8 mb-0">Broadcast corporate announcements and policy updates to employees.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="{{ route('announcements.index') }}" class="btn btn-light btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 col-lg-8">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label fs-8 fw-semibold">Announcement Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control form-control-sm" required placeholder="e.g. Q3 Town Hall & Annual Performance Briefing" value="{{ old('title') }}">
                    @error('title')
                        <div class="text-danger fs-9 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-8 fw-semibold">Announcement Type <span class="text-danger">*</span></label>
                    <select name="announcement_type" class="form-select form-select-sm" required>
                        <option value="General" {{ old('announcement_type') === 'General' ? 'selected' : '' }}>General Broadcast</option>
                        <option value="Event" {{ old('announcement_type') === 'Event' ? 'selected' : '' }}>Corporate Event</option>
                        <option value="Policy" {{ old('announcement_type') === 'Policy' ? 'selected' : '' }}>Policy Update</option>
                        <option value="Urgent" {{ old('announcement_type') === 'Urgent' ? 'selected' : '' }}>Urgent Alert</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-8 fw-semibold">Target Company <span class="text-danger">*</span></label>
                    <select name="company_id" class="form-select form-select-sm" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->company_id }}" {{ old('company_id') == $company->company_id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-8 fw-semibold">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control form-control-sm" required value="{{ old('start_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-8 fw-semibold">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control form-control-sm" required value="{{ old('end_date', date('Y-m-d', strtotime('+7 days'))) }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label fs-8 fw-semibold">Target Department (Optional)</label>
                    <select name="department_id" class="form-select form-select-sm">
                        <option value="0">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fs-8 fw-semibold">Summary / Teaser Text <span class="text-danger">*</span></label>
                    <textarea name="summary" rows="2" class="form-control form-control-sm" required placeholder="Brief 1-2 sentence overview for news card preview...">{{ old('summary') }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label fs-8 fw-semibold">Full Announcement Description <span class="text-danger">*</span></label>
                    <textarea name="description" rows="6" class="form-control form-control-sm" required placeholder="Full details, agenda, and guidelines...">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label fs-8 fw-semibold">Cover / Banner Image (Optional)</label>
                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('announcements.index') }}" class="btn btn-light btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm fw-bold">Publish Announcement</button>
            </div>
        </form>
    </div>
</div>
@endsection
