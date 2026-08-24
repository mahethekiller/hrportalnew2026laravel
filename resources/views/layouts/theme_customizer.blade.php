<!-- Theme & Color Profile Customization Offcanvas Drawer -->
<div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="themeCustomizerDrawer" aria-labelledby="themeCustomizerDrawerLabel">
    <div class="offcanvas-header bg-body-tertiary border-bottom py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-palette text-primary fs-5"></i>
            <h5 class="offcanvas-title fw-bold text-body-emphasis fs-6" id="themeCustomizerDrawerLabel">Theme & Color Profiles</h5>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-4">
        <!-- 1. Color Profiles Grid -->
        <div class="mb-4">
            <label class="form-label fw-bold text-body-emphasis fs-7 mb-2 d-block">
                <i class="fa-solid fa-swatchbook me-1 text-primary"></i> Primary Color Palette
            </label>
            <p class="text-body-secondary fs-8 mb-3">Select a curated color profile to personalize the portal theme.</p>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 p-2 text-start d-flex align-items-center gap-2 border shadow-xs" onclick="PortalTheme.setColorProfile('fern')">
                        <span class="rounded-circle d-inline-block" style="width: 18px; height: 18px; background-color: #2F7A63;"></span>
                        <span class="fs-8 fw-semibold text-body-emphasis">Fern Emerald</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 p-2 text-start d-flex align-items-center gap-2 border shadow-xs" onclick="PortalTheme.setColorProfile('sapphire')">
                        <span class="rounded-circle d-inline-block" style="width: 18px; height: 18px; background-color: #2563EB;"></span>
                        <span class="fs-8 fw-semibold text-body-emphasis">Sapphire Blue</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 p-2 text-start d-flex align-items-center gap-2 border shadow-xs" onclick="PortalTheme.setColorProfile('violet')">
                        <span class="rounded-circle d-inline-block" style="width: 18px; height: 18px; background-color: #7C3AED;"></span>
                        <span class="fs-8 fw-semibold text-body-emphasis">Royal Violet</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 p-2 text-start d-flex align-items-center gap-2 border shadow-xs" onclick="PortalTheme.setColorProfile('ruby')">
                        <span class="rounded-circle d-inline-block" style="width: 18px; height: 18px; background-color: #DC2626;"></span>
                        <span class="fs-8 fw-semibold text-body-emphasis">Ruby Crimson</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 p-2 text-start d-flex align-items-center gap-2 border shadow-xs" onclick="PortalTheme.setColorProfile('cyan')">
                        <span class="rounded-circle d-inline-block" style="width: 18px; height: 18px; background-color: #0891B2;"></span>
                        <span class="fs-8 fw-semibold text-body-emphasis">Ocean Cyan</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 p-2 text-start d-flex align-items-center gap-2 border shadow-xs" onclick="PortalTheme.setColorProfile('amber')">
                        <span class="rounded-circle d-inline-block" style="width: 18px; height: 18px; background-color: #D97706;"></span>
                        <span class="fs-8 fw-semibold text-body-emphasis">Sunset Amber</span>
                    </button>
                </div>
                <div class="col-12">
                    <button type="button" class="btn btn-outline-secondary w-100 p-2 text-start d-flex align-items-center gap-2 border shadow-xs" onclick="PortalTheme.setColorProfile('obsidian')">
                        <span class="rounded-circle d-inline-block" style="width: 18px; height: 18px; background-color: #1E293B;"></span>
                        <span class="fs-8 fw-semibold text-body-emphasis">Midnight Obsidian (Monochrome)</span>
                    </button>
                </div>
            </div>

            <!-- Custom HEX Picker -->
            <div class="p-3 bg-body-tertiary rounded border">
                <label class="form-label fs-8 fw-semibold text-body-emphasis mb-1">Custom Primary Hex Color</label>
                <div class="d-flex gap-2">
                    <input type="color" class="form-control form-control-color form-control-sm" id="customColorPicker" value="#2F7A63" onchange="PortalTheme.setColorProfile('custom', this.value)">
                    <input type="text" class="form-control form-control-sm font-monospace fs-8" id="customColorHexInput" value="#2F7A63" placeholder="#2F7A63" onchange="PortalTheme.setColorProfile('custom', this.value)">
                </div>
            </div>
        </div>

        <hr class="my-4">

        <!-- 2. Typography Font Family Selector -->
        <div class="mb-4">
            <label class="form-label fw-bold text-body-emphasis fs-7 mb-2 d-block">
                <i class="fa-solid fa-font me-1 text-info"></i> Typography Font Family
            </label>
            <select class="form-select form-select-sm fs-8" id="fontFamilySelector" onchange="PortalTheme.setFontFamily(this.value)">
                <option value="inter">Inter / Executive Clean (Default)</option>
                <option value="outfit">Outfit / Geometric Premium</option>
                <option value="roboto">Roboto / Precision Technical</option>
                <option value="plus_jakarta">Plus Jakarta Sans / Friendly Modern</option>
            </select>
        </div>

        <hr class="my-4">

        <!-- 3. Theme Mode (Light / Dark / Auto) -->
        <div class="mb-4">
            <label class="form-label fw-bold text-body-emphasis fs-7 mb-2 d-block">
                <i class="fa-solid fa-circle-half-stroke me-1 text-warning"></i> Theme Mode
            </label>
            <div class="btn-group w-100" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="PortalTheme.setThemeMode('light')">
                    <i class="fa-solid fa-sun me-1 text-warning"></i> Light
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="PortalTheme.setThemeMode('dark')">
                    <i class="fa-solid fa-moon me-1 text-primary"></i> Dark
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="PortalTheme.setThemeMode('auto')">
                    <i class="fa-solid fa-laptop me-1 text-success"></i> Auto
                </button>
            </div>
        </div>

        <hr class="my-4">

        <!-- 4. Sidebar Layout Style -->
        <div class="mb-4">
            <label class="form-label fw-bold text-body-emphasis fs-7 mb-2 d-block">
                <i class="fa-solid fa-table-columns me-1 text-success"></i> Sidebar Accent Style
            </label>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="PortalTheme.setSidebarStyle('default')">
                    Default
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="PortalTheme.setSidebarStyle('accent')">
                    Accent Color
                </button>
            </div>
        </div>
    </div>

    <div class="offcanvas-footer p-3 bg-body-tertiary border-top text-center">
        <button type="button" class="btn btn-sm btn-light-secondary w-100 fw-bold" onclick="PortalTheme.resetToDefault()">
            <i class="fa-solid fa-rotate-left me-1"></i> Reset to System Defaults
        </button>
    </div>
</div>
