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
    <link href="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet">

    <!-- Custom Theme Styling -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    @stack('css')

    <!-- Pre-load Dark Theme script to prevent UI flash -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
</head>
<body class="bg-body-secondary">
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
                    
                    <span class="navbar-brand fw-semibold text-body-emphasis">@yield('page_title', 'Dashboard')</span>
                    
                    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                        <ul class="navbar-nav align-items-center gap-3">
                            <!-- Theme Toggle Button -->
                            <li class="nav-item">
                                <button class="btn btn-link text-body-secondary p-0 border-0 theme-toggle-btn fs-5" id="theme-toggle-btn" onclick="toggleTheme()" type="button" title="Toggle theme">
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
        });
    </script>
    @stack('js')
</body>
</html>
