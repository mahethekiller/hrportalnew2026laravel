<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-error font-semibold text-sm text-white shadow-xs submit-loader inline-flex items-center gap-2']) }} onclick="submitWithLoader(this)">
    {{ $slot }}
</button>

