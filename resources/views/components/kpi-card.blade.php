@props([
    'title' => 'Total Metric',
    'value' => '0',
    'icon' => 'fa-solid fa-chart-bar',
    'variant' => 'primary',
    'badgeText' => null,
    'badgeTrend' => 'up',
])

<div class="card bg-base-100 border border-base-300 shadow-sm rounded-box p-4 h-full transition hover:shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-base-content/70 block">{{ $title }}</span>
            <div class="text-2xl font-bold text-base-content tabular-nums my-1">{{ $value }}</div>
            @if($badgeText)
                <span class="badge badge-soft badge-{{ $variant }} text-xs font-bold gap-1 mt-1">
                    <i class="fa-solid fa-arrow-{{ $badgeTrend === 'up' ? 'up' : 'down' }}"></i>
                    <span>{{ $badgeText }}</span>
                </span>
            @endif
        </div>
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-{{ $variant }}/10 text-{{ $variant }} text-xl shrink-0">
            <i class="{{ $icon }}"></i>
        </div>
    </div>
</div>
