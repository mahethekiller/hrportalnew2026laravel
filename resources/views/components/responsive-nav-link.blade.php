@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active fw-semibold text-primary bg-primary-subtle rounded-2 px-3 py-2 transition'
            : 'nav-link text-body-secondary rounded-2 px-3 py-2 transition hover-bg-light';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
