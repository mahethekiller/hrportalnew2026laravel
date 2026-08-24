@extends('layouts.app')

@section('title', 'Global Theme & Brand Settings')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">Global Theme & Brand Settings</h1>
            <p class="text-body-secondary fs-7 mb-0">Configure system-wide default color profiles, typography fonts, seasonal festival accents, and branding style.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm fw-bold" data-bs-toggle="offcanvas" data-bs-target="#themeCustomizerDrawer">
                <i class="fa-solid fa-palette me-1"></i> Live Theme Customizer
            </button>
            <a href="{{ route('system-settings.index') }}" class="btn btn-light-secondary btn-sm fw-semibold">
                <i class="fa-solid fa-sliders me-1"></i> System Settings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.theme.update') }}">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Global Color Profile & Typography -->
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 pt-4 bg-body-tertiary">
                        <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                            <i class="fa-solid fa-swatchbook text-primary me-2"></i> System Default Brand Color Palette
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-body-secondary fs-7 mb-3">Select the default primary brand color profile applied to guest/login pages and new employee accounts.</p>

                        <div class="row g-3 mb-4">
                            @foreach($colorProfiles as $key => $prof)
                            <div class="col-md-6">
                                <div class="form-check custom-option p-3 border rounded {{ ($themeConfig['theme_color_profile'] ?? 'fern') === $key ? 'border-primary bg-body-tertiary' : '' }}">
                                    <input class="form-check-input" type="radio" name="theme_color_profile" id="profRadio_{{ $key }}" value="{{ $key }}" {{ ($themeConfig['theme_color_profile'] ?? 'fern') === $key ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center gap-2 fw-semibold fs-8 text-body-emphasis" for="profRadio_{{ $key }}">
                                        <span class="rounded-circle d-inline-block" style="width: 20px; height: 20px; background-color: {{ $prof['color'] }};"></span>
                                        {{ $prof['name'] }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Custom Primary Hex Option -->
                        <div class="p-3 rounded border bg-body-tertiary mb-3">
                            <label class="form-label fs-8 fw-bold text-body-emphasis">Custom Primary Hex Color</label>
                            <div class="d-flex gap-2">
                                <input type="color" class="form-control form-control-color form-control-sm" name="custom_primary_hex" value="{{ $themeConfig['custom_primary_hex'] ?? '#2F7A63' }}">
                                <input type="text" class="form-control form-control-sm font-monospace fs-8" value="{{ $themeConfig['custom_primary_hex'] ?? '#2F7A63' }}" placeholder="#2F7A63">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pt-4 bg-body-tertiary">
                        <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                            <i class="fa-solid fa-font text-info me-2"></i> System Default Typography Font Family
                        </h3>
                    </div>
                    <div class="card-body">
                        <label class="form-label fs-8 fw-semibold">Select Portal Font Family</label>
                        <select name="font_family" class="form-select form-select-sm mb-3">
                            @foreach($fontFamilies as $fKey => $font)
                                <option value="{{ $fKey }}" {{ ($themeConfig['font_family'] ?? 'inter') === $fKey ? 'selected' : '' }}>
                                    {{ $font['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="p-3 bg-body-tertiary rounded border fs-7">
                            <span class="fw-bold">Typography Live Sample:</span>
                            <p class="mb-0 text-body-secondary">The quick brown fox jumps over the lazy dog. 1234567890</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Seasonal Accents & Layout Defaults -->
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 pt-4 bg-body-tertiary">
                        <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                            <i class="fa-solid fa-wand-magic-sparkles text-warning me-2"></i> Seasonal & Festival Theme Accents
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-body-secondary fs-7 mb-3">Enable celebratory festive header banners for upcoming holidays and corporate occasions.</p>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Active Seasonal Accent</label>
                            <select name="seasonal_accent" class="form-select form-select-sm">
                                @foreach($seasonalAccents as $sKey => $sAcc)
                                    <option value="{{ $sKey }}" {{ ($themeConfig['seasonal_accent'] ?? 'off') === $sKey ? 'selected' : '' }}>
                                        {{ $sAcc['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 pt-4 bg-body-tertiary">
                        <h3 class="card-title fw-bold text-body-emphasis fs-6 mb-0">
                            <i class="fa-solid fa-laptop text-success me-2"></i> Default Mode & Sidebar Style
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Default Theme Mode</label>
                            <select name="theme_mode" class="form-select form-select-sm">
                                <option value="light" {{ ($themeConfig['theme_mode'] ?? 'light') === 'light' ? 'selected' : '' }}>Light Mode (Classic)</option>
                                <option value="dark" {{ ($themeConfig['theme_mode'] ?? '') === 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                <option value="auto" {{ ($themeConfig['theme_mode'] ?? '') === 'auto' ? 'selected' : '' }}>System Auto Sync</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-8 fw-semibold">Default Sidebar Accent Style</label>
                            <select name="sidebar_style" class="form-select form-select-sm">
                                <option value="default" {{ ($themeConfig['sidebar_style'] ?? 'default') === 'default' ? 'selected' : '' }}>Default Surface Background</option>
                                <option value="accent" {{ ($themeConfig['sidebar_style'] ?? '') === 'accent' ? 'selected' : '' }}>Primary Color Accent</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4 w-100 py-2">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Global Theme Configurations
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
