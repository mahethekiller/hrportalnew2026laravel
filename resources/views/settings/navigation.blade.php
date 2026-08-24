@extends('layouts.app')

@section('title', 'Sidebar Dynamic Menu Manager')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">
                <i class="fa-solid fa-sitemap me-2 text-primary"></i> Navigation Menu Manager
            </h1>
            <p class="text-body-secondary fs-7 mb-0">Drag and drop navigation links, discover application routes, and manage sidebar hierarchy in real-time.</p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#menuModal" onclick="prepareAddModal()">
                <i class="fa-solid fa-plus me-1"></i> Add Menu Node
            </button>
        </div>
    </div>


    <!-- Role Preview Chips Bar -->
    <div class="card border-0 shadow-sm mb-4 bg-body-tertiary">
        <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="label-sm me-1"><i class="fa-solid fa-eye me-1 text-primary"></i>Role Navigation Preview:</span>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 role-preview-chip active" onclick="filterRolePreview('all', this)">All Nodes</button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 role-preview-chip" onclick="filterRolePreview('employee', this)">Employee</button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 role-preview-chip" onclick="filterRolePreview('manager', this)">Manager</button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 role-preview-chip" onclick="filterRolePreview('recruiter', this)">Recruiter</button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 role-preview-chip" onclick="filterRolePreview('hr', this)">HR Admin</button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 role-preview-chip" onclick="filterRolePreview('admin', this)">Super Admin</button>
            </div>
            <span class="fs-9 text-body-secondary"><i class="fa-solid fa-circle-info me-1"></i>Filter tree to preview menu accessibility for each role persona</span>
        </div>
    </div>

    <!-- Drag & Drop Container -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header border-0 pt-3 bg-body-tertiary d-flex align-items-center justify-content-between">
            <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                <i class="fa-solid fa-up-down-left-right text-primary me-2"></i> Navigation Menu Hierarchy Workstation
            </h3>
            <span class="badge bg-primary-subtle text-primary fs-8 fw-semibold">
                <i class="fa-solid fa-lightbulb me-1"></i> Drag rows to re-order navigation leaves
            </span>
        </div>

        <div class="card-body p-4">
            <div id="dragMenuContainer" class="list-group col">
                @foreach($menus as $root)
                    <div class="list-group-item border rounded-3 mb-3 p-3 bg-body-tertiary menu-node-item" data-id="{{ $root->menu_id }}" data-resource="{{ strtolower($root->resource_key ?? 'public') }}">
                        <div class="d-flex align-items-center justify-content-between cursor-move">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-grip-vertical text-body-secondary drag-handle fs-6 me-1"></i>
                                <i class="{{ $root->icon ?? 'fa-solid fa-folder' }} text-primary fs-5"></i>
                                <span class="fw-bold text-body-emphasis fs-6">{{ $root->title }}</span>
                                @if(!$root->is_active)
                                    <span class="badge bg-danger-subtle text-danger fs-9 ms-2">Hidden</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($root->route_name)
                                    <span class="badge bg-body-secondary text-body-emphasis border fs-9 font-mono"><i class="fa-solid fa-link me-1 text-info"></i>{{ $root->route_name }}</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary fs-9">Root Category</span>
                                @endif

                                @if($root->resource_key)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-9">{{ $root->resource_key }}</span>
                                @endif

                                <button class="btn btn-sm btn-outline-secondary py-1 px-2 fs-9 ms-2" onclick='prepareEditModal(@json($root))'>
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>

                                <form method="POST" action="{{ route('settings.navigation.destroy', $root->menu_id) }}" class="d-inline" onsubmit="return confirm('Delete this menu node and all its sub-links?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-ghost-danger py-1 px-2 fs-9" title="Delete Node"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Nested Sub-Items Dropzone -->
                        <div class="nested-sortable-list mt-3 min-h-50px p-2 bg-body rounded border border-dashed" style="min-height: 50px;" data-parent-id="{{ $root->menu_id }}">
                            @foreach($root->children as $child)
                                <div class="list-group-item border rounded p-2 mb-2 bg-body-secondary d-flex align-items-center justify-content-between cursor-move menu-node-item" data-id="{{ $child->menu_id }}" data-resource="{{ strtolower($child->resource_key ?? 'public') }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-grip-vertical text-body-secondary drag-handle fs-7 me-1"></i>
                                        <i class="{{ $child->icon ?? 'fa-solid fa-circle bullet-dot' }} text-success fs-7"></i>
                                        <span class="fw-semibold text-body-emphasis fs-7">{{ $child->title }}</span>
                                        @if(!$child->is_active)
                                            <span class="badge bg-danger-subtle text-danger fs-9">Hidden</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-body-secondary text-body-emphasis border fs-9 font-mono">{{ $child->route_name }}</span>
                                        @if($child->resource_key)
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-9">{{ $child->resource_key }}</span>
                                        @endif
                                        <button class="btn btn-sm btn-outline-secondary py-1 px-2 fs-9 ms-1" onclick='prepareEditModal(@json($child))'>
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('settings.navigation.destroy', $child->menu_id) }}" class="d-inline" onsubmit="return confirm('Delete this sub-menu link?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-ghost-danger py-1 px-2 fs-9" title="Delete Link"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Sticky Save Layout Bar (Appears when layout structure is modified) -->
<div id="stickySaveFooterBar" class="position-fixed bottom-0 start-50 translate-middle-x mb-4 z-3 d-none">
    <div class="card border-0 shadow-lg bg-body-tertiary px-4 py-3 rounded-pill border border-subtle d-flex flex-row align-items-center gap-3">
        <span class="fw-semibold text-body-emphasis fs-7"><i class="fa-solid fa-circle-exclamation text-warning me-2"></i>Layout modifications detected.</span>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="window.location.reload()">Discard</button>
        <button id="saveMenuStructureBtn" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-cloud-arrow-up me-1"></i> Save Layout Order
        </button>
    </div>
</div>

<!-- Add / Edit Menu Node Modal -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="menuForm" method="POST" action="{{ route('settings.navigation.store') }}">
                @csrf
                <div id="methodContainer"></div>

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-body-emphasis" id="modalTitle">Add Navigation Menu Node</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Menu Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="menu_title" class="form-control form-control-sm" required placeholder="e.g. Employee Directory">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Parent Section (Optional)</label>
                            <select name="parent_id" id="menu_parent_id" class="form-select form-select-sm">
                                <option value="0">Top-Level (Root Section)</option>
                                @foreach($menus as $root)
                                    <option value="{{ $root->menu_id }}">{{ $root->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dynamic Laravel Route Selector -->
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Target Application Route (DB Auto-Discovered)</label>
                            <select name="route_name" id="menu_route_name" class="form-select form-select-sm">
                                <option value="">None (Category Container)</option>
                                @foreach($availableRoutes as $rName)
                                    <option value="{{ $rName }}">{{ $rName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Permission Resource Key -->
                        <div class="col-md-6">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis">Security Permission Key</label>
                            <select name="resource_key" id="menu_resource_key" class="form-select form-select-sm">
                                <option value="">Public (Always Visible)</option>
                                @foreach($availableResources as $res)
                                    <option value="{{ $res }}">{{ $res }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Searchable Visual Icon Picker -->
                        <div class="col-md-12">
                            <label class="form-label fs-8 fw-semibold text-body-emphasis d-flex justify-content-between align-items-center">
                                <span>Menu Icon <span class="text-danger">*</span></span>
                                <span class="fs-9 text-body-secondary">Preview: <i id="iconPreview" class="fa-solid fa-circle text-primary fs-6 ms-1"></i></span>
                            </label>

                            <input type="hidden" name="icon" id="menu_icon" value="fa-solid fa-circle bullet-dot">

                            <div class="card border rounded-3 p-3 bg-body-tertiary">
                                <div class="input-group input-group-text-sm mb-3">
                                    <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" id="iconSearchInput" class="form-control" placeholder="Search icon (e.g. user, ticket, chart, building, settings)..." onkeyup="filterIcons()">
                                </div>

                                <div class="d-flex flex-wrap gap-2 overflow-y-auto p-2 bg-body rounded border" id="iconGrid" style="max-height: 160px;">
                                    @php
                                        $icons = [
                                            'fa-solid fa-gauge', 'fa-solid fa-users', 'fa-solid fa-building', 'fa-solid fa-sitemap',
                                            'fa-solid fa-calendar-days', 'fa-solid fa-clock', 'fa-solid fa-wallet', 'fa-solid fa-file-invoice-dollar',
                                            'fa-solid fa-chart-line', 'fa-solid fa-box-archive', 'fa-solid fa-briefcase', 'fa-solid fa-graduation-cap',
                                            'fa-solid fa-headset', 'fa-solid fa-ticket', 'fa-solid fa-bullhorn', 'fa-solid fa-user-shield',
                                            'fa-solid fa-gear', 'fa-solid fa-chart-pie', 'fa-solid fa-circle', 'fa-solid fa-envelope',
                                            'fa-solid fa-lock', 'fa-solid fa-folder', 'fa-solid fa-layer-group', 'fa-solid fa-circle-check'
                                        ];
                                    @endphp
                                    @foreach($icons as $ic)
                                        <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 icon-select-btn px-2 py-1 fs-9" data-icon="{{ $ic }}" onclick="selectIcon('{{ $ic }}')">
                                            <i class="{{ $ic }} text-primary"></i> <span>{{ str_replace(['fa-solid ', 'fa-regular '], '', $ic) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="menu_is_active" value="1" checked>
                                <label class="form-check-label fs-8 fw-semibold text-body-emphasis" for="menu_is_active">Visible in Sidebar Navigation</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4">Save Navigation Node</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Local Vendor Script (Zero CDN Compliance) -->
<script src="{{ asset('assets/vendor/sortablejs/Sortable.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var stickyBar = document.getElementById('stickySaveFooterBar');

    function markDirty() {
        if (stickyBar) stickyBar.classList.remove('d-none');
    }

    // 1. SortableJS initialization
    var rootEl = document.getElementById('dragMenuContainer');
    if (rootEl) {
        new Sortable(rootEl, {
            group: 'roots',
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'bg-primary-subtle',
            onEnd: markDirty
        });
    }

    var nestedLists = document.querySelectorAll('.nested-sortable-list');
    nestedLists.forEach(function (list) {
        new Sortable(list, {
            group: 'children',
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'bg-success-subtle',
            onEnd: markDirty
        });
    });

    // 2. Save layout button click
    document.getElementById('saveMenuStructureBtn')?.addEventListener('click', function () {
        var structure = [];
        var roots = document.querySelectorAll('#dragMenuContainer > .list-group-item');
        roots.forEach(function (root, rootIdx) {
            var rootId = root.getAttribute('data-id');
            structure.push({ id: rootId, parent_id: 0 });
            
            var children = root.querySelectorAll('.nested-sortable-list > .list-group-item');
            children.forEach(function (child, childIdx) {
                var childId = child.getAttribute('data-id');
                structure.push({ id: childId, parent_id: rootId });
            });
        });

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

// Role Preview Chips Filter Helper
function filterRolePreview(role, btnEl) {
    document.querySelectorAll('.role-preview-chip').forEach(c => {
        c.classList.remove('btn-primary', 'active');
        c.classList.add('btn-outline-secondary');
    });
    btnEl.classList.remove('btn-outline-secondary');
    btnEl.classList.add('btn-primary', 'active');

    const roleResourceMap = {
        'employee': ['public'],
        'manager': ['public', 'organization', 'leave', 'attendance', 'performance'],
        'recruiter': ['public', 'recruitment'],
        'hr': ['public', 'employees', 'organization', 'leave', 'attendance', 'payroll', 'performance', 'training', 'assets', 'announcements', 'reports', 'support_tickets', 'hr_tickets'],
        'admin': ['all']
    };

    const items = document.querySelectorAll('.menu-node-item');
    items.forEach(item => {
        const resKey = item.getAttribute('data-resource') || 'public';
        if (role === 'all' || (roleResourceMap[role] && (roleResourceMap[role].includes('all') || roleResourceMap[role].includes(resKey)))) {
            item.style.opacity = '1';
        } else {
            item.style.opacity = '0.35';
        }
    });
}

// Modal helpers
function prepareAddModal() {
    document.getElementById('modalTitle').innerText = 'Add Navigation Menu Node';
    document.getElementById('menuForm').action = '{{ route("settings.navigation.store") }}';
    document.getElementById('methodContainer').innerHTML = '';
    document.getElementById('menu_title').value = '';
    document.getElementById('menu_parent_id').value = '0';
    document.getElementById('menu_route_name').value = '';
    document.getElementById('menu_resource_key').value = '';
    selectIcon('fa-solid fa-circle');
    document.getElementById('menu_is_active').checked = true;
}

function prepareEditModal(node) {
    document.getElementById('modalTitle').innerText = 'Edit Navigation Menu Node: ' + node.title;
    document.getElementById('menuForm').action = '/settings/navigation/' + node.menu_id;
    document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('menu_title').value = node.title || '';
    document.getElementById('menu_parent_id').value = node.parent_id || '0';
    document.getElementById('menu_route_name').value = node.route_name || '';
    document.getElementById('menu_resource_key').value = node.resource_key || '';
    selectIcon(node.icon || 'fa-solid fa-circle');
    document.getElementById('menu_is_active').checked = node.is_active ? true : false;
    var modal = new bootstrap.Modal(document.getElementById('menuModal'));
    modal.show();
}

function selectIcon(iconClass) {
    document.getElementById('menu_icon').value = iconClass;
    var preview = document.getElementById('iconPreview');
    if (preview) preview.className = iconClass + ' text-primary fs-6 ms-1';
}

function filterIcons() {
    var query = document.getElementById('iconSearchInput').value.toLowerCase();
    var buttons = document.querySelectorAll('.icon-select-btn');
    buttons.forEach(function(btn) {
        var text = btn.innerText.toLowerCase();
        btn.style.display = text.includes(query) ? 'inline-flex' : 'none';
    });
}
</script>
@endsection
