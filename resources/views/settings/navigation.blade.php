@extends('layouts.app')

@section('title', 'Sidebar Dynamic Menu Manager')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">Sidebar Dynamic Menu Manager</h1>
            <p class="text-muted fs-7 mb-0">Drag and drop navigation links to customize the HR Portal sidebar hierarchy and ordering.</p>
        </div>
    </div>

    <!-- Drag & Drop Container -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <h3 class="card-title fw-bold text-gray-900 fs-6">
                <i class="fa-solid fa-arrows-alt text-primary me-2"></i> Draggable Navigation Structure Workstation
            </h3>
        </div>
        <div class="card-body p-4">
            <div id="dragMenuContainer" class="list-group col">
                @foreach($menus as $root)
                    <div class="list-group-item border rounded mb-3 bg-light p-3" data-id="{{ $root->menu_id }}">
                        <div class="d-flex align-items-center justify-content-between fw-bold text-gray-900 cursor-move">
                            <span>
                                <i class="fa-solid fa-bars-staggered text-muted me-2 drag-handle"></i>
                                <i class="{{ $root->icon ?? 'fa-solid fa-folder' }} text-primary me-2"></i>
                                {{ $root->title }}
                            </span>
                            <span class="badge bg-light-primary text-primary fs-9">Root Section</span>
                        </div>
                        
                        <!-- Children Dropzone Area -->
                        <div class="nested-sortable-list mt-3 min-h-50px p-2 bg-white rounded border border-dashed" style="min-height: 60px;" data-parent-id="{{ $root->menu_id }}">
                            @foreach($root->children as $child)
                                <div class="list-group-item border rounded p-2 mb-2 bg-white d-flex align-items-center justify-content-between cursor-move" data-id="{{ $child->menu_id }}">
                                    <span>
                                        <i class="fa-solid fa-grip-vertical text-muted me-2 drag-handle"></i>
                                        <i class="fa-solid fa-link text-success me-2"></i>
                                        {{ $child->title }}
                                    </span>
                                    <span class="badge bg-light-success text-success fs-9 font-monospace">{{ $child->route_name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-end mt-4">
                <button id="saveMenuStructureBtn" class="btn btn-primary btn-sm px-4">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Save Layout Hierarchy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Load SortableJS library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Make root sections draggable
    var rootEl = document.getElementById('dragMenuContainer');
    new Sortable(rootEl, {
        group: 'roots',
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'bg-light-warning'
    });

    // 2. Make children items draggable between different root containers
    var nestedLists = document.querySelectorAll('.nested-sortable-list');
    nestedLists.forEach(function (list) {
        new Sortable(list, {
            group: 'children',
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'bg-light-success'
        });
    });

    // 3. Serialize and save the menu structure on button click
    document.getElementById('saveMenuStructureBtn').addEventListener('click', function () {
        var structure = [];
        
        // Loop roots
        var roots = document.querySelectorAll('#dragMenuContainer > .list-group-item');
        roots.forEach(function (root, rootIdx) {
            var rootId = root.getAttribute('data-id');
            structure.push({
                id: rootId,
                parent_id: 0
            });
            
            // Loop children in this root
            var children = root.querySelectorAll('.list-group-item');
            children.forEach(function (child, childIdx) {
                var childId = child.getAttribute('data-id');
                structure.push({
                    id: childId,
                    parent_id: rootId
                });
            });
        });

        // AJAX POST request to save layout
        fetch('{{ route("settings.navigation.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ structure: structure })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Structure saved successfully!');
            window.location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Failed to save structure.');
        });
    });
});
</script>
@endsection
