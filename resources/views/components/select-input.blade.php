@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->merge(['class' => 'select select-bordered w-full bg-base-100 border-base-300 text-base-content text-sm rounded-field focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition']) }}>
    {{ $slot }}
</select>
