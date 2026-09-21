@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs text-error font-medium mt-1.5 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation text-[11px] shrink-0"></i>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif

