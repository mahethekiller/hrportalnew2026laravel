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
