<aside id="sidebar" class="d-flex flex-column align-items-stretch flex-shrink-0">
    <!-- Sidebar Header / Brand Logo -->
    <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-secondary-subtle">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none text-body-emphasis gap-2">
            <i class="fa-solid fa-layer-group text-primary fs-3"></i>
            <div class="d-flex flex-column">
                <span class="fs-6 fw-bold text-gray-900 leading-none">Antigravity HR</span>
                <span class="fs-9 text-gray-500 fw-semibold">Enterprise Portal</span>
            </div>
        </a>
        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" onclick="toggleSidebar()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    
    <!-- Sidebar Navigation Menu (Accordion Supported) -->
    <div class="nav nav-pills flex-column flex-nowrap mb-auto overflow-y-auto py-2 px-2" id="sidebarMenu">
        
        <!-- MAIN MENU -->
        <div class="px-3 pt-2 pb-1 text-uppercase label-sm text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.08em;">Main</div>
        
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge menu-icon"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users menu-icon"></i>
            <span>Employees</span>
        </a>

        <!-- DYNAMIC DATABASE MENUS -->
        @foreach($dynamicMenus as $root)
            @php
                $childRouteNames = $root->children->pluck('route_name')->filter()->toArray();
                $isRootActive = false;
                foreach($childRouteNames as $rn) {
                    if (request()->routeIs($rn . '*')) {
                        $isRootActive = true;
                        break;
                    }
                }
                $collapseId = 'menuNode' . $root->menu_id;
            @endphp
            <div class="menu-item py-1">
                <a class="nav-link w-100 justify-content-between {{ $isRootActive ? '' : 'collapsed' }}" 
                   data-bs-toggle="collapse" 
                   href="#{{ $collapseId }}" 
                   role="button" 
                   aria-expanded="{{ $isRootActive ? 'true' : 'false' }}" 
                   aria-controls="{{ $collapseId }}">
                    <div class="d-flex align-items-center">
                        <i class="{{ $root->icon ?? 'fa-solid fa-folder' }} menu-icon"></i>
                        <span>{{ $root->title }}</span>
                    </div>
                    <i class="fa-solid fa-chevron-right menu-arrow"></i>
                </a>

                <div class="collapse nav-sub-menu {{ $isRootActive ? 'show' : '' }}" id="{{ $collapseId }}" data-bs-parent="#sidebarMenu">
                    @foreach($root->children as $child)
                        @php
                            $userRole = Auth::user()?->roleRelation;
                            $hasAccess = true;
                            if ($userRole && $userRole->role_access !== 'all' && $child->resource_key) {
                                $hasAccess = in_array("view." . $child->resource_key, $userRole->resource_list);
                            }
                        @endphp
                        @if($hasAccess)
                            @php
                                $routeUrl = '#';
                                if ($child->route_name) {
                                    try {
                                        $routeUrl = route($child->route_name);
                                    } catch (\Throwable $e) {
                                        $routeUrl = '#';
                                    }
                                }
                            @endphp
                            <a href="{{ $routeUrl }}" class="nav-sub-link {{ ($child->route_name && request()->routeIs($child->route_name . '*')) ? 'active' : '' }}">
                                <i class="fa-solid fa-circle bullet-dot"></i>
                                <span>{{ $child->title }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- User Profile Dropdown Footer -->
    <div class="p-3 border-top border-secondary-subtle">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-body-emphasis" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-circle-user fs-3 me-2 text-primary"></i>
                <div class="d-flex flex-column text-start me-auto overflow-hidden">
                    <strong class="text-truncate fs-7">{{ Auth::user()->first_name ?? Auth::user()->name ?? 'Administrator' }}</strong>
                    <span class="fs-9 text-muted text-truncate">{{ Auth::user()->email ?? 'admin@example.com' }}</span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li>
                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                        <i class="fa-regular fa-user me-2 text-primary"></i> Profile Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</aside>
