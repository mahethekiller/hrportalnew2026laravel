/**
 * Executive Precision x Portal Theme Engine
 * Handles dynamic CSS variable injection, color profile switching, font family updates,
 * dark/light/auto mode toggles, and live interactive drawer previews.
 */
(function (window, document) {
    'use strict';

    const COLOR_PROFILES = {
        fern: {
            name: 'Corporate Navy Blue',
            primary_light: '#1E40AF',
            primary_dark: '#3B82F6',
            primary_active: '#1E3A8A',
            primary_wash_light: '#EFF6FF',
            primary_wash_dark: '#172554',
            rgb: '30, 64, 175',
        },
        sapphire: {
            name: 'Sapphire Corporate',
            primary_light: '#1E40AF',
            primary_dark: '#3B82F6',
            primary_active: '#1E3A8A',
            primary_wash_light: '#EFF6FF',
            primary_wash_dark: '#172554',
            rgb: '30, 64, 175',
        },
        violet: {
            name: 'Royal Violet',
            primary_light: '#7C3AED',
            primary_dark: '#A78BFA',
            primary_active: '#6D28D9',
            primary_wash_light: '#F5F3FF',
            primary_wash_dark: '#3B0764',
            rgb: '124, 58, 237',
        },
        ruby: {
            name: 'Ruby Crimson',
            primary_light: '#DC2626',
            primary_dark: '#F87171',
            primary_active: '#B91C1C',
            primary_wash_light: '#FEF2F2',
            primary_wash_dark: '#450A0A',
            rgb: '220, 38, 38',
        },
        cyan: {
            name: 'Ocean Cyan',
            primary_light: '#0891B2',
            primary_dark: '#22D3EE',
            primary_active: '#0E7490',
            primary_wash_light: '#ECFEFF',
            primary_wash_dark: '#164E63',
            rgb: '8, 145, 178',
        },
        amber: {
            name: 'Sunset Amber',
            primary_light: '#D97706',
            primary_dark: '#FBBF24',
            primary_active: '#B45309',
            primary_wash_light: '#FFFBEB',
            primary_wash_dark: '#451A03',
            rgb: '217, 119, 6',
        },
        obsidian: {
            name: 'Midnight Obsidian',
            primary_light: '#1E293B',
            primary_dark: '#94A3B8',
            primary_active: '#0F172A',
            primary_wash_light: '#F8FAFC',
            primary_wash_dark: '#020617',
            rgb: '30, 41, 59',
        },
    };

    const FONT_FAMILIES = {
        inter: '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        outfit: '"Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        roboto: '"Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif',
        plus_jakarta: '"Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        syne_mono: '"Syne Mono", monospace, sans-serif',
        comfortaa: '"Comfortaa", -apple-system, BlinkMacSystemFont, "Segoe UI", cursive, sans-serif',
        fredoka: '"Fredoka", -apple-system, BlinkMacSystemFont, "Segoe UI", cursive, sans-serif',
        sniglet: '"Sniglet", "Comic Sans MS", cursive, sans-serif',
        luckiest_guy: '"Luckiest Guy", cursive, fantasy, sans-serif',
        chewy: '"Chewy", cursive, sans-serif',
        silkscreen: '"Silkscreen", monospace, sans-serif',
        system: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
    };

    /**
     * Optical Metrics & Size Normalization Profiles.
     * Large display, comic, and pixel fonts (Sniglet, Luckiest Guy, Chewy, Silkscreen)
     * have naturally high x-heights and bulky letterforms. These profiles automatically
     * scale the root typography so UI components, tables, and buttons stay balanced.
     */
    const FONT_METRICS = {
        inter: {
            scale: 1.0,
            letterSpacing: 'normal',
            lineHeight: '1.5',
            extraCss: '',
        },
        outfit: {
            scale: 1.0,
            letterSpacing: 'normal',
            lineHeight: '1.5',
            extraCss: '',
        },
        roboto: {
            scale: 1.0,
            letterSpacing: 'normal',
            lineHeight: '1.5',
            extraCss: '',
        },
        plus_jakarta: {
            scale: 1.0,
            letterSpacing: 'normal',
            lineHeight: '1.5',
            extraCss: '',
        },
        syne_mono: {
            scale: 0.92,
            letterSpacing: '-0.02em',
            lineHeight: '1.4',
            extraCss: `
                .badge, .btn-sm { font-size: 0.80rem !important; }
            `,
        },
        comfortaa: {
            scale: 0.94,
            letterSpacing: '-0.01em',
            lineHeight: '1.45',
            extraCss: `
                .badge, .btn-sm { font-size: 0.80rem !important; }
            `,
        },
        fredoka: {
            scale: 0.92,
            letterSpacing: 'normal',
            lineHeight: '1.45',
            extraCss: `
                .badge, .btn-sm { font-size: 0.80rem !important; }
            `,
        },
        sniglet: {
            scale: 0.86,
            letterSpacing: '-0.015em',
            lineHeight: '1.35',
            extraCss: `
                h1 { font-size: 1.85rem !important; }
                h2 { font-size: 1.55rem !important; }
                h3 { font-size: 1.30rem !important; }
                h4 { font-size: 1.15rem !important; }
                h5 { font-size: 1.00rem !important; }
                h6 { font-size: 0.88rem !important; }
                .badge, .btn-sm { font-size: 0.78rem !important; padding: 0.22rem 0.52rem !important; }
                .table td, .table th { font-size: 0.88rem !important; }
                .form-control, .form-select { font-size: 0.88rem !important; }
            `,
        },
        luckiest_guy: {
            scale: 0.76,
            letterSpacing: '0.03em',
            lineHeight: '1.25',
            extraCss: `
                h1 { font-size: 1.75rem !important; }
                h2 { font-size: 1.45rem !important; }
                h3 { font-size: 1.25rem !important; }
                h4 { font-size: 1.08rem !important; }
                h5 { font-size: 0.95rem !important; }
                h6 { font-size: 0.85rem !important; }
                .badge, .btn-sm { font-size: 0.72rem !important; padding: 0.20rem 0.48rem !important; }
                .table td, .table th { font-size: 0.82rem !important; }
                .form-control, .form-select { font-size: 0.84rem !important; }
                .fw-bold, .fw-bolder, strong, b, h1, h2, h3, h4, h5, h6, .btn, .badge {
                    font-weight: 400 !important;
                }
            `,
        },
        chewy: {
            scale: 0.82,
            letterSpacing: '0.01em',
            lineHeight: '1.3',
            extraCss: `
                h1 { font-size: 1.85rem !important; }
                h2 { font-size: 1.55rem !important; }
                h3 { font-size: 1.30rem !important; }
                h4 { font-size: 1.12rem !important; }
                h5 { font-size: 1.00rem !important; }
                h6 { font-size: 0.88rem !important; }
                .badge, .btn-sm { font-size: 0.78rem !important; padding: 0.22rem 0.50rem !important; }
                .table td, .table th { font-size: 0.85rem !important; }
                .form-control, .form-select { font-size: 0.86rem !important; }
            `,
        },
        silkscreen: {
            scale: 0.76,
            letterSpacing: '0.02em',
            lineHeight: '1.4',
            extraCss: `
                h1 { font-size: 1.65rem !important; }
                h2 { font-size: 1.40rem !important; }
                h3 { font-size: 1.20rem !important; }
                h4 { font-size: 1.05rem !important; }
                h5 { font-size: 0.92rem !important; }
                h6 { font-size: 0.82rem !important; }
                .badge, .btn-sm { font-size: 0.70rem !important; padding: 0.18rem 0.45rem !important; }
                .table td, .table th { font-size: 0.80rem !important; }
                .form-control, .form-select { font-size: 0.82rem !important; }
                body, input, button, select, textarea, .card, .table, .navbar, .sidebar {
                    -webkit-font-smoothing: none !important;
                    -moz-osx-font-smoothing: grayscale !important;
                    text-rendering: geometricPrecision !important;
                }
            `,
        },
        system: {
            scale: 1.0,
            letterSpacing: 'normal',
            lineHeight: '1.5',
            extraCss: '',
        },
    };

    const PortalTheme = {
        init: function () {
            this.applyCurrentTheme();

            // System dark mode preference change listener
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    if (localStorage.getItem('portal_theme_mode') === 'auto') {
                        this.applyCurrentTheme();
                    }
                });
            }
        },

        getCurrentConfig: function () {
            const systemDefault = window.SYSTEM_THEME_CONFIG || {};

            return {
                profile: localStorage.getItem('portal_theme_profile') || systemDefault.theme_color_profile || 'sapphire',
                customHex: localStorage.getItem('portal_custom_hex') || systemDefault.custom_primary_hex || '#1E40AF',
                mode: localStorage.getItem('portal_theme_mode') || systemDefault.theme_mode || 'light',
                font: localStorage.getItem('portal_font_family') || systemDefault.font_family || 'inter',
                sidebar: localStorage.getItem('portal_sidebar_style') || systemDefault.sidebar_style || 'default',
            };
        },

        applyCurrentTheme: function () {
            const config = this.getCurrentConfig();

            // 1. Resolve Mode
            let resolvedMode = config.mode;
            if (config.mode === 'auto') {
                resolvedMode = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', resolvedMode);
            document.documentElement.setAttribute('data-theme', resolvedMode === 'dark' ? 'portal-dark' : 'portal-light');

            // 2. Resolve Color Profile
            let profileData = COLOR_PROFILES[config.profile];
            if (!profileData && config.profile === 'custom') {
                profileData = this.generateCustomProfile(config.customHex);
            } else if (!profileData) {
                profileData = COLOR_PROFILES.sapphire;
            }

            const primaryColor = resolvedMode === 'dark' ? profileData.primary_dark : profileData.primary_light;
            const primaryWash = resolvedMode === 'dark' ? profileData.primary_wash_dark : profileData.primary_wash_light;

            // 3. Inject CSS Variables into :root
            const root = document.documentElement;
            root.style.setProperty('--bs-primary', primaryColor);
            root.style.setProperty('--bs-primary-active', profileData.primary_active);
            root.style.setProperty('--bs-primary-light', primaryWash);
            root.style.setProperty('--bs-primary-rgb', profileData.rgb);
            root.style.setProperty('--input-focus-border', primaryColor);

            // Sidebar Variables
            if (config.sidebar === 'accent') {
                root.style.setProperty('--sidebar-bg', primaryColor);
                root.style.setProperty('--sidebar-color', '#ffffff');
                root.style.setProperty('--sidebar-active-bg', 'rgba(255, 255, 255, 0.2)');
                root.style.setProperty('--sidebar-active-color', '#ffffff');
            } else {
                root.style.removeProperty('--sidebar-bg');
                root.style.removeProperty('--sidebar-color');
                root.style.removeProperty('--sidebar-active-bg');
                root.style.removeProperty('--sidebar-active-color');
            }

            // 4. Resolve Font Family & Optical Metrics
            const fontStack = FONT_FAMILIES[config.font] || FONT_FAMILIES.inter;
            const metrics = FONT_METRICS[config.font] || FONT_METRICS.inter;

            root.style.setProperty('--portal-font-family', fontStack);
            root.style.setProperty('--bs-body-font-family', fontStack);
            root.style.setProperty('--bs-font-sans-serif', fontStack);
            root.style.setProperty('--portal-font-scale', metrics.scale);

            // Dynamically inject/update high-priority font override tag
            let fontOverrideEl = document.getElementById('portal-dynamic-font-override');
            if (!fontOverrideEl) {
                fontOverrideEl = document.createElement('style');
                fontOverrideEl.id = 'portal-dynamic-font-override';
                document.head.appendChild(fontOverrideEl);
            }
            fontOverrideEl.textContent = `
                html {
                    font-size: calc(16px * ${metrics.scale}) !important;
                }
                body, input, button, select, textarea, .card, .table, .navbar, .sidebar, 
                h1, h2, h3, h4, h5, h6, p, span, a, label, li, .form-control, .form-select {
                    font-family: ${fontStack} !important;
                    letter-spacing: ${metrics.letterSpacing} !important;
                    line-height: ${metrics.lineHeight} !important;
                }
                .fa, .fas, .far, .fal, .fad, .fa-solid, .fa-regular {
                    font-family: var(--fa-style-family, "Font Awesome 6 Free") !important;
                    letter-spacing: normal !important;
                    line-height: inherit !important;
                }
                .fa-brands, .fab {
                    font-family: var(--fa-style-family-brands, "Font Awesome 6 Brands") !important;
                    letter-spacing: normal !important;
                    line-height: inherit !important;
                }
                ${metrics.extraCss}
            `;

            // Sync dropdowns if present on page
            const fontSelect = document.getElementById('fontFamilySelector');
            if (fontSelect && fontSelect.value !== config.font) {
                fontSelect.value = config.font;
            }
            const themeFontSelect = document.getElementById('themeSettingFontFamily');
            if (themeFontSelect && themeFontSelect.value !== config.font) {
                themeFontSelect.value = config.font;
            }
            const sampleEl = document.getElementById('typographyLiveSample');
            if (sampleEl) {
                sampleEl.style.setProperty('font-family', fontStack, 'important');
                const nameEl = sampleEl.querySelector('.font-preview-name');
                if (nameEl) {
                    nameEl.textContent = `${config.font} (Optical Scale: ${Math.round(metrics.scale * 100)}%)`;
                }
            }
        },

        generateCustomProfile: function (hex) {
            hex = hex.replace('#', '');
            if (hex.length === 3) {
                hex = hex.split('').map(c => c + c).join('');
            }
            const r = parseInt(hex.substring(0, 2), 16) || 30;
            const g = parseInt(hex.substring(2, 4), 16) || 64;
            const b = parseInt(hex.substring(4, 6), 16) || 175;

            return {
                name: 'Custom Profile',
                primary_light: '#' + hex,
                primary_dark: '#' + hex,
                primary_active: '#' + hex,
                primary_wash_light: `rgba(${r}, ${g}, ${b}, 0.12)`,
                primary_wash_dark: `rgba(${r}, ${g}, ${b}, 0.25)`,
                rgb: `${r}, ${g}, ${b}`,
            };
        },

        setThemeMode: function (mode) {
            localStorage.setItem('portal_theme_mode', mode);
            this.applyCurrentTheme();
        },

        setColorProfile: function (profileKey, customHex) {
            localStorage.setItem('portal_theme_profile', profileKey);
            if (customHex) {
                localStorage.setItem('portal_custom_hex', customHex);
            }
            this.applyCurrentTheme();
        },

        setFontFamily: function (fontKey) {
            localStorage.setItem('portal_font_family', fontKey);
            this.applyCurrentTheme();
        },

        setSidebarStyle: function (sidebarStyle) {
            localStorage.setItem('portal_sidebar_style', sidebarStyle);
            this.applyCurrentTheme();
        },

        resetToDefault: function () {
            localStorage.removeItem('portal_theme_profile');
            localStorage.removeItem('portal_custom_hex');
            localStorage.removeItem('portal_theme_mode');
            localStorage.removeItem('portal_font_family');
            localStorage.removeItem('portal_sidebar_style');
            this.applyCurrentTheme();
        }
    };

    window.PortalTheme = PortalTheme;
    document.addEventListener('DOMContentLoaded', () => PortalTheme.init());
    PortalTheme.applyCurrentTheme();

})(window, document);
