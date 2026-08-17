@props([
    'icon' => 'fa-solid fa-folder-open',
    'title' => 'No Records Found',
    'description' => 'There are no items to display at the moment.',
    'actionUrl' => null,
    'actionText' => null,
    'actionPermission' => null,
])

<div class="text-center py-5 px-3">
    <div class="avatar-lg rounded-circle bg-body-tertiary d-inline-flex align-items-center justify-content-center mb-3 shadow-sm p-3" style="width: 70px; height: 70px;">
        <i class="{{ $icon }} fs-2 text-body-secondary"></i>
    </div>
    <h6 class="fw-bold text-body-emphasis mb-1">{{ $title }}</h6>
    <p class="text-body-secondary fs-8 mb-3 max-w-md mx-auto" style="max-width: 400px;">{{ $description }}</p>
    
    @if($actionUrl && $actionText)
        @if(!$actionPermission || auth()->user()->can($actionPermission))
            <a href="{{ $actionUrl }}" class="btn btn-primary btn-sm fw-bold shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> {{ $actionText }}
            </a>
        @endif
    @endif
</div>
