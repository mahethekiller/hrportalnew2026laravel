# Implementation Plan - Public TEKKEN 7 Showdown Arcade Portal (Standalone & No Login Required)

Integrate a **100% standalone, public, and unauthenticated** event page for **TEKKEN 7: TEEJ SPECIAL SHOWDOWN** in Laravel (`d:\SOFTWARES\xampp82new\hri2k2new\antigravity_portal`). This module requires **zero login / zero authentication**, operating completely separate from the internal HR portal authentication layer.

## User Review Required

> [!IMPORTANT]
> **100% Public Access**: The registration form, tournament queue, live bracket status switcher, and CSV export will be open and accessible via direct URLs without needing user credentials or session authentication (`auth` middleware bypassed completely).
> **Standalone Layout**: The view will be self-contained and will not rely on `layouts.app` or authenticated navigation sidebars.
> **Zero CDN & Zero Tailwind**: Built purely with Bootstrap 5.3, custom Vanilla CSS arcade/cyberpunk styling, inline SVGs, and native browser Web Audio API for arcade sounds.

---

## Proposed Changes

### Database Layer

#### [NEW] [2026_08_13_000000_create_tekken_registrations_table.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/database/migrations/2026_08_13_000000_create_tekken_registrations_table.php)
- Creates `tekken_registrations` table schema:
  - `id` (bigIncrements)
  - `full_name` (string)
  - `department` (string)
  - `festive_green` (boolean, default false - T-shirt flag)
  - `matches` (integer, default 1)
  - `fee_paid` (decimal 8,2)
  - `utr_number` (string)
  - `status` (enum: `'in_queue'`, `'playing'`, `'completed'`, default `'in_queue'`)
  - `timestamps`

### Application Backend (Laravel - Public Controllers & Routes)

#### [NEW] [TekkenRegistration.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/app/Models/TekkenRegistration.php)
- Eloquent Model for `tekken_registrations`.
- `$fillable` array for mass assignment, `$casts` for `festive_green`, `fee_paid`, and `matches`.
- Helper badge class accessors (`status_badge_class`, `status_label`).

#### [NEW] [TekkenShowdownController.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/app/Http/Controllers/TekkenShowdownController.php)
- Unauthenticated public endpoints handling:
  - `index()`: Renders standalone arcade view with current queue list.
  - `store(Request $request)`: Validates input, calculates fee (`matches * 20`), saves registration, returns JSON for dynamic AJAX injection.
  - `updateStatus(Request $request, $id)`: Cycles match state (`in_queue` -> `playing` -> `completed`) via AJAX without requiring login.
  - `destroy($id)`: Removes registration record via AJAX.
  - `export()`: Generates and downloads `tekken7_registrations.csv` file.

#### [MODIFY] [web.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/routes/web.php)
- Registers public routes **outside** the `Route::middleware('auth')` group:
  - `GET  /tekken-showdown` -> `TekkenShowdownController@index` (named `tekken.index`)
  - `POST /tekken-showdown/register` -> `TekkenShowdownController@store` (named `tekken.store`)
  - `PATCH /tekken-showdown/status/{id}` -> `TekkenShowdownController@updateStatus` (named `tekken.status`)
  - `DELETE /tekken-showdown/{id}` -> `TekkenShowdownController@destroy` (named `tekken.destroy`)
  - `GET  /tekken-showdown/export` -> `TekkenShowdownController@export` (named `tekken.export`)

### Frontend Layer (Standalone Blade View & Cyberpunk Esports UI)

#### [NEW] [showdown.blade.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/resources/views/tekken/showdown.blade.php)
- Completely independent single-page Blade layout (`<!DOCTYPE html>...</html>`).
- Features:
  - Dynamic fee auto-calculator (₹20 per match).
  - Built-in canvas QR code renderer for quick payment scanning (`upi://pay?pa=7382218413@ybl&pn=Tekken%20Entry...`).
  - Web Audio Synthesizer sound effects (Arcade coin drop, start fight sound, button click feedback).
  - Dynamic Queue Table with real-time AJAX row insert, status badge toggling, search filter, and player counter.
  - Fully responsive, high-contrast dark theme with neon accents (`#ff0055`, `#00f0ff`, `#ffe600`), 100% compatible with both light and dark display preferences.

---

## Verification Plan

### Automated / Command Verification
1. Migration execution:
   ```bash
   php artisan migrate
   ```
2. Verify public route exposure without auth middleware:
   ```bash
   php artisan route:list --path=tekken-showdown
   ```

### Manual Verification
1. Access `http://localhost:8000/tekken-showdown` directly in an incognito browser window (without signing into HR Portal).
2. Confirm the page renders cleanly without redirecting to `/login`.
3. Submit a new registration (e.g. Player "Kazuya Mishima", Dept "IT", 3 Matches, UTR "123456789012").
4. Verify dynamic fee updates to ₹60 and player is added instantly to the live arcade queue table.
5. Test status cycling (`In Queue` -> `Playing Now` -> `Completed`) via AJAX.
6. Test CSV download export without authentication.

