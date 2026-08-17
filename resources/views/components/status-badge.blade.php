@props([
    'status' => 'Active',
    'variant' => null,
    'label' => null,
    'pulse' => false,
])

@php
    $rawStatus = (string)$status;
    $statusMap = [
        '1' => ['label' => 'Active', 'variant' => 'success'],
        '2' => ['label' => 'Terminated', 'variant' => 'danger'],
        '3' => ['label' => 'Left', 'variant' => 'warning'],
        '4' => ['label' => 'Abscond', 'variant' => 'dark'],
        '5' => ['label' => 'Disabled', 'variant' => 'secondary'],
        '0' => ['label' => 'Resigned', 'variant' => 'info'],
    ];

    if (isset($statusMap[$rawStatus])) {
        $displayLabel = $label ?? $statusMap[$rawStatus]['label'];
        $resolvedVariant = $variant ?? $statusMap[$rawStatus]['variant'];
    } else {
        $displayLabel = $label ?? ucfirst($rawStatus);
        $resolvedVariant = $variant ?? match(strtolower($rawStatus)) {
            'active', 'approved', 'paid', 'completed', 'published' => 'success',
            'pending', 'under review', 'in progress' => 'warning',
            'rejected', 'cancelled', 'inactive', 'terminated' => 'danger',
            'resigned' => 'info',
            'abscond' => 'dark',
            'disabled', 'disable' => 'secondary',
            default => 'secondary'
        };
    }

    $badgeClass = match($resolvedVariant) {
        'dark' => 'bg-dark text-white',
        default => "bg-{$resolvedVariant}-subtle text-{$resolvedVariant}"
    };
@endphp

<span class="badge {{ $badgeClass }} fw-bold fs-9 px-2 py-1 align-items-center gap-1 d-inline-flex">
    @if($pulse)
        <span class="badge-pulse-dot bg-{{ $resolvedVariant === 'dark' ? 'light' : $resolvedVariant }}"></span>
    @endif
    {{ $displayLabel }}
</span>
