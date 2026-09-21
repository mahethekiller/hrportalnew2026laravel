@props(['disabled' => false, 'label' => null])

<label class="label cursor-pointer justify-start gap-2.5 py-1">
    <input type="checkbox" @disabled($disabled) {{ $attributes->merge(['class' => 'checkbox checkbox-primary checkbox-sm rounded-field border-base-300 transition']) }}>
    @if($label || (isset($slot) && $slot->isNotEmpty()))
        <span class="label-text text-sm font-medium text-base-content/90">{{ $label ?? $slot }}</span>
    @endif
</label>
