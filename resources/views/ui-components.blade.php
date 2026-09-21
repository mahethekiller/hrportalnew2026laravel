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

<!-- SECTION 4: DAISYUI 5 & EXECUTIVE BADGE SUITE -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">4. daisyUI 5 & Executive Badge Suite (WCAG AAA)</h3>
        <span class="badge badge-soft badge-primary">daisyUI 5 Component System</span>
    </div>
    <div class="card-body">
        <!-- 4.1 Soft Badges (Executive Wash Pair Rule) -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Soft Badges (daisyUI 5 <code>badge-soft</code>)</h5>
        <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
            <span class="badge badge-soft badge-primary"><i class="fa-solid fa-circle-dot me-1"></i>Primary (Navy)</span>
            <span class="badge badge-soft badge-secondary"><i class="fa-solid fa-shield me-1"></i>Secondary (Slate)</span>
            <span class="badge badge-soft badge-success"><i class="fa-solid fa-circle-check me-1"></i>Success (Emerald)</span>
            <span class="badge badge-soft badge-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>Warning (Amber)</span>
            <span class="badge badge-soft badge-error"><i class="fa-solid fa-circle-xmark me-1"></i>Error / Danger (Crimson)</span>
            <span class="badge badge-soft badge-info"><i class="fa-solid fa-circle-info me-1"></i>Info (Sky)</span>
            <span class="badge badge-soft badge-accent"><i class="fa-solid fa-sparkles me-1"></i>Accent (Teal)</span>
            <span class="badge badge-soft badge-neutral"><i class="fa-solid fa-cube me-1"></i>Neutral (Charcoal)</span>
            <span class="badge badge-soft badge-purple"><i class="fa-solid fa-crown me-1"></i>Royal Violet (Executive)</span>
        </div>

        <!-- 4.2 Solid Badges -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Solid Badges (daisyUI 5 <code>badge-{variant}</code>)</h5>
        <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
            <span class="badge badge-primary">Primary</span>
            <span class="badge badge-secondary">Secondary</span>
            <span class="badge badge-success">Success</span>
            <span class="badge badge-warning">Warning</span>
            <span class="badge badge-error">Error</span>
            <span class="badge badge-info">Info</span>
            <span class="badge badge-accent">Accent</span>
            <span class="badge badge-neutral">Neutral</span>
            <span class="badge badge-purple">Purple</span>
        </div>

        <!-- 4.3 Outline & Dashed Badges -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Outline & Dashed Badges (<code>badge-outline</code> & <code>badge-dash</code>)</h5>
        <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
            <span class="badge badge-outline badge-primary">Outline Primary</span>
            <span class="badge badge-outline badge-success">Outline Success</span>
            <span class="badge badge-outline badge-warning">Outline Warning</span>
            <span class="badge badge-outline badge-error">Outline Error</span>
            <span class="badge badge-outline badge-info">Outline Info</span>
            <span class="badge badge-dash badge-primary">Dashed Primary</span>
            <span class="badge badge-dash badge-success">Dashed Success</span>
            <span class="badge badge-dash badge-warning">Dashed Warning</span>
            <span class="badge badge-dash badge-error">Dashed Error</span>
        </div>

        <!-- 4.4 Badge Size Hierarchy -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Badge Sizes (xs, sm, md, lg, xl)</h5>
        <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
            <span class="badge badge-soft badge-primary badge-xs">Extra Small (.badge-xs)</span>
            <span class="badge badge-soft badge-success badge-sm">Small (.badge-sm)</span>
            <span class="badge badge-soft badge-info badge-md">Medium Default (.badge-md)</span>
            <span class="badge badge-soft badge-warning badge-lg">Large (.badge-lg)</span>
            <span class="badge badge-soft badge-error badge-xl">Extra Large (.badge-xl)</span>
        </div>

        <!-- 4.5 Status Badge Blade Component with Dynamic Pulse -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Reusable Status Badge Component (<code>&lt;x-status-badge /&gt;</code>)</h5>
        <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
            <x-status-badge status="Active" :pulse="true" />
            <x-status-badge status="Pending" :pulse="true" />
            <x-status-badge status="Terminated" />
            <x-status-badge status="Shortlisted" />
            <x-status-badge status="Resigned" />
            <x-status-badge status="Abscond" />
            <x-status-badge status="Training" />
            <x-status-badge status="1" label="Numeric Active (1)" :pulse="true" />
            <x-status-badge status="2" label="Numeric Terminated (2)" />
            <x-status-badge variant="danger" label="Normalized Danger" />
        </div>

        <!-- 4.6 Legacy Bridge Verification -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Legacy Bridge Compatibility (<code>badge-light-*</code> & <code>bg-*-subtle</code>)</h5>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge badge-light-primary">badge-light-primary</span>
            <span class="badge badge-light-success">badge-light-success</span>
            <span class="badge badge-light-warning">badge-light-warning</span>
            <span class="badge badge-light-danger">badge-light-danger</span>
            <span class="badge badge-light-info">badge-light-info</span>
            <span class="badge badge-light-secondary">badge-light-secondary</span>
            <span class="badge badge-light-purple">badge-light-purple</span>
            <span class="badge bg-primary-subtle text-primary">bg-primary-subtle</span>
            <span class="badge bg-success-subtle text-success">bg-success-subtle</span>
            <span class="badge bg-warning-subtle text-warning">bg-warning-subtle</span>
            <span class="badge bg-danger-subtle text-danger">bg-danger-subtle</span>
        </div>
    </div>
