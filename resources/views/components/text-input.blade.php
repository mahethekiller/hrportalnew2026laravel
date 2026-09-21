@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input input-bordered w-full bg-base-100 border-base-300 text-base-content text-sm rounded-field focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-base-content/40 transition']) }}>

