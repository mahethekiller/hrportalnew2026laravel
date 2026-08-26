<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HR Portal') - {{ $systemSetting->application_name ?? 'Antigravity HR' }}</title>

    <!-- Local Vendor CSS Assets -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet">

    <!-- Custom Theme Styling -->
    <link href="{{ asset('assets/css/app.css') }}?v={{ @filemtime(public_path('assets/css/app.css')) }}" rel="stylesheet">
    @stack('css')

    <!-- Pre-load Dark Theme & Color Profile Engine -->
    <script src="{{ asset('assets/js/theme-engine.js') }}?v={{ @filemtime(public_path('assets/js/theme-engine.js')) }}"></script>
</head>
<body class="bg-body-secondary">
    @php
        $themeService = app(\App\Services\ThemeService::class);
        $activeThemeConfig = $themeService->getThemeConfig();
        $seasonalAccents = $themeService->getSeasonalAccents();
        $activeAccentKey = $activeThemeConfig['seasonal_accent'] ?? 'off';
        $activeAccent = $seasonalAccents[$activeAccentKey] ?? null;
    @endphp

    @if(!empty($activeAccent) && $activeAccentKey !== 'off' && !empty($activeAccent['banner_css']))
        <!-- Seasonal & Festival Banner Overlay -->
        <div class="py-2 px-4 text-center fs-8 fw-bold z-3 position-relative d-flex align-items-center justify-content-center gap-2" style="{{ $activeAccent['banner_css'] }}">
            <i class="fa-solid {{ $activeAccent['icon'] }} fs-6"></i>
            <span>{{ $activeAccent['text'] }}</span>
            <span class="badge bg-body text-body-emphasis border fs-9 ms-2">{{ $activeAccent['badge'] }}</span>
        </div>
    @endif

    @if(session()->has('impersonated_by'))
        <!-- Impersonation Guard Warning Banner -->
        <div class="alert alert-warning border-0 rounded-0 m-0 py-2 px-4 d-flex align-items-center justify-content-between bg-warning-subtle text-warning-emphasis border-bottom border-warning-subtle z-3 position-relative">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-user-shield fs-5 text-warning"></i>
                <span class="fs-8">Viewing as <strong>{{ Auth::user()->first_name ?? Auth::user()->name }}</strong> (Session Impersonated). Sensitive financial actions are locked and logged.</span>
            </div>
            @if(Route::has('impersonate.stop'))
                <form method="POST" action="{{ route('impersonate.stop') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm fs-9 fw-bold px-3 py-1">Stop Impersonating</button>
                </form>
            @endif
        </div>
    @endif

    <div class="d-flex">
        <!-- Sidebar Navigation -->
        @include('layouts.sidebar')

        <!-- Main Content Area -->
        <div id="main-content" class="d-flex flex-column">
            <!-- Navigation Header -->
            <nav class="navbar navbar-expand navbar-light bg-body border-bottom px-4 py-2 sticky-top">
                <div class="container-fluid p-0">
                    <button class="btn btn-outline-secondary d-lg-none me-3" type="button" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    
                    <div class="d-flex align-items-center gap-3">
                        <span class="navbar-brand fw-semibold text-body-emphasis mb-0">@yield('page_title', 'Dashboard')</span>
                        
                        <!-- Quick Search / Command Palette Launcher Button -->
                        <button type="button" class="btn btn-body-tertiary border-subtle btn-sm text-body-secondary d-none d-md-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-xs" data-bs-toggle="modal" data-bs-target="#commandPaletteModal">
                            <i class="fa-solid fa-magnifying-glass fs-8"></i>
                            <span class="fs-8">Quick Search & Jump...</span>
                            <kbd class="bg-body-secondary text-body-emphasis border border-subtle rounded px-1 fs-9 ms-2">Ctrl K</kbd>
                        </button>
                    </div>
                    
                    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                        <ul class="navbar-nav align-items-center gap-3">
                            <!-- Theme Palette Customizer Drawer Button -->
                            <li class="nav-item">
                                <button class="btn btn-link text-body-secondary p-0 border-0 fs-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#themeCustomizerDrawer" title="Theme & Color Profiles">
                                    <i class="fa-solid fa-palette text-primary"></i>
                                </button>
                            </li>

                            <!-- Theme Mode Toggle Button -->
                            <li class="nav-item">
                                <button class="btn btn-link text-body-secondary p-0 border-0 theme-toggle-btn fs-5" id="theme-toggle-btn" onclick="PortalTheme.setThemeMode(document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark')" type="button" title="Toggle theme mode">
                                    <i class="fa-solid fa-moon d-none" id="theme-icon-dark"></i>
                                    <i class="fa-solid fa-sun" id="theme-icon-light"></i>
                                </button>
                            </li>

                            <!-- User Actions Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center text-body-emphasis p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-bell fs-5 text-body-secondary"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow mt-2">
                                    <li><span class="dropdown-item text-muted">No new notifications</span></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Page View Content -->
            <div class="container-fluid p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Floating Quick Action Dock (FAB) -->
    <div class="floating-action-dock dropdown">
        <button class="fab-button" type="button" id="fabMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Quick Actions">
            <i class="fa-solid fa-bolt fs-4"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-subtle p-2 mb-2" aria-labelledby="fabMenuButton" style="min-width: 220px; border-radius: 12px;">
            <li><h6 class="dropdown-header text-uppercase fs-9 fw-bold tracking-wider">Quick Actions</h6></li>
            <li><a class="dropdown-item rounded py-2 fs-8 fw-semibold" href="{{ route('my-portal.leaves') }}"><i class="fa-solid fa-calendar-plus text-primary me-2"></i> Apply for Leave</a></li>
            <li><a class="dropdown-item rounded py-2 fs-8 fw-semibold" href="{{ route('my-portal.conveyance') }}"><i class="fa-solid fa-receipt text-success me-2"></i> Submit Claim</a></li>
            <li><a class="dropdown-item rounded py-2 fs-8 fw-semibold" href="{{ route('my-portal.meetings') }}"><i class="fa-solid fa-door-open text-warning me-2"></i> Book Room</a></li>
            <li><a class="dropdown-item rounded py-2 fs-8 fw-semibold" href="{{ route('support-tickets.create') }}"><i class="fa-solid fa-headset text-info me-2"></i> Raise HR Ticket</a></li>
        </ul>
    </div>

    <!-- Command Palette Search Modal (Ctrl + K) -->
    <div class="modal fade command-palette-modal" id="commandPaletteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg bg-body-tertiary">
                <div class="p-3 border-bottom border-subtle d-flex align-items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-body-secondary ms-2"></i>
                    <input type="text" id="commandPaletteInput" class="form-control command-palette-input text-body-emphasis" placeholder="Search pages, employees, or features..." autocomplete="off">
                    <button type="button" class="btn-close me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3" style="max-height: 400px; overflow-y: auto;">
                    <div id="commandPaletteResults">
                        <div class="text-uppercase fs-9 fw-bold text-body-secondary px-3 mb-2 tracking-wider">Navigation Shortcuts</div>
                        <a href="{{ route('dashboard') }}" class="command-palette-item">
                            <span><i class="fa-solid fa-chart-pie me-2 text-primary"></i> Main Dashboard</span>
                            <span class="badge bg-body-secondary text-body-secondary fs-9">Page</span>
                        </a>
                        <a href="{{ route('my-portal.leaves') }}" class="command-palette-item">
                            <span><i class="fa-solid fa-calendar-check me-2 text-success"></i> My Leave Applications</span>
                            <span class="badge bg-body-secondary text-body-secondary fs-9">ESS</span>
                        </a>
                        <a href="{{ route('my-portal.payslips') }}" class="command-palette-item">
                            <span><i class="fa-solid fa-wallet me-2 text-warning"></i> My Payslips</span>
                            <span class="badge bg-body-secondary text-body-secondary fs-9">Payroll</span>
                        </a>
                        <a href="{{ route('manager-portal.index') }}" class="command-palette-item">
                            <span><i class="fa-solid fa-users-gear me-2 text-info"></i> Manager Workstation</span>
                            <span class="badge bg-body-secondary text-body-secondary fs-9">Manager</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="command-palette-item">
                            <span><i class="fa-solid fa-chart-line me-2 text-danger"></i> Reports & Analytics</span>
                            <span class="badge bg-body-secondary text-body-secondary fs-9">Admin</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-body border-top border-subtle d-flex justify-content-between">
                    <span class="fs-9 text-body-secondary">Press <kbd class="bg-body-secondary text-body-emphasis border border-subtle rounded px-1 fs-9">Esc</kbd> to exit</span>
                    <span class="fs-9 text-body-secondary">Antigravity Quick Search</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Local Vendor Scripts -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/dayjs/dayjs.min.js') }}"></script>

    <!-- Base Layout Controls Script -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcons(newTheme);
        }

        function updateThemeIcons(theme) {
            const darkIcon = document.getElementById('theme-icon-dark');
            const lightIcon = document.getElementById('theme-icon-light');
            if (theme === 'dark') {
                darkIcon.classList.remove('d-none');
                lightIcon.classList.add('d-none');
            } else {
                darkIcon.classList.add('d-none');
                lightIcon.classList.remove('d-none');
            }
        }

        // Run on boot to ensure icons match attributes
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            updateThemeIcons(currentTheme);

            // Global Select2 Searchable Dropdown Initializer
            if (typeof $.fn.select2 !== 'undefined') {
                function initPortalSelect2(context) {
                    var $targets = context ? $(context).find('.select-search, select.select2, select[data-control="select2"]') : $('.select-search, select.select2, select[data-control="select2"]');
                    $targets.each(function() {
                        var $this = $(this);
                        var placeholder = $this.attr('placeholder') || 'Search & Select...';
                        $this.select2({
                            width: '100%',
                            placeholder: placeholder,
                            allowClear: true,
                            minimumResultsForSearch: 0,
                            dropdownParent: $this.closest('.modal').length ? $this.closest('.modal') : $(document.body)
                        });
                    });
                }

                initPortalSelect2();

                // Re-initialize or adjust width on Bootstrap tab switches & modals
                $(document).on('shown.bs.tab shown.bs.modal', function(e) {
                    initPortalSelect2(e.target);
                });
            }

            // Command Palette (Ctrl + K) Keyboard Shortcut
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    const paletteModalEl = document.getElementById('commandPaletteModal');
                    if (paletteModalEl) {
                        const modal = bootstrap.Modal.getOrCreateInstance(paletteModalEl);
                        modal.toggle();
                    }
                }
            });

            // Focus search input when command palette modal opens
            const paletteModalEl = document.getElementById('commandPaletteModal');
            if (paletteModalEl) {
                paletteModalEl.addEventListener('shown.bs.modal', () => {
                    const input = document.getElementById('commandPaletteInput');
                    if (input) input.focus();
                });
            }

            // Real-time Command Palette Item Filter
            const searchInput = document.getElementById('commandPaletteInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const term = this.value.toLowerCase().trim();
                    const items = document.querySelectorAll('.command-palette-item');
                    });
                });
            }
        });
    </script>

    <!-- Theme Customizer Offcanvas Drawer -->
    @include('layouts.theme_customizer')

    @stack('js')
</body>
</html>
