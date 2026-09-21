@props(['value'])

<label {{ $attributes->merge(['class' => 'label-text font-semibold text-xs text-base-content/80 uppercase tracking-wider mb-1.5 block']) }}>
    {{ $value ?? $slot }}
</label>