</div>

<!-- SECTION 5: DAISYUI 5 MODALS, FORM CONTROLS & SEARCHABLE SELECTS -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">5. daisyUI 5 Modals, Form Controls & Searchable Selects</h3>
        <span class="badge badge-soft badge-success">WCAG AAA Form Architecture</span>
    </div>
    <div class="card-body">
        <!-- 5.1 Modal Triggers -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Modal Dialog Triggers (Rule 11 & daisyUI <code>&lt;dialog&gt;</code>)</h5>
        <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('demoFormModal').showModal()">
                <i class="fa-solid fa-window-restore me-1"></i> Open daisyUI Form Modal (<code class="text-white">&lt;x-form-modal&gt;</code>)
            </button>
            <button type="button" class="btn btn-ghost border border-base-300 btn-sm" onclick="document.getElementById('demoStudioModal').showModal()">
                <i class="fa-solid fa-columns me-1"></i> Open 2-Column Widescreen Studio Modal
            </button>
        </div>

        <!-- 5.2 Form Inputs Grid Showcase -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Form Control System (High-Contrast in Light & Dark Mode)</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <x-input-label for="demo_name" value="Employee Full Name" />
                <x-text-input id="demo_name" placeholder="e.g. Rahul Sharma" value="Rahul Sharma" />
            </div>
            <div class="col-md-4">
                <x-input-label for="demo_dept" value="Department (with Company Disambiguation)" />
                <select id="demo_dept" class="form-select select select-bordered w-full">
                    <option value="">Select Department...</option>
                    <option value="1" selected>Engineering (Company: Antigravity Corp)</option>
                    <option value="2">Human Resources (Company: Antigravity Corp)</option>
                    <option value="3">Operations (Company: Global Tech)</option>
                </select>
            </div>
            <div class="col-md-4">
                <x-input-label for="demo_role" value="Searchable Select2 Dropdown" />
                <select id="demo_role" class="form-select select-search" data-control="select2">
                    <option value=""></option>
                    <option value="lead" selected>Senior Technical Lead</option>
                    <option value="architect">Principal Architect</option>
                    <option value="manager">Engineering Manager</option>
                </select>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <x-input-label for="demo_notes" value="Comments & Description (Sanitized Content)" />
                <textarea id="demo_notes" rows="2" class="form-control textarea textarea-bordered w-full" placeholder="Type comments or justification here...">Approved under standard annual policy quota.</textarea>
            </div>
            <div class="col-md-6">
                <x-input-label value="Multi-Select Pills (High-Contrast Inversion in Dark Mode)" />
                <select id="demo_multi_select" class="form-select select-search" data-control="select2" multiple>
                    <option value="1" selected>Rahul Sharma (EMP-101)</option>
                    <option value="2" selected>Alexander Pierce (EMP-102)</option>
                    <option value="3">Sarah Connor (EMP-103)</option>
                </select>
            </div>
        </div>

        <!-- 5.3 Checkboxes, Radios & Submit Buttons -->
        <h5 class="fw-semibold text-body-emphasis small mb-2">Checkboxes, Radios & Button States (Rule 12 Loader)</h5>
        <div class="d-flex flex-wrap gap-4 align-items-center mb-3">
            <div class="form-check d-flex items-center gap-2">
                <input class="form-check-input checkbox checkbox-primary rounded" type="checkbox" id="checkActive" checked>
                <label class="form-check-label small" for="checkActive">Send Email Confirmation</label>
            </div>
            <div class="form-check d-flex items-center gap-2">
                <input class="form-check-input checkbox checkbox-primary rounded" type="checkbox" id="checkNotify">
                <label class="form-check-label small" for="checkNotify">Notify Department Head</label>
            </div>
            <div class="form-check d-flex items-center gap-2">
                <input class="form-check-input radio radio-primary" type="radio" name="demoRadio" id="radio1" checked>
                <label class="form-check-label small" for="radio1">Full Day</label>
            </div>
            <div class="form-check d-flex items-center gap-2">
                <input class="form-check-input radio radio-primary" type="radio" name="demoRadio" id="radio2">
                <label class="form-check-label small" for="radio2">Half Day</label>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <x-primary-button>
                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes (<code class="text-white text-xs">&lt;x-primary-button&gt;</code>)
            </x-primary-button>
            <x-secondary-button>
                Cancel (<code class="text-xs">&lt;x-secondary-button&gt;</code>)
            </x-secondary-button>
            <x-danger-button>
                <i class="fa-solid fa-trash me-1"></i> Delete Record (<code class="text-white text-xs">&lt;x-danger-button&gt;</code>)
            </x-danger-button>
        </div>
    </div>
