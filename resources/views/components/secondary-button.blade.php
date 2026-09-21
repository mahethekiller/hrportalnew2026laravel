<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-ghost border border-base-300 font-medium text-sm text-base-content hover:bg-base-200 inline-flex items-center gap-2']) }}>
    {{ $slot }}
</button>

