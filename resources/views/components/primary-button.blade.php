<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary font-semibold text-sm shadow-xs submit-loader inline-flex items-center gap-2']) }} onclick="submitWithLoader(this)">
    {{ $slot }}
</button>