</div>

<!-- COMPONENT MODAL 1: Standard Form Modal -->
<x-form-modal id="demoFormModal" title="Edit Employee Profile Record" submitText="Save Employee" submitVariant="primary" size="md">
    <div class="space-y-4">
        <div>
            <x-input-label for="modal_emp_name" value="Full Name" />
            <x-text-input id="modal_emp_name" name="name" value="Sarah Connor" placeholder="Enter full name" />
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <x-input-label for="modal_emp_email" value="Official Email" />
                <x-text-input id="modal_emp_email" name="email" type="email" value="s.connor@antigravity.io" />
            </div>
            <div>
                <x-input-label for="modal_emp_phone" value="Phone Number" />
                <x-text-input id="modal_emp_phone" name="phone" value="+1 (555) 019-2834" />
            </div>
        </div>
        <div>
            <x-input-label for="modal_emp_dept" value="Designation" />
            <select id="modal_emp_dept" class="form-select select select-bordered w-full">
                <option value="1" selected>Lead Architect (Company: Antigravity Corp)</option>
                <option value="2">Senior System Analyst</option>
            </select>
        </div>
        <div class="form-check d-flex items-center gap-2 pt-1">
            <input class="form-check-input checkbox checkbox-primary rounded" type="checkbox" id="modal_emp_active" checked>
            <label class="form-check-label text-sm" for="modal_emp_active">Employee Account is Active</label>
        </div>
    </div>
</x-form-modal>

<!-- COMPONENT MODAL 2: 2-Column Side-by-Side Widescreen Studio Modal (Rule 11) -->
<x-form-modal id="demoStudioModal" title="Email Notification Template Studio" submitText="Publish Template" submitVariant="primary" size="studio">
    <div class="grid grid-cols-12 gap-5">
        <!-- Left: Form Parameters (col-span-5) -->
        <div class="col-span-5 space-y-3.5 border-r border-base-300 pr-5">
            <div>
                <x-input-label for="studio_tpl_name" value="Template Name" />
                <x-text-input id="studio_tpl_name" value="Leave Request Approved" />
            </div>
            <div>
                <x-input-label for="studio_tpl_subject" value="Email Subject Line" />
                <x-text-input id="studio_tpl_subject" value="Your leave application has been approved" />
            </div>
            <div>
                <x-input-label value="Available Dynamic Placeholders" />
                <div class="flex flex-wrap gap-1.5 mt-1">
                    <span class="badge badge-soft badge-primary font-mono text-[11px]">{employee_name}</span>
                    <span class="badge badge-soft badge-info font-mono text-[11px]">{leave_type}</span>
                    <span class="badge badge-soft badge-success font-mono text-[11px]">{start_date}</span>
                    <span class="badge badge-soft badge-warning font-mono text-[11px]">{end_date}</span>
                </div>
            </div>
        </div>

        <!-- Right: Studio Preview Canvas (col-span-7) -->
        <div class="col-span-7 space-y-2">
            <x-input-label value="Live Email Preview Studio Canvas" />
            <div class="wysiwyg-canvas p-4 rounded-lg bg-base-200/50 border border-base-300 font-sans text-sm space-y-2">
                <div class="font-bold text-base text-primary">Antigravity HR Portal</div>
                <p>Hello <strong>{employee_name}</strong>,</p>
                <p>We are pleased to inform you that your request for <strong>{leave_type}</strong> from <strong>{start_date}</strong> to <strong>{end_date}</strong> has been officially approved by your manager.</p>
                <div class="pt-2">
                    <span class="badge badge-soft badge-success">Status: Approved</span>
                </div>
            </div>
        </div>
    </div>
</x-form-modal>
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
