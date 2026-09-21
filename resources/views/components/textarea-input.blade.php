@props(['disabled' => false, 'rows' => 3])

<textarea @disabled($disabled) rows="{{ $rows }}" {{ $attributes->merge(['class' => 'textarea textarea-bordered w-full bg-base-100 border-base-300 text-base-content text-sm rounded-field focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-base-content/40 transition']) }}>{{ $slot }}</textarea>
