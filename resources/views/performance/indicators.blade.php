@extends('layouts.app')

@section('title', 'Performance Indicators')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Designation Performance Indicators</h1>
            <p class="text-muted fs-7 mb-0">Set benchmark target ratings for different job designations.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('performance-appraisals.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Appraisals
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createIndicatorModal">
                <i class="fa-solid fa-plus me-1"></i> New Indicator Benchmark
            </button>
        </div>
    </div>

    <!-- Indicators Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <form method="GET" action="{{ route('performance-indicators.index') }}" class="row g-2 align-items-center w-100">
                <div class="col-md-8">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search designation title..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 text-end d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('performance-indicators.index') }}" class="btn btn-light-secondary btn-sm px-3"><i class="fa-solid fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">Designation</th>
                            <th>Target Work Quality</th>
                            <th>Target Efficiency</th>
                            <th>Target Teamwork</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indicators as $ind)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-gray-900 fs-7">{{ $ind->designation->designation_name ?? 'Designation' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-success font-monospace fs-8">{{ $ind->quality_of_work ?? 4 }}.0 / 5.0</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary font-monospace fs-8">{{ $ind->efficiency ?? 4 }}.0 / 5.0</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-info font-monospace fs-8">{{ $ind->team_work ?? 4 }}.0 / 5.0</span>
                                </td>
                                <td>
                                    <span class="text-gray-700 fs-8">{{ $ind->created_at ?? date('Y-m-d') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-bullseye fs-2 mb-2 d-block text-muted"></i>
                                    No performance benchmark indicators found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($indicators->hasPages())
            <div class="card-footer py-3 border-top">
                {{ $indicators->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: New Indicator Benchmark -->
<div class="modal fade" id="createIndicatorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('performance-indicators.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus me-2 text-primary"></i> Create Indicator Benchmark</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Select Designation <span class="text-danger">*</span></label>
                        <select name="designation_id" class="form-select form-select-sm" required>
                            <option value="">Choose Designation</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->designation_id }}">{{ $desig->designation_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Target Quality of Work (1-5) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="5" name="quality_of_work" class="form-control form-control-sm" required value="4">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 fw-semibold">Target Efficiency (1-5) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="5" name="efficiency" class="form-control form-control-sm" required value="4">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Indicator Benchmark</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
