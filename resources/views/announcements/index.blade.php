@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-bullhorn me-2 text-primary"></i> Company Announcements</h4>
        <p class="text-muted fs-8 mb-0">Stay informed with executive broadcasts, policy updates, and corporate events.</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        @can('edit.announcements')
            <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm fw-bold">
                <i class="fa-solid fa-plus me-1"></i> Post Announcement
            </a>
        @endcan
    </div>
</div>


<!-- Filters -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('announcements.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4 col-6">
                <select name="type" class="form-select form-select-sm fs-8" onchange="this.form.submit()">
                    <option value="">All Announcement Types</option>
                    <option value="General" {{ request('type') === 'General' ? 'selected' : '' }}>General Broadcast</option>
                    <option value="Event" {{ request('type') === 'Event' ? 'selected' : '' }}>Corporate Event</option>
                    <option value="Policy" {{ request('type') === 'Policy' ? 'selected' : '' }}>Policy Update</option>
                    <option value="Urgent" {{ request('type') === 'Urgent' ? 'selected' : '' }}>Urgent Alert</option>
                </select>
            </div>
            <div class="col-md-4 col-6">
                <select name="company_id" class="form-select form-select-sm fs-8" onchange="this.form.submit()">
                    <option value="">All Companies</option>
                    @foreach($companies as $comp)
                        <option value="{{ $comp->company_id }}" {{ request('company_id') == $comp->company_id ? 'selected' : '' }}>{{ $comp->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Grid Cards -->
<div class="row g-4 mb-4">
    @forelse($announcements as $anc)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden d-flex flex-column">
                @if(!empty($anc->image))
                    <img src="{{ asset($anc->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $anc->title }}">
                @else
                    <div class="bg-gradient text-white p-4 d-flex align-items-center justify-content-center" style="height: 140px; background: linear-gradient(135deg, #1e293b, #3b82f6);">
                        <i class="fa-solid fa-bullhorn fa-3x opacity-50"></i>
                    </div>
                @endif
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        @php
                            $typeBadge = match(strtolower($anc->announcement_type)) {
                                'urgent' => 'bg-danger text-white',
                                'event' => 'bg-info text-dark',
                                'policy' => 'bg-warning text-dark',
                                default => 'bg-primary text-white'
                            };
                        @endphp
                        <span class="badge {{ $typeBadge }} text-capitalize px-2 py-1 fs-9">{{ $anc->announcement_type }}</span>
                        <span class="text-muted fs-9"><i class="fa-regular fa-clock me-1"></i>{{ $anc->start_date }}</span>
                    </div>

                    <h5 class="fw-bold text-gray-900 mb-2">{{ $anc->title }}</h5>
                    <p class="text-muted fs-8 leading-relaxed mb-4 flex-grow-1">{{ Str::limit($anc->summary, 110) }}</p>

                    <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-auto">
                        <span class="text-muted fs-9">
                            <i class="fa-solid fa-building me-1"></i>{{ $anc->company ? $anc->company->name : 'All Companies' }}
                        </span>
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route('announcements.show', $anc->announcement_id) }}" class="btn btn-light-primary btn-sm py-1 px-3 fs-9 fw-bold">
                                Read More
                            </a>
                            @can('edit.announcements')
                                <form method="POST" action="{{ route('announcements.destroy', $anc->announcement_id) }}" onsubmit="return confirm('Delete this announcement?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light-danger btn-sm py-1 px-2 fs-9"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="bg-light p-4 rounded-circle d-inline-block mb-3">
                <i class="fa-solid fa-bullhorn fs-1 text-muted"></i>
            </div>
            <h5 class="fw-bold text-gray-900">No Announcements Found</h5>
            <p class="text-muted fs-8">Check back later for company news and corporate updates.</p>
        </div>
    @endforelse
</div>

@if($announcements->hasPages())
    <div class="d-flex justify-content-center">
        {{ $announcements->links() }}
    </div>
@endif
@endsection
