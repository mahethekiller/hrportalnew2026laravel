@props([
    'headers' => [],
    'isEmpty' => false,
    'emptyTitle' => 'No Records Found',
    'emptyDescription' => 'There are no entries available in this table.',
    'emptyIcon' => 'fa-solid fa-folder-open',
])

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0 fs-8">
        <thead class="bg-body-secondary">
            <tr>
                @foreach($headers as $header)
                    <th class="{{ $loop->first ? 'ps-4' : '' }} text-body-secondary fs-9 text-uppercase tracking-wider">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if($isEmpty)
                <tr>
                    <td colspan="{{ count($headers) }}" class="p-0">
                        <x-empty-state 
                            :icon="$emptyIcon" 
                            :title="$emptyTitle" 
                            :description="$emptyDescription" 
                        />
                    </td>
                </tr>
            @else
                {{ $slot }}
            @endif
        </tbody>
    </table>
</div>
