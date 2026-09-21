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
        <form action="{{ $action }}" method="POST" {{ $multipart ? 'enctype="multipart/form-data"' : '' }}>
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
                {{-- In-Modal Error Alerts (Rule 10) --}}
                @if(session('error'))
                    <div class="alert alert-error mb-4 text-xs font-semibold py-2.5 px-3 rounded-lg flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-sm shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error mb-4 text-xs font-semibold py-2.5 px-3 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-xmark text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <div class="font-bold">Please check the form for errors:</div>
                                <ul class="list-disc list-inside mt-1 space-y-0.5 opacity-90">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
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


