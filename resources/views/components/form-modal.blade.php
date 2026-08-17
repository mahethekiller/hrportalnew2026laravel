@props([
    'id',
    'title' => 'Form Modal',
    'action' => '#',
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
