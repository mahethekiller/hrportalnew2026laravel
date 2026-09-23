@props([
    'id',
    'title' => 'Form Modal',
    'action' => '#',
    'method' => 'POST',
    'submitText' => 'Save Changes',
    'submitVariant' => 'primary',
    'size' => 'md',
    'multipart' => true,
    'autoReopen' => true,
])

@php
    $maxWidth = match($size) {
        'sm' => 'max-w-md',
        'lg' => 'max-w-3xl',
        'xl', 'studio' => 'max-w-5xl',
        'full' => 'max-w-7xl',
        default => 'max-w-xl',
    };

    $shouldReopen = false;
    if ($errors->any() || session('error')) {
        $submittedModal = old('_modal_id', session('_modal_id'));
        $shouldReopen = $submittedModal ? ($submittedModal === $id) : (bool)$autoReopen;
    }
@endphp

<dialog id="{{ $id }}" class="modal">
    <div class="modal-box {{ $maxWidth }} w-11/12 p-0 bg-base-100 border border-base-300 shadow-2xl rounded-box overflow-hidden">
        <form action="{{ $action }}" method="POST" @if($multipart) enctype="multipart/form-data" @endif>
            @csrf
            <input type="hidden" name="_modal_id" value="{{ $id }}">
            @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
                @method($method)
            @endif

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-base-300 bg-base-200/50">
                <h3 class="font-bold text-base text-base-content flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-{{ $submitVariant }}"></span>
                    {{ $title }}
                </h3>
                <button type="button" class="btn btn-sm btn-circle btn-ghost text-base-content/60 hover:text-base-content" onclick="document.getElementById('{{ $id }}').close()" aria-label="Close">✕</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 text-base-content max-h-[72vh] overflow-y-auto">
                {{-- In-Modal Error & Status Alerts (Rule 10 Compliance) --}}
                {{-- In-Modal Error & Status Alerts (Rule 10 Compliance) --}}
                @if(session('error'))
                    <div class="modal-session-alert mb-4 flex items-start gap-3">
                        <div class="modal-session-icon">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                        </div>
                        <div class="flex-grow min-w-0 pt-0.5">
                            <div class="modal-session-title">Action Failed</div>
                            <div class="modal-session-desc">{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="modal-session-success mb-4 flex items-start gap-3">
                        <div class="modal-session-success-icon">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                        </div>
                        <div class="flex-grow min-w-0 pt-0.5">
                            <div class="modal-session-success-title">Success</div>
                            <div class="modal-session-success-desc">{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="modal-validation-banner mb-4">
                        <div class="flex items-start gap-3">
                            <div class="modal-err-icon">
                                <i class="fa-solid fa-circle-exclamation text-sm"></i>
                            </div>
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="modal-err-title">
                                        Please check the form for errors:
                                    </span>
                                    <span class="modal-err-badge font-mono">
                                        {{ $errors->count() }} {{ Str::plural('issue', $errors->count()) }}
                                    </span>
                                </div>
                                <ul class="modal-err-list">
                                    @foreach($errors->all() as $err)
                                        <li class="modal-err-item">
                                            <i class="fa-solid fa-circle modal-err-dot"></i>
                                            <span class="modal-err-text">{{ $err }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </div>

            <!-- Modal Footer -->
            @if(isset($footer))
                {{ $footer }}
            @else
                <div class="flex items-center justify-end gap-2.5 px-6 py-3.5 border-t border-base-300 bg-base-200/30">
                    <button type="button" class="btn btn-sm btn-ghost font-medium" onclick="document.getElementById('{{ $id }}').close()">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-{{ $submitVariant }} font-semibold shadow-xs submit-loader" onclick="submitWithLoader(this)">
                        {{ $submitText }}
                    </button>
                </div>
            @endif
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

{{-- Scoped Styles for Modal Alerts & Banner (100% Light & Dark Mode Compatible) --}}
<style>
.modal-validation-banner {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 12px;
    padding: 13px 15px;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.05);
}
[data-bs-theme="dark"] .modal-validation-banner,
[data-theme="portal-dark"] .modal-validation-banner {
    background: rgba(239, 68, 68, 0.12) !important;
    border: 1px solid rgba(248, 113, 113, 0.35) !important;
    box-shadow: 0 4px 16px -2px rgba(239, 68, 68, 0.22) !important;
}

.modal-err-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #FEE2E2;
    color: #DC2626;
    border: 1px solid #FCA5A5;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
[data-bs-theme="dark"] .modal-err-icon,
[data-theme="portal-dark"] .modal-err-icon {
    background: rgba(239, 68, 68, 0.25) !important;
    color: #F87171 !important;
    border: 1px solid rgba(248, 113, 113, 0.45) !important;
}

.modal-err-title {
    color: #991B1B;
    font-size: 0.8125rem;
    font-weight: 700;
    letter-spacing: -0.01em;
}
[data-bs-theme="dark"] .modal-err-title,
[data-theme="portal-dark"] .modal-err-title {
    color: #FCA5A5 !important;
}

.modal-err-badge {
    background: #FEE2E2;
    color: #991B1B;
    border: 1px solid #FCA5A5;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 9999px;
    letter-spacing: 0.02em;
}
[data-bs-theme="dark"] .modal-err-badge,
[data-theme="portal-dark"] .modal-err-badge {
    background: rgba(239, 68, 68, 0.3) !important;
    color: #FECDD3 !important;
    border: 1px solid rgba(248, 113, 113, 0.45) !important;
}

.modal-err-list {
    margin-top: 8px;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.modal-err-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.45;
}

.modal-err-dot {
    color: #EF4444;
    font-size: 5px;
    flex-shrink: 0;
    margin-top: 6px;
}
[data-bs-theme="dark"] .modal-err-dot,
[data-theme="portal-dark"] .modal-err-dot {
    color: #F87171 !important;
    filter: drop-shadow(0 0 4px rgba(248, 113, 113, 0.6));
}

.modal-err-text {
    font-size: 0.775rem;
    font-weight: 500;
    color: #7F1D1D;
}
[data-bs-theme="dark"] .modal-err-text,
[data-theme="portal-dark"] .modal-err-text {
    color: #FEE2E2 !important;
}

/* Session Error / Warning Alert */
.modal-session-alert {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 12px;
    padding: 12px 15px;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.05);
}
[data-bs-theme="dark"] .modal-session-alert,
[data-theme="portal-dark"] .modal-session-alert {
    background: rgba(239, 68, 68, 0.12) !important;
    border: 1px solid rgba(248, 113, 113, 0.35) !important;
    box-shadow: 0 4px 16px -2px rgba(239, 68, 68, 0.22) !important;
}

.modal-session-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #FEE2E2;
    color: #DC2626;
    border: 1px solid #FCA5A5;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
[data-bs-theme="dark"] .modal-session-icon,
[data-theme="portal-dark"] .modal-session-icon {
    background: rgba(239, 68, 68, 0.25) !important;
    color: #F87171 !important;
    border: 1px solid rgba(248, 113, 113, 0.45) !important;
}

.modal-session-title {
    color: #991B1B;
    font-size: 0.8125rem;
    font-weight: 700;
}
[data-bs-theme="dark"] .modal-session-title,
[data-theme="portal-dark"] .modal-session-title {
    color: #FCA5A5 !important;
}

.modal-session-desc {
    color: #7F1D1D;
    font-size: 0.775rem;
    margin-top: 2px;
    line-height: 1.45;
}
[data-bs-theme="dark"] .modal-session-desc,
[data-theme="portal-dark"] .modal-session-desc {
    color: #FEE2E2 !important;
}

/* Session Success Alert */
.modal-session-success {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    border-radius: 12px;
    padding: 12px 15px;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.05);
}
[data-bs-theme="dark"] .modal-session-success,
[data-theme="portal-dark"] .modal-session-success {
    background: rgba(16, 185, 129, 0.12) !important;
    border: 1px solid rgba(52, 211, 153, 0.35) !important;
    box-shadow: 0 4px 16px -2px rgba(16, 185, 129, 0.22) !important;
}

.modal-session-success-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #D1FAE5;
    color: #059669;
    border: 1px solid #6EE7B7;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
[data-bs-theme="dark"] .modal-session-success-icon,
[data-theme="portal-dark"] .modal-session-success-icon {
    background: rgba(16, 185, 129, 0.25) !important;
    color: #34D399 !important;
    border: 1px solid rgba(52, 211, 153, 0.45) !important;
}

.modal-session-success-title {
    color: #065F46;
    font-size: 0.8125rem;
    font-weight: 700;
}
[data-bs-theme="dark"] .modal-session-success-title,
[data-theme="portal-dark"] .modal-session-success-title {
    color: #6EE7B7 !important;
}

.modal-session-success-desc {
    color: #047857;
    font-size: 0.775rem;
    margin-top: 2px;
    line-height: 1.45;
}
[data-bs-theme="dark"] .modal-session-success-desc,
[data-theme="portal-dark"] .modal-session-success-desc {
    color: #D1FAE5 !important;
}
</style>

{{-- Auto-reopen modal if validation errors or session error occurred (Rule 10) --}}
@if($shouldReopen)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('{{ $id }}');
        if (modalEl && typeof modalEl.showModal === 'function') {
            modalEl.showModal();
        }
    });
</script>
@endif


