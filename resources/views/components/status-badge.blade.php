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
