@props([
    'value' => null,
    'time' => false,
    'icon' => null,
    'fallback' => '--',
    'badge' => false,
    'badgeClass' => 'badge bg-body-secondary text-body-emphasis border',
])

@php
    $carbon = \App\Helpers\DateHelper::parse($value);
    $formatted = $carbon 
        ? ($time ? \App\Helpers\DateHelper::formatDateTime($carbon) : \App\Helpers\DateHelper::format($carbon)) 
        : $fallback;
    $tooltip = $carbon ? \App\Helpers\DateHelper::tooltip($carbon) : '';
@endphp

@if($carbon)
    @if($badge)
        <span class="{{ $badgeClass }} text-nowrap" @if($tooltip) title="{{ $tooltip }}" @endif {{ $attributes }}>
            @if($icon)
                <i class="{{ $icon }} me-1 opacity-75"></i>
            @endif
            {{ $formatted }}
        </span>
    @else
        <span class="text-nowrap" @if($tooltip) title="{{ $tooltip }}" @endif {{ $attributes }}>
            @if($icon)
                <i class="{{ $icon }} me-1 opacity-75 text-body-secondary"></i>
            @endif
            {{ $formatted }}
        </span>
    @endif
@else
    <span class="text-body-secondary" {{ $attributes }}>{{ $fallback }}</span>
@endif
