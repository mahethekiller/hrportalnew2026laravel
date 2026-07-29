<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HR Portal') - Antigravity</title>

    <!-- Local Vendor CSS Assets -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">
    
    <!-- App CSS stylesheet -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    
    <!-- Pre-load Dark Theme script to prevent UI flash -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">
    <!-- Ambient Glow Background Blobs -->
    <div class="bg-glow-container">
        <div class="bg-glow-blob blob-1"></div>
        <div class="bg-glow-blob blob-2"></div>
    </div>

    <!-- Floating Theme Switcher -->
    <div class="position-fixed top-0 end-0 p-3">
        <button class="btn btn-link text-body-secondary p-0 border-0 theme-toggle-btn fs-5" id="theme-toggle-btn" onclick="toggleTheme()" type="button" title="Toggle theme">
            <i class="fa-solid fa-moon d-none" id="theme-icon-dark"></i>
            <i class="fa-solid fa-sun" id="theme-icon-light"></i>
        </button>
    </div>

    <div class="container" style="max-width: 450px; z-index: 1;">
        <div class="text-center mb-4">
            <a href="/" class="d-flex align-items-center justify-content-center text-decoration-none text-body gap-2">
                <i class="fa-solid fa-plane-departure text-primary fs-2"></i>
                <span class="fs-3 fw-bold">Antigravity HR</span>
            </a>
        </div>
        
        <div class="card shadow-sm border-0 px-4 py-4 rounded-4 bg-body glass-panel">
            @yield('content')
        </div>
    </div>

    <!-- Local JS Scripts -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
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

        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            updateThemeIcons(currentTheme);
        });
    </script>
</body>
</html>
