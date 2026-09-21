@props([
    'status' => 'Active',
    'variant' => null,
    'label' => null,
    'pulse' => false,
    'size' => null, // xs, sm, md, lg
    'outline' => false,
])

@php
    $rawStatus = (string)$status;
    $statusMap = [
        '1' => ['label' => 'Active', 'variant' => 'success'],
        '2' => ['label' => 'Terminated', 'variant' => 'error'],
        '3' => ['label' => 'Left', 'variant' => 'warning'],
        '4' => ['label' => 'Abscond', 'variant' => 'neutral'],
        '5' => ['label' => 'Disabled', 'variant' => 'secondary'],
        '0' => ['label' => 'Resigned', 'variant' => 'info'],
    ];

    if (isset($statusMap[$rawStatus])) {
        $displayLabel = $label ?? $statusMap[$rawStatus]['label'];
        $resolvedVariant = $variant ?? $statusMap[$rawStatus]['variant'];
    } else {
        $displayLabel = $label ?? ucfirst($rawStatus);
        $resolvedVariant = $variant ?? match(strtolower($rawStatus)) {
            'active', 'approved', 'paid', 'completed', 'published', 'hired', 'offered', 'present', 'clocked_in', 'success' => 'success',
            'pending', 'under review', 'in progress', 'interview scheduled', 'interview_scheduled', 'probation', 'waiting', 'warning' => 'warning',
            'shortlisted', 'scheduled', 'converted', 'review' => 'info',
            'applied', 'new', 'open' => 'primary',
            'rejected', 'cancelled', 'inactive', 'terminated', 'danger', 'absent', 'failed', 'unpaid' => 'error',
            'resigned', 'on leave', 'half day' => 'info',
            'abscond' => 'neutral',
            'disabled', 'disable', 'draft', 'closed', 'secondary' => 'secondary',
            'training', 'certified', 'special', 'promoted' => 'purple',
            default => 'secondary'
        };
    }

    // Sanitize any legacy prefixes (e.g. 'badge-light-danger' -> 'error')
    $resolvedVariant = str_replace(['badge-light-', 'badge-soft-', 'badge-', 'light-', 'bg-'], '', (string)$resolvedVariant);
    if ($resolvedVariant === 'danger') {
        $resolvedVariant = 'error';
    }

    $sizeClass = match($size) {
        'xs' => 'badge-xs',
        'sm' => 'badge-sm',
        'lg' => 'badge-lg',
        'xl' => 'badge-xl',
        default => ''
    };

    $styleClass = $outline ? 'badge-outline' : 'badge-soft';
@endphp

<span class="badge {{ $styleClass }} badge-{{ $resolvedVariant }} {{ $sizeClass }} font-semibold text-xs px-2.5 py-1 inline-flex items-center gap-1.5 rounded">
    @if($pulse)
        <span class="inline-block w-1.5 h-1.5 rounded-full bg-current opacity-80 animate-pulse"></span>
    @endif
    {{ $displayLabel }}
</span>

