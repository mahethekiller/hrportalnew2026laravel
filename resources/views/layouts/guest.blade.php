<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="portal-light" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Login') - {{ $systemSetting->application_name ?? 'Antigravity HR' }}</title>

    <!-- Tailwind CSS 4 & daisyUI 5 (Local Vite Bundle) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Local Vendor CSS Assets -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">
    
    <!-- App CSS stylesheet -->
    <link href="{{ asset('assets/css/app.css') }}?v={{ @filemtime(public_path('assets/css/app.css')) }}" rel="stylesheet">
    
    @stack('css')

    <!-- Pre-load Dark Theme script & Theme Engine -->
    @php
        $systemThemeConfig = app(\App\Services\ThemeService::class)->getThemeConfig();
    @endphp
    <script>
        window.SYSTEM_THEME_CONFIG = @json($systemThemeConfig);
    </script>
    <script src="{{ asset('assets/js/theme-engine.js') }}?v={{ @filemtime(public_path('assets/js/theme-engine.js')) }}"></script>
    <script>
        (function () {
            const savedTheme = localStorage.getItem('portal_theme_mode') || localStorage.getItem('theme') || 'light';
            const resolved = (savedTheme === 'auto')
                ? ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light')
                : savedTheme;
            document.documentElement.setAttribute('data-bs-theme', resolved);
            document.documentElement.setAttribute('data-theme', resolved === 'dark' ? 'portal-dark' : 'portal-light');
        })();
    </script>
</head>
<body class="min-vh-100 p-0 m-0 overflow-x-hidden">
    @hasSection('custom_layout')
        @yield('custom_layout')
    @else
        <div class="d-flex align-items-center justify-content-center min-vh-100 py-5">
            <!-- Ambient Glow Background Blobs -->
            <div class="bg-glow-container">
                <div class="bg-glow-blob blob-1"></div>
                <div class="bg-glow-blob blob-2"></div>
            </div>

            <!-- Floating Theme Switcher -->
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 99;">
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 theme-toggle-btn shadow-xs" onclick="togglePortalTheme()" type="button" title="Toggle theme">
                    <i class="fa-solid fa-moon me-1 d-none" id="theme-icon-dark"></i>
                    <i class="fa-solid fa-sun me-1" id="theme-icon-light"></i>
                    <span id="theme-label" class="fs-9 fw-semibold">Theme</span>
                </button>
            </div>

            <div class="container" style="max-width: 450px; z-index: 1;">
                <div class="text-center mb-4">
                    <a href="/" class="d-inline-flex align-items-center justify-content-center text-decoration-none">
                        <img src="{{ asset('assets/images/portal_logo_transparent.png') }}" alt="{{ $systemSetting->application_name ?? 'i2u2 HR Portal' }}" style="max-height: 54px; width: auto; object-fit: contain;">
                    </a>
                </div>
                
                <div class="card shadow-sm border-0 px-4 py-4 rounded-4 bg-body glass-panel">
                    @yield('content')
                </div>
            </div>
        </div>
    @endif

    <!-- Local JS Scripts -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        window.submitWithLoader = function(btn) {
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.setAttribute('data-original-html', originalHtml);
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Signing In...';
            const form = btn.closest('form');
            if (form && !form.dataset.submitted) {
                form.dataset.submitted = 'true';
                form.submit();
            }
        };

        function togglePortalTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            html.setAttribute('data-theme', newTheme === 'dark' ? 'portal-dark' : 'portal-light');
            localStorage.setItem('portal_theme_mode', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcons(newTheme);
        }

        function updateThemeIcons(theme) {
            const darkIcons = document.querySelectorAll('.theme-icon-dark, #theme-icon-dark');
            const lightIcons = document.querySelectorAll('.theme-icon-light, #theme-icon-light');
            if (theme === 'dark') {
                darkIcons.forEach(el => el.classList.remove('d-none'));
                lightIcons.forEach(el => el.classList.add('d-none'));
            } else {
                darkIcons.forEach(el => el.classList.add('d-none'));
                lightIcons.forEach(el => el.classList.remove('d-none'));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateThemeIcons(currentTheme);
        });
    </script>
    @stack('scripts')
</body>
</html>
