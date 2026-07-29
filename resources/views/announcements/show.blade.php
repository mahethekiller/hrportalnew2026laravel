@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-bullhorn me-2 text-primary"></i> {{ $announcement->title }}</h4>
        <p class="text-muted fs-8 mb-0">Published by {{ $announcement->published_by }} on {{ $announcement->created_at }}</p>
    </div>
    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
        <a href="{{ route('announcements.index') }}" class="btn btn-light btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Announcements
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            @if(!empty($announcement->image))
                <img src="{{ asset($announcement->image) }}" class="w-100" style="max-height: 350px; object-fit: cover;" alt="{{ $announcement->title }}">
            @endif

            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $typeBadge = match(strtolower($announcement->announcement_type)) {
                                'urgent' => 'bg-danger text-white',
                                'event' => 'bg-info text-dark',
                                'policy' => 'bg-warning text-dark',
                                default => 'bg-primary text-white'
                            };
                        @endphp
                        <span class="badge {{ $typeBadge }} text-capitalize px-3 py-2 fs-8">{{ $announcement->announcement_type }}</span>
                        <span class="badge bg-light text-dark border fs-8">
                            <i class="fa-solid fa-building me-1"></i>{{ $announcement->company ? $announcement->company->name : 'All Companies' }}
                        </span>
                        @if($announcement->department)
                            <span class="badge bg-light text-dark border fs-8">
                                <i class="fa-solid fa-sitemap me-1"></i>{{ $announcement->department->department_name }}
                            </span>
                        @endif
                    </div>
                    <div class="text-muted fs-8">
                        <i class="fa-regular fa-calendar-days me-1 text-primary"></i> Active: <strong>{{ $announcement->start_date }}</strong> to <strong>{{ $announcement->end_date }}</strong>
                    </div>
                </div>

                <div class="alert alert-light border-start border-primary border-3 p-3 mb-4 rounded-2">
                    <h6 class="fw-bold text-gray-900 mb-1"><i class="fa-solid fa-circle-info text-primary me-1"></i> Executive Summary</h6>
                    <p class="text-muted fs-8 mb-0">{{ $announcement->summary }}</p>
                </div>

                <div class="fs-7 leading-relaxed text-gray-800 whitespace-pre-line">
                    {!! nl2br(e($announcement->description)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
