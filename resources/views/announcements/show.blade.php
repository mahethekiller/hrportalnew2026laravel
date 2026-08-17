@extends('layouts.app')

@section('content')
@php
    $rawDescription = $announcement->description;
    if (str_contains($rawDescription, '&lt;') || str_contains($rawDescription, '&gt;')) {
        $rawDescription = html_entity_decode($rawDescription);
    }

    $rawSummary = $announcement->summary;
    if (str_contains($rawSummary, '&lt;') || str_contains($rawSummary, '&gt;')) {
        $rawSummary = html_entity_decode($rawSummary);
    }

    $hasHtml = $rawDescription !== strip_tags($rawDescription);
@endphp

<div class="row mb-4 align-items-center">
    <div class="col-sm-8">
        <h4 class="mb-0 text-body-emphasis fw-bold"><i class="fa-solid fa-bullhorn me-2 text-primary"></i> {{ $announcement->title }}</h4>
        <p class="text-body-secondary fs-8 mb-0">Published on {{ $announcement->created_at ?? 'N/A' }}</p>
    </div>
    <div class="col-sm-4 text-sm-end mt-2 mt-sm-0">
        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Announcements
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4 bg-body-tertiary">
            @if(!empty($announcement->image))
                <img src="{{ asset($announcement->image) }}" class="w-100" style="max-height: 350px; object-fit: cover;" alt="{{ $announcement->title }}">
            @endif

            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom border-subtle">
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $typeBadge = match(strtolower($announcement->announcement_type)) {
                                'urgent' => 'bg-danger-subtle text-danger',
                                'event' => 'bg-info-subtle text-info',
                                'policy' => 'bg-warning-subtle text-warning',
                                default => 'bg-primary-subtle text-primary'
                            };
                        @endphp
                        <span class="badge {{ $typeBadge }} text-capitalize px-3 py-2 fs-8 fw-bold">{{ $announcement->announcement_type }}</span>
                        <span class="badge bg-body-secondary text-body-emphasis border border-subtle fs-8">
                            <i class="fa-solid fa-building me-1"></i>{{ $announcement->company ? $announcement->company->name : 'All Companies' }}
                        </span>
                        @if($announcement->department)
                            <span class="badge bg-body-secondary text-body-emphasis border border-subtle fs-8">
                                <i class="fa-solid fa-sitemap me-1"></i>{{ $announcement->department->department_name }}
                            </span>
                        @endif
                    </div>
                    <div class="text-body-secondary fs-8">
                        <i class="fa-regular fa-calendar-days me-1 text-primary"></i> Active: <strong>{{ $announcement->start_date }}</strong> to <strong>{{ $announcement->end_date }}</strong>
                    </div>
                </div>

                @if(!empty($rawSummary))
                <div class="alert alert-primary bg-primary-subtle border-start border-primary border-3 p-3 mb-4 rounded-2">
                    <h6 class="fw-bold text-primary mb-1"><i class="fa-solid fa-circle-info me-1"></i> Executive Summary</h6>
                    <p class="text-body-emphasis fs-8 mb-0">{!! strip_tags($rawSummary) !!}</p>
                </div>
                @endif

                <div class="fs-7 leading-relaxed text-body-emphasis">
                    @if($hasHtml)
                        {!! $rawDescription !!}
                    @else
                        {!! nl2br(e($rawDescription)) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
