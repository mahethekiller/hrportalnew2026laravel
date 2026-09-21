<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="portal-light" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HR Portal') - {{ $systemSetting->application_name ?? 'Antigravity HR' }}</title>

    <!-- Tailwind CSS 4 & daisyUI 5 (Local Vite Bundle) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Local Vendor CSS Assets -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet">

    <!-- Custom Theme Styling Bridge -->
    <link href="{{ asset('assets/css/app.css') }}?v={{ @filemtime(public_path('assets/css/app.css')) }}" rel="stylesheet">
    @stack('css')

    <!-- Pre-load Dark Theme & Color Profile Engine -->
    <script src="{{ asset('assets/js/theme-engine.js') }}?v={{ @filemtime(public_path('assets/js/theme-engine.js')) }}"></script>

    <!-- Global Form Submit Helper & Smart Multi-Tab/Hidden Validation Engine -->
    <script>
        // Check if an element is hidden from user interaction
        function isElementHidden(el) {
            if (!el) return true;
            return (el.offsetParent === null) || 
                   window.getComputedStyle(el).display === 'none' || 
                   window.getComputedStyle(el).visibility === 'hidden' ||
                   el.closest('.tab-pane:not(.active), .collapse:not(.show), [style*="display: none"]');
        }

        // Auto-activate tab pane or collapse containing a specific element
        window.revealElementContainer = function(el) {
            if (!el) return false;
            let revealed = false;

            // 1. Reveal Bootstrap / daisyUI Tab Pane
            const tabPane = el.closest('.tab-pane');
            if (tabPane && (!tabPane.classList.contains('active') || !tabPane.classList.contains('show'))) {
                const tabId = tabPane.getAttribute('id');
                if (tabId) {
                    const tabBtn = document.querySelector(
                        `button[data-bs-target="#${tabId}"], button[data-target="#${tabId}"], a[data-bs-target="#${tabId}"], a[data-target="#${tabId}"], a[href="#${tabId}"]`
                    );
                    if (tabBtn) {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                            bootstrap.Tab.getOrCreateInstance(tabBtn).show();
                        } else if (typeof $ !== 'undefined' && $(tabBtn).tab) {
                            $(tabBtn).tab('show');
                        } else {
                            tabBtn.click();
                        }
                        revealed = true;
                    } else {
                        // Fallback: force active class
                        const tabContent = tabPane.closest('.tab-content');
                        if (tabContent) {
                            tabContent.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active', 'show'));
                        }
                        tabPane.classList.add('active', 'show');
                        revealed = true;
                    }
                }
            }

            // 2. Reveal Bootstrap Collapse / Accordion
            const collapseEl = el.closest('.collapse:not(.show)');
            if (collapseEl) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    bootstrap.Collapse.getOrCreateInstance(collapseEl).show();
                } else {
                    collapseEl.classList.add('show');
                }
                revealed = true;
            }

            return revealed;
        };

        // Comprehensive Form Validation that safely reveals hidden tabs first
        let isRevealingInvalid = false;
        window.validateFormWithTabs = function(form) {
            if (!form) return true;

            const controls = form.querySelectorAll('input, select, textarea');
            for (let i = 0; i < controls.length; i++) {
                const control = controls[i];
                if (control.disabled || control.type === 'hidden' || control.type === 'submit' || control.type === 'button') continue;

                if (!control.checkValidity()) {
                    if (!isRevealingInvalid) {
                        isRevealingInvalid = true;
                        window.revealElementContainer(control);
                        setTimeout(() => {
                            try { control.focus(); } catch (err) {}
                            isRevealingInvalid = false;
                        }, 120);
                    }
                    return false;
                }
            }
            return true;
        };

        // Universal handler for native HTML5 'invalid' event to prevent "not focusable" console crashes
        let isHandlingInvalid = false;
        document.addEventListener('invalid', function(e) {
            const el = e.target;
            if (!el) return;

            // If the element is hidden (inside inactive tab or closed collapse)
            if (isElementHidden(el)) {
                // Suppress browser's default focus attempt on hidden element
                e.preventDefault();

                if (isHandlingInvalid) return;
                isHandlingInvalid = true;

                // Reveal its container
                window.revealElementContainer(el);

                // Focus after reveal without re-triggering validation loop
                setTimeout(() => {
                    try { el.focus(); } catch (err) {}
                    isHandlingInvalid = false;
                }, 150);
            }
        }, true);

        // Global Form Submit Helper
        window.submitWithLoader = function(btn) {
            if (!btn) return;
            const form = btn.closest('form');
            if (!form) return;

            if (!window.validateFormWithTabs(form)) {
                return false;
            }

            btn.disabled = true;
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Processing...';
            form.submit();
        };

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-loader, .submit-loader');
            if (btn) {
                window.submitWithLoader(btn);
            }
        }, true);

        // daisyUI Native <dialog> Modal Click Bridge
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('[data-bs-toggle="modal"], [data-toggle="modal"]');
            if (trigger) {
                const targetSelector = trigger.getAttribute('data-bs-target') || trigger.getAttribute('data-target') || trigger.getAttribute('href');
                if (targetSelector && targetSelector.startsWith('#')) {
                    const modal = document.querySelector(targetSelector);
                    if (modal && typeof modal.showModal === 'function') {
                        e.preventDefault();
                        modal.showModal();
                    }
                }
            }
            const dismiss = e.target.closest('[data-bs-dismiss="modal"], [data-dismiss="modal"]');
            if (dismiss) {
                const modal = dismiss.closest('dialog.modal, .modal');
                if (modal && typeof modal.close === 'function') {
                    e.preventDefault();
                    modal.close();
                }
            }
        }, true);

        // Auto-initialize Select2 when daisyUI <dialog.modal> opens
        if (typeof HTMLDialogElement !== 'undefined') {
            const origShowModal = HTMLDialogElement.prototype.showModal;
            HTMLDialogElement.prototype.showModal = function() {
                origShowModal.apply(this, arguments);
                if (typeof window.initPortalSelect2 === 'function') {
                    window.initPortalSelect2(this);
                }
            };
        }
    </script>
