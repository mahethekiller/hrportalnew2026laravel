---
name: building-maintainable-components
description: Enforces code maintainability and component reusability across Blade views, forms, metric cards, data tables, modal dialogs, and badges. Provides patterns for extracting duplicate HTML into reusable Blade components, maintaining 100% Bootstrap 5.3 and Dark/Light Mode compliance, and reducing code churn. Use when building reusable Blade components, refactoring repetitive view logic, or standardizing UI component architecture.
---

# Building Maintainable & Reusable Components

Expert instructions for standardizing UI component architecture, extracting repetitive HTML into reusable Blade components, and enforcing 100% Bootstrap 5.3 & Dark/Light Mode maintainability.

---

## 1. Core Principles of Reusability

To maintain a clean, scalable, and low-maintenance codebase:
1. **Rule 5 Compliance (Zero Duplicate HTML/Logic)**: If a visual element (e.g. Metric Card, Status Badge, Modal, Form Input, Table Header) appears in 2 or more views, extract it into a Blade Component (`resources/views/components/`).
2. **Rule 2 & Theme Compliance (Bootstrap 5.3 + Dark Mode)**: Every component must use Bootstrap 5.3 semantic utility tokens (`bg-body-tertiary`, `text-body-emphasis`, `border-subtle`, `bg-primary-subtle`) to ensure 100% dark mode (`data-bs-theme="dark"`) responsiveness without hardcoded light backgrounds or dark text colors.
3. **Decoupled Props & Sensible Defaults**: Components must accept `@props` with sensible defaults so callers only pass required overrides.

---

## 2. Reusable Blade Component Catalog

### A. Metric Summary Tile Component (`components/kpi-card.blade.php`)
```html
@props([
    'title' => 'Total Metric',
    'value' => '0',
    'icon' => 'fa-solid fa-chart-bar',
    'variant' => 'primary',
    'badgeText' => null,
    'badgeTrend' => 'up',
])

<div class="card border-0 shadow-sm rounded-3 p-3 bg-body-tertiary dashboard-card h-100">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <span class="text-body-secondary fs-9 fw-semibold d-block text-uppercase tracking-wider">{{ $title }}</span>
            <h3 class="fw-bolder text-body-emphasis mb-0 mt-1">{{ $value }}</h3>
            @if($badgeText)
                <span class="badge bg-{{ $variant }}-subtle text-{{ $variant }} fw-bold fs-9 mt-1">
                    <i class="fa-solid fa-arrow-{{ $badgeTrend === 'up' ? 'up' : 'down' }} me-1"></i>{{ $badgeText }}
                </span>
            @endif
        </div>
        <div class="avatar-md rounded-circle bg-{{ $variant }}-subtle text-{{ $variant }} d-flex align-items-center justify-content-center p-3" style="width: 52px; height: 52px;">
            <i class="{{ $icon }} fs-4"></i>
        </div>
    </div>
</div>
```

### B. Status Badge Component (`components/status-badge.blade.php`)
```html
@props([
    'status' => 'Active',
    'variant' => null,
    'pulse' => false,
])

@php
    $resolvedVariant = $variant ?? match(strtolower((string)$status)) {
        'active', 'approved', 'paid', 'completed', 'published', '1' => 'success',
        'pending', 'under review', 'in progress', '0' => 'warning',
        'rejected', 'cancelled', 'inactive', 'terminated', '2' => 'danger',
        default => 'secondary'
    };
@endphp

<span class="badge bg-{{ $resolvedVariant }}-subtle text-{{ $resolvedVariant }} fw-bold fs-9 px-2 py-1 align-items-center gap-1 d-inline-flex">
    @if($pulse)
        <span class="badge-pulse-dot bg-{{ $resolvedVariant }}"></span>
    @endif
    {{ ucfirst((string)$status) }}
</span>
```

### C. Reusable Form Modal Dialog (`components/form-modal.blade.php`)
```html
@props([
    'id',
    'title' => 'Form Modal',
    'action',
    'method' => 'POST',
    'submitText' => 'Save Changes',
    'submitVariant' => 'primary',
    'size' => 'md',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-{{ $size }}">
        <div class="modal-content border-0 shadow-lg bg-body-tertiary">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
                    @method($method)
                @endif
                
                <div class="modal-header border-bottom border-subtle">
                    <h5 class="modal-title fw-bold text-body-emphasis fs-6">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{ $slot }}
                </div>
                
                <div class="modal-footer border-top border-subtle bg-body">
                    <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-{{ $submitVariant }} btn-sm fw-bold shadow-sm">{{ $submitText }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

### D. Reusable Data Table Wrapper (`components/data-table.blade.php`)
```html
@props([
    'headers' => [],
    'isEmpty' => false,
    'emptyTitle' => 'No Records Found',
    'emptyDescription' => 'There are no entries available in this table.',
    'emptyIcon' => 'fa-solid fa-folder-open',
])

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0 fs-8">
        <thead class="bg-body-secondary">
            <tr>
                @foreach($headers as $header)
                    <th class="{{ $loop->first ? 'ps-4' : '' }} text-body-secondary fs-9 text-uppercase tracking-wider">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if($isEmpty)
                <tr>
                    <td colspan="{{ count($headers) }}" class="p-0">
                        <x-empty-state 
                            :icon="$emptyIcon" 
                            :title="$emptyTitle" 
                            :description="$emptyDescription" 
                        />
                    </td>
                </tr>
            @else
                {{ $slot }}
            @endif
        </tbody>
    </table>
</div>
```

---

## 3. Refactoring & Component Extraction Checklist

When reviewing or building Blade templates:
- [ ] Are cards using `<x-kpi-card>` or manual HTML?
- [ ] Are status badges using `<x-status-badge>` for unified color mapping?
- [ ] Are empty table rows using `<x-empty-state>` for consistent UX?
- [ ] Are modal forms using `<x-form-modal>` to avoid boilerplate setup?
- [ ] Are all components 100% Dark Mode (`data-bs-theme="dark"`) tested?
- [ ] Are actions wrapped in Spatie permission gates (`@can('permission.name')`)?
