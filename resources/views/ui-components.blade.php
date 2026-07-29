@extends('layouts.app')

@section('title', 'UI Component Library')
@section('page_title', 'Design System & Component Library')

@section('content')
<!-- Page Header -->
<div class="row mb-3 align-items-center">
    <div class="col-md-8">
        <h2 class="headline-lg text-body-emphasis mb-1">UI Component Library</h2>
        <p class="text-body-secondary small mb-0">High-density Executive Precision x Metronic 8 component reference kit.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <span class="badge badge-light-primary me-1"><i class="fa-solid fa-code me-1"></i>Bootstrap 5.3</span>
        <span class="badge badge-light-success me-1"><i class="fa-solid fa-chart-line me-1"></i>ApexCharts</span>
        <span class="badge badge-light-warning"><i class="fa-solid fa-wand-magic-sparkles me-1"></i>Executive</span>
    </div>
</div>

<!-- System HR Announcement Banner -->
<div class="announcement-banner mb-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <div class="btn btn-light-primary btn-sm rounded-circle p-2">
            <i class="fa-solid fa-bullhorn fs-5"></i>
        </div>
        <div>
            <div class="fw-bold text-body-emphasis">📢 Announcement Banner Component</div>
            <div class="small text-body-secondary">Use this banner for system-wide notices, policy updates, or broadcast notifications.</div>
        </div>
    </div>
    <button class="btn btn-light-primary btn-sm">Action Link</button>
</div>

<!-- SECTION 1: QUICK ACTION TILES & BUTTONS -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">1. Quick Action Tiles & Buttons</h3>
        <span class="label-sm">Actions</span>
    </div>
    <div class="card-body">
        <h5 class="fw-semibold text-body-emphasis small mb-2">Quick Action Navigation Tiles</h5>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-tile">
                    <i class="fa-solid fa-calendar-plus text-primary fs-4 mb-2 d-block"></i>
                    <div class="fw-semibold small">Apply Leave</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-tile">
                    <i class="fa-solid fa-receipt text-success fs-4 mb-2 d-block"></i>
                    <div class="fw-semibold small">Submit Claim</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-tile">
                    <i class="fa-solid fa-file-invoice-dollar text-info fs-4 mb-2 d-block"></i>
                    <div class="fw-semibold small">View Payslip</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-tile">
                    <i class="fa-solid fa-sitemap text-warning fs-4 mb-2 d-block"></i>
                    <div class="fw-semibold small">Org Directory</div>
                </a>
            </div>
        </div>

        <h5 class="fw-semibold text-body-emphasis small mb-2">Solid & Light Buttons</h5>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button class="btn btn-primary"><i class="fa-solid fa-check me-1"></i>Primary</button>
            <button class="btn btn-light-primary"><i class="fa-solid fa-plus me-1"></i>Light Primary</button>
            <button class="btn btn-light-success"><i class="fa-solid fa-download me-1"></i>Light Success</button>
            <button class="btn btn-light-danger"><i class="fa-solid fa-trash me-1"></i>Light Danger</button>
            <button class="btn btn-light-warning"><i class="fa-solid fa-bell me-1"></i>Light Warning</button>
        </div>
    </div>
</div>

<!-- SECTION 2: FILE UPLOAD DROPZONE & FORMS -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">2. Drag & Drop File Box & Advanced Forms</h3>
        <span class="label-sm">Upload & Forms</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="label-sm mb-2">Dropzone Document Upload Box</label>
                <div class="dropzone-box">
                    <i class="fa-solid fa-cloud-arrow-up fs-2 text-primary mb-2 d-block"></i>
                    <div class="fw-bold text-body-emphasis small">Click or Drag PDF/Doc files here</div>
                    <div class="text-body-secondary fs-7">Supports PDF, DOCX, PNG (Max size: 10MB)</div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="label-sm mb-2">Task Checklist Widget</label>
                <div class="d-flex flex-column gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="task1" checked>
                        <label class="form-check-label text-body-emphasis text-decoration-line-through small" for="task1">Review 3 Probation Evaluation Forms</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="task2" checked>
                        <label class="form-check-label text-body-emphasis text-decoration-line-through small" for="task2">Approve July Salary Payroll Batch</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="task3">
                        <label class="form-check-label text-body-emphasis small" for="task3">Schedule Onboarding Orientation for Alexander</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: GRAPH VISUALIZATIONS & GAUGE -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">3. Graphs & Radial Gauge Chart</h3>
        <span class="badge badge-light-primary">ApexCharts</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <div id="uiShowcaseAreaChart" style="min-height: 250px;"></div>
            </div>
            <div class="col-md-4">
                <div id="uiShowcaseGaugeChart" style="min-height: 250px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Area Chart
        const areaOptions = {
            series: [{ name: 'Attendance Rate %', data: [85, 90, 88, 94, 92, 96, 98] }],
            chart: { height: 250, type: 'area', toolbar: { show: false } },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#1B84FF'],
            xaxis: { categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"] }
        };
        new ApexCharts(document.querySelector("#uiShowcaseAreaChart"), areaOptions).render();

        // Radial Gauge Chart
        const gaugeOptions = {
            series: [88],
            chart: { height: 250, type: 'radialBar' },
            plotOptions: {
                radialBar: {
                    hollow: { size: '65%' },
                    dataLabels: { name: { show: false }, value: { fontSize: '22px', fontWeight: 700, offsetY: 6 } }
                }
            },
            colors: ['#17C653']
        };
        new ApexCharts(document.querySelector("#uiShowcaseGaugeChart"), gaugeOptions).render();
    });
</script>
@endpush