</head>
<body class="bg-body-secondary">
    @php
        $themeService = app(\App\Services\ThemeService::class);
        $activeThemeConfig = $themeService->getThemeConfig();
        $seasonalAccents = $themeService->getSeasonalAccents();
        $activeAccentKey = $activeThemeConfig['seasonal_accent'] ?? 'auto';

        $activeAccent = null;

        // 1. Dynamic Holiday Auto-Sync with Published Holidays Table
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('xin_holidays')) {
                $today = date('Y-m-d');
                $upcomingRange = date('Y-m-d', strtotime('+14 days'));

                    // Check if today is an active published holiday
                    $todayHoliday = \App\Models\Holiday::where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->first();

                    if ($todayHoliday) {
                        $nameLower = strtolower($todayHoliday->event_name);
                        $gradient = 'background: linear-gradient(90deg, #B45309 0%, #F59E0B 50%, #B45309 100%); color: #ffffff;';
                        $icon = 'fa-gift';

                        if (str_contains($nameLower, 'diwali') || str_contains($nameLower, 'deepawali')) {
                            $gradient = 'background: linear-gradient(90deg, #B45309 0%, #F59E0B 50%, #B45309 100%); color: #ffffff;';
                            $icon = 'fa-om';
                        } elseif (str_contains($nameLower, 'independence') || str_contains($nameLower, 'republic')) {
                            $gradient = 'background: linear-gradient(90deg, #FF671F 0%, #FFFFFF 50%, #046A38 100%); color: #1e293b;';
                            $icon = 'fa-flag';
                        } elseif (str_contains($nameLower, 'raksha') || str_contains($nameLower, 'holi')) {
                            $gradient = 'background: linear-gradient(90deg, #7C3AED 0%, #EC4899 50%, #7C3AED 100%); color: #ffffff;';
                            $icon = 'fa-wand-magic-sparkles';
                        }

                        $activeAccent = [
                            'name' => $todayHoliday->event_name,
                            'badge' => 'Holiday Today',
                            'banner_css' => $gradient,
                            'text' => '🎉 Celebrating ' . $todayHoliday->event_name . '! Warmest wishes & greetings from HR.',
                            'icon' => $icon,
                        ];
                    } else {
                        // Check upcoming holiday within 14 days
                        $upcomingHoliday = \App\Models\Holiday::where('start_date', '>', $today)
                            ->where('start_date', '<=', $upcomingRange)
                            ->orderBy('start_date', 'asc')
                            ->first();

                        if ($upcomingHoliday) {
                            $formattedDate = \Carbon\Carbon::parse($upcomingHoliday->start_date)->format('D, M d');
                            $activeAccent = [
                                'name' => $upcomingHoliday->event_name,
                                'badge' => 'Upcoming Holiday',
                                'banner_css' => 'background: linear-gradient(90deg, #1E3A8A 0%, #2563EB 50%, #1E3A8A 100%); color: #ffffff;',
                                'text' => '🗓️ Upcoming Holiday: ' . $upcomingHoliday->event_name . ' on ' . $formattedDate . '! Enjoy the upcoming break.',
                                'icon' => 'fa-calendar-check',
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Ignore in SQLite test environment
            }

        // 2. Fallback to manual theme selection if set
        if (!$activeAccent && isset($seasonalAccents[$activeAccentKey]) && $activeAccentKey !== 'off') {
            $activeAccent = $seasonalAccents[$activeAccentKey];
        }
    @endphp

    @if(!empty($activeAccent) && !empty($activeAccent['banner_css']))
        <!-- Seasonal & Festival Banner Overlay -->
        <div class="py-2 px-4 text-center fs-8 fw-bold z-3 position-relative d-flex align-items-center justify-content-center gap-2 shadow-xs" style="{{ $activeAccent['banner_css'] }}">
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

    <div class="d-flex position-relative">
        <!-- Sidebar Navigation -->
        @include('layouts.sidebar')

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" class="sidebar-backdrop d-none" onclick="toggleSidebar()"></div>

        <!-- Main Content Area -->
        <div id="main-content" class="d-flex flex-column flex-grow-1">
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
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                        <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                        <div class="flex-grow-1 fs-8 fw-medium">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                        <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
                        <div class="flex-grow-1 fs-8 fw-medium">{{ session('error') }}</div>
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
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) {
                sidebar.classList.toggle('show');
                if (backdrop) {
                    backdrop.classList.toggle('d-none', !sidebar.classList.contains('show'));
                }
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            html.setAttribute('data-theme', newTheme === 'dark' ? 'portal-dark' : 'portal-light');
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

        // Global Select2 Searchable Dropdown Initializer Function
        window.initPortalSelect2 = function(context) {
            if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') return;
            var $targets = context ? $(context).find('.select-search, select.select2, select[data-control="select2"]') : $('.select-search, select.select2, select[data-control="select2"]');
            
            $targets.each(function() {
                var $this = $(this);
                var $modal = $this.closest('.modal');
                var placeholderText = $this.attr('data-placeholder') || $this.attr('placeholder') || 'Search & Select...';
                var isRequired = $this.prop('required') || $this.attr('required');

                if ($this.data('select2')) {
                    try { $this.select2('destroy'); } catch(e) {}
                }
                
                $this.select2({
                    width: '100%',
                    placeholder: {
                        id: '',
                        text: placeholderText
                    },
                    allowClear: !isRequired,
                    minimumResultsForSearch: 0,
                    dropdownParent: $modal.length ? $modal : $(document.body)
                });

                // Auto focus search field on open
                $this.off('select2:open').on('select2:open', function() {
                    setTimeout(function() {
                        let searchField = document.querySelector('.select2-container--open .select2-search__field');
                        if (searchField) searchField.focus();
                    }, 50);
                });
            });
        };

        // Run on boot to ensure icons & select2 match attributes
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            updateThemeIcons(currentTheme);
            window.initPortalSelect2();

            // Auto-reveal tab pane containing any server-side validation errors
            const firstError = document.querySelector('.is-invalid, .invalid-feedback:not(:empty)');
            if (firstError && typeof window.revealElementContainer === 'function') {
                window.revealElementContainer(firstError);
                setTimeout(() => {
                    try { firstError.focus(); } catch(err) {}
                }, 100);
            }
        });

        // Native Vanilla JS & jQuery Bootstrap 5 Event Listeners
        document.addEventListener('shown.bs.modal', (e) => window.initPortalSelect2(e.target));
        document.addEventListener('shown.bs.tab', (e) => window.initPortalSelect2(e.target));
        if (typeof $ !== 'undefined') {
            $(document).on('shown.bs.modal shown.bs.tab', function(e) {
                window.initPortalSelect2(e.target);
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
                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(term) ? '' : 'none';
                    });
                });
            }

        // Global Form Submit Lock & Visual Spinner Indicator (Native Event Capturing)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form || form.tagName !== 'FORM') return;
            if (form.getAttribute('data-no-loader') === 'true') return;

            // Pre-validate form and safely reveal any hidden/inactive tabs containing invalid controls
            if (typeof window.validateFormWithTabs === 'function' && !window.validateFormWithTabs(form)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }

            if (form.dataset.isSubmitting === 'true') {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }

            form.dataset.isSubmitting = 'true';

            const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitBtns.forEach(btn => {
                btn.classList.add('disabled');
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.75';
                btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Processing...';
            });
        }, true);

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('button[type="submit"], input[type="submit"]');
            if (!btn) return;
            const form = btn.closest('form');
            if (form && form.dataset.isSubmitting === 'true') {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        }, true);
    </script>

    <!-- Theme Customizer Offcanvas Drawer -->
    @include('layouts.theme_customizer')

    @stack('js')
    @stack('scripts')
</body>
</html>
