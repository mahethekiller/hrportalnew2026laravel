---
name: Antigravity HR Portal
description: Enterprise workforce & HR command center built with high-density executive precision
colors:
  primary: "#1E40AF"
  primary-active: "#1E3A8A"
  primary-wash: "#EFF6FF"
  primary-dark: "#3B82F6"
  secondary: "#475569"
  success: "#10B981"
  success-wash: "#ECFDF5"
  danger: "#EF4444"
  danger-wash: "#FEF2F2"
  warning: "#F59E0B"
  warning-wash: "#FFFBEB"
  info: "#06B6D4"
  info-wash: "#ECFEFF"
  violet: "#7C3AED"
  violet-wash: "#F5F3FF"
  purple: "#9333EA"
  purple-wash: "#F3E8FF"
  emerald: "#059669"
  emerald-wash: "#ECFDF5"
  forest-green: "#2F7A63"
  saffron: "#FF671F"
  deep-green: "#046A38"
  pink: "#EC4899"
  neutral-canvas: "#F8FAFC"
  neutral-surface: "#FFFFFF"
  neutral-surface-raised: "#FFFFFF"
  neutral-ink: "#0F172A"
  neutral-slate: "#475569"
  neutral-line: "#E2E8F0"
  dark-canvas: "#0B0F19"
  dark-surface: "#111827"
  dark-surface-raised: "#1F2937"
  dark-ink: "#F9FAFB"
  dark-slate: "#9CA3AF"
  dark-line: "#1F2937"
typography:
  display:
    fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
    fontSize: "1.75rem"
    fontWeight: 700
    lineHeight: 1.2
    letterSpacing: "-0.02em"
  metric:
    fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
    fontSize: "24px"
    fontWeight: 700
    lineHeight: 1.2
    letterSpacing: "-0.01em"
  headline:
    fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
    fontSize: "1.4rem"
    fontWeight: 600
    lineHeight: 1.25
    letterSpacing: "-0.015em"
  title:
    fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
    fontSize: "1.15rem"
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: "-0.01em"
  body:
    fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
    fontSize: "0.85rem"
    fontWeight: 400
    lineHeight: 1.45
    letterSpacing: "-0.005em"
  label:
    fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
    fontSize: "0.7rem"
    fontWeight: 600
    lineHeight: 1
    letterSpacing: "0.04em"
rounded:
  sm: "4px"
  md: "6px"
  default: "8px"
  lg: "10px"
  xl: "12px"
  pill: "50px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#FFFFFF"
    rounded: "{rounded.default}"
    padding: "0.45rem 0.95rem"
  button-primary-hover:
    backgroundColor: "{colors.primary-active}"
    textColor: "#FFFFFF"
    rounded: "{rounded.default}"
    padding: "0.45rem 0.95rem"
  button-light-secondary:
    backgroundColor: "#F1F5F9"
    textColor: "#334155"
    rounded: "{rounded.default}"
    padding: "0.45rem 0.95rem"
  card:
    backgroundColor: "{colors.neutral-surface}"
    rounded: "{rounded.lg}"
    padding: "1.15rem"
  input:
    backgroundColor: "{colors.neutral-surface}"
    textColor: "{colors.neutral-ink}"
    rounded: "{rounded.default}"
    padding: "0.45rem 0.85rem"
---

# Design System: Antigravity HR Portal

## Overview

**Creative North Star: "Executive Command Center"**

The Antigravity HR Portal is an enterprise workforce management platform engineered for executive precision, dense operational efficiency, and instantaneous cognitive scanning. Designed with an austere, high-contrast visual architecture inspired by Metronic and modern executive terminals, it balances data density with visual calm. The interface prioritizes structured tabular data, instant visual status recognition, and frictionless multi-company organizational management.

Rather than decorative excess or novelty animations, the system draws its visual authority from razor-sharp typography, deliberate 1px structural borders, and purposeful contrast tokens. Both Light Mode (`#F8FAFC` canvas) and Dark Mode (`#0B0F19` canvas via `data-bs-theme="dark"`) are first-class citizens, ensuring seamless 24/7 readability for HR administrators, managers, and executives.

**Key Characteristics:**
- **High-Density Information Architecture**: Compact typography (0.85rem base), structured 6-8px padding rhythms, and tabular numeric figures (`tabular-nums`) optimized for large employee rosters, payroll matrices, and recruitment pipelines.
- **Bi-Modal High-Contrast Legibility**: Full dynamic contrast compliance across all cards, tables, select2 pills, and modals in both Light and Dark themes with zero low-contrast text.
- **Ergonomic First-Column Table Actions**: Icon-only action buttons consolidated in the first table column with explicit 6px spacing for predictable, rapid administrative interaction.
- **Zero-Flicker Multi-Company Disambiguation**: Deep contextual clarity across departments, designations, and employee branches.

## Colors

The palette is rooted in an authoritative Corporate Navy Blue primary accent flanked by cool slate neutrals and vivid semantic status washes that guide attention without causing visual fatigue.

### Primary
- **Corporate Navy Blue** (#1E40AF / dark #3B82F6): Primary brand anchor used for primary action buttons, active navigation markers, key focal points, and branded badges. In dark mode, lightens to high-visibility Cobalt Blue (#3B82F6).
- **Navy Deep Active** (#1E3A8A / dark #2563EB): Hover and pressed states for primary interactive elements.
- **Corporate Blue Wash** (#EFF6FF / dark #172554): Subtle tinted background for active sidebar links, primary table row selections, and selected tabs.

### Secondary
- **Executive Slate** (#475569 / dark #9CA3AF): Used for secondary buttons, non-active menu items, auxiliary icons, and structural metadata labels.

### Semantic Accents
- **Emerald Green** (#10B981 / wash #ECFDF5): Denotes approved leaves, active payroll statuses, active employee badges, and success confirmations.
- **Crimson Red** (#EF4444 / wash #FEF2F2): Indicates rejected workflows, terminated contracts, overdue tickets, and destructive actions.
- **Amber Marigold** (#F59E0B / wash #FFFBEB): Represents pending approvals, warning alerts, probation periods, and review-pending submissions.
- **Cyan Harbor** (#06B6D4 / wash #ECFEFF): Reserved for informational notices, onboarding progress markers, and scheduled interview indicators.

### Theme Profiles & Seasonal Accents
- **Royal Violet** (#7C3AED / wash #F5F3FF): Executive palette profile for management analytics and leadership dashboards.
- **Imperial Purple** (#9333EA / wash #F3E8FF): Specialized training, rewards, and recognition status chips.
- **Emerald & Forest Green** (#059669 / #2F7A63): Sustainability, eco-initiatives, and health/wellness badge categories.
- **Holiday & Festive Accents** (#FF671F Saffron, #046A38 Deep Green, #EC4899 Festive Pink): Automated seasonal greeting banners synchronized with official public holidays.

### Neutral
- **Page Canvas** (#F8FAFC / dark #0B0F19): Structural backplane for the main viewport background.
- **Surface Card** (#FFFFFF / dark #111827): Foreground container for metric cards, data tables, and modal dialogs.
- **Surface Raised** (#FFFFFF / dark #1F2937): Elevated layers such as dropdowns, popovers, select2 overlays, and nested list containers.
- **Primary Ink** (#0F172A / dark #F9FAFB): High-contrast headline and body text color.
- **Secondary Muted** (#475569 / dark #94A3B8): Subtitles, helper text, and secondary cell metadata.
- **Structural Line** (#E2E8F0 / dark #1F2937): 1px perimeter border for cards, table rows, and input fields.

### Named Rules
**The Wash Pair Rule.** Semantic status colors must never be rendered as solid high-saturation backgrounds behind long text. Always pair a vibrant text accent with its corresponding 10-15% opacity pastel wash (`.badge-light-primary`, `.badge-light-success`, `.badge-light-danger`).

**The Zero Hardcoded White Rule.** Never use hardcoded `#FFFFFF` or `bg-white` classes in template markup without theme-aware tokens (`--card-bg`, `bg-body`, or `[data-bs-theme="dark"]` overrides). Every surface must respond instantly to theme changes.

## Typography

**Primary System UI Font:** `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif`  
**Display / Editorial Font:** `'Fraunces', Georgia, serif` (used selectively for executive banner titles)  
**Tabular Figures / Mono Font:** `'IBM Plex Mono', monospace` (used for financial metrics, employee IDs, and timestamp figures)

**Character:** Ultra-crisp, authoritative, and compact. Engineered for rapid data scanning across dense operational screens with zero visual fuzziness.

### Hierarchy
- **Display** (Bold 700, 1.75rem / 28px, line-height 1.2, letter-spacing -0.02em): Top-level portal hero headings and executive reporting titles.
- **Headline** (SemiBold 600, 1.4rem / 22.4px, line-height 1.25, letter-spacing -0.015em): Section titles and primary dashboard card headers.
- **Title** (SemiBold 600, 1.15rem / 18.4px, line-height 1.3, letter-spacing -0.01em): Module subtitles, modal titles, and metric card labels.
- **Body** (Regular 400, 0.85rem / 13.6px, line-height 1.45, letter-spacing -0.005em): Standard table cell content, form labels, descriptions, and comments.
- **Label** (SemiBold 600, 0.7rem / 11.2px, line-height 1, letter-spacing 0.04em, uppercase): Table headers, badge labels, and metadata captions.

### Named Rules
**The Tabular Figure Rule.** All currency amounts, leave day counts, attendance percentages, and metric figures must enforce `font-variant-numeric: tabular-nums` to prevent column jitter and align figures strictly along vertical axes.

## Layout

The spatial model uses a fixed 250px left navigation sidebar with a flexible, fluid-responsive main content viewport. Layout spacing relies on an 8px grid rhythm with compact vertical padding for high data density.

- **Sidebar Width:** 250px fixed navigation rail with collapsible accordion submenus and custom slim scrollbars.
- **Content Viewport:** Fluid width with responsive margins (`padding: 1.25rem 1.5rem` on desktop).
- **Grid Structure:** Bootstrap 5.3 12-column responsive grid with consistent gutter spacing (`g-3` or `g-4`).
- **Modal Viewports:** Large multi-input forms and studio workflows must utilize `.modal-xl` with a 2-Column Side-by-Side Widescreen Studio Layout (`col-lg-5` parameters on the left, `col-lg-7` preview canvas on the right).

## Elevation & Depth

The system uses a "Crisp & Subdued" elevation model. Surfaces are fundamentally planar and defined by clean 1px borders (`#E2E8F0` / `#1F2937`) at rest. Shadows serve exclusively to communicate dynamic elevation, transient hover feedback, and spatial overlays.

### Shadow Vocabulary
- **Card Rest** (`box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.05), 0px 1px 2px 0px rgba(0, 0, 0, 0.03)` / dark: `0px 4px 20px rgba(0, 0, 0, 0.3)`): Subtle ground plane anchor for dashboard metric cards and data containers.
- **Card Hover** (`box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05)`): Gentle lift confirming interactive card affordance.
- **Button Primary** (`box-shadow: 0 2px 4px rgba(79, 70, 229, 0.25)`): High-tactile micro-shadow accentuating primary action buttons.
- **Dropdown & Modal Overlays** (`box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15)` / dark: `0px 12px 36px rgba(0, 0, 0, 0.6)`): High-depth elevation floating above the workspace.

### Named Rules
**The Border-First Elevation Rule.** Elevation must never substitute for perimeter definition. Every elevated card, dropdown, and modal must retain its 1px structural border regardless of shadow presence.

## Shapes

Forms are geometric, refined, and consistent across all modules:
- **Small Elements & Badges:** 4px - 6px radius (`rounded-1` to `rounded-2`) for compact badges, tags, and small icon buttons.
- **Interactive Controls & Inputs:** 8px radius (`rounded-2`) for buttons, form inputs, and select2 dropdown containers.
- **Content Containers & Cards:** 10px radius (`border-radius: 10px`) for all cards and summary widgets.
- **Modals:** 12px radius (`border-radius: 12px`) for modal dialogs and alert popups.
- **Pills:** 50px pill radius (`rounded-pill`) reserved exclusively for numeric count counters and user avatar chips.

## Components

### Buttons
- **Shape:** Refined 8px radius (`border-radius: 8px`), compact vertical padding (`0.45rem 0.95rem`), 500 font weight.
- **Primary:** Corporate Navy Blue background (`#1E40AF`), white text (`#FFFFFF`), micro-shadow.
- **Hover / Focus:** Transitions smoothly (0.15s) to `#1E3A8A` with an intensified hover shadow.
- **Secondary / Subtle:** Light Slate (`#F1F5F9`, border `#CBD5E1`, text `#334155`), transitioning to `#E2E8F0` on hover. In dark mode, adapts to `#334155` background with `#F8FAFC` text.
- **Table Action Buttons:** Compact icon-only buttons (`btn-sm px-2.5 rounded-2`) wrapped inside `<div class="d-inline-flex align-items-center" style="gap: 6px;">` in Column 1.

### Cards & Metric Containers
- **Corner Style:** 10px radius with 1px border (`var(--card-border)`).
- **Background:** Crisp white (`#FFFFFF`) in light mode; Deep Charcoal (`#111827`) in dark mode.
- **Card Header:** Transparent background with clean 1px border-bottom (`padding: 0.85rem 1.15rem`), flex layout with bold 0.95rem card titles.
- **Card Body:** Generous 1.15rem padding with zero wasted padding margins.

### Inputs & Form Fields
- **Style:** 8px radius (`border-radius: 8px`), 1px border (`#CBD5E1` / dark `#1F2937`), 0.45rem 0.85rem padding, 0.85rem font size.
- **Focus:** Sharp primary border tint (`#1E40AF` / `#3B82F6`) with a 3px soft focus ring (`box-shadow: 0 0 0 3px rgba(27, 132, 255, 0.15)`).
- **Searchable Select2:** Native integration with live search filtering, company disambiguation labels, and dark mode pill color inversions.

### Data Tables
- **Header (`th`):** Uppercase, bold 700 weight, compact 0.72rem font size with 0.04em letter spacing, slate background (`#F1F5F9` / dark `#1E293B`), 2px bottom border.
- **Cells (`td`):** 0.85rem font size, vertical alignment centered, 1px bottom border (`#E2E8F0` / dark `#334155`).
- **Row Hover:** Subtle wash highlight (`rgba(27, 132, 255, 0.04)` / dark `rgba(255, 255, 255, 0.04)`).
- **Action Column:** Always positioned in Column 1 with descriptive HTML `title` tooltips.

### Badges & Status Pills
- **Style:** Compact inline-flex pill with 6px radius, bold 600 weight, 0.75rem font size, 0.3rem 0.6rem padding.
- **Color Pairing:** High-contrast subtle wash backgrounds paired with strong text color (`badge-light-primary`, `badge-light-success`, `badge-light-danger`, `badge-light-warning`).

## Do's and Don'ts

### Do:
- **Do** place data table action buttons in **Column 1** as compact, icon-only buttons with explicit 6px gaps (`style="gap: 6px;"`).
- **Do** format numbers, dates, salaries, and counts with `tabular-nums` for rock-solid tabular alignment.
- **Do** append company context to Department and Designation dropdowns (`$dept->department_name . ' (Company: ' . $dept->company->name . ')'`).
- **Do** provide instant loading feedback on submit buttons (`submitWithLoader(this)` or `.btn-loader` with `<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Processing...`).
- **Do** ensure every interactive element and text label maintains high-contrast visibility under both light mode and `data-bs-theme="dark"`.

### Don't:
- **Don't** use glued `.btn-group` containers for table row actions; always preserve individual rounded borders with distinct 6px spacing.
- **Don't** hardcode light-only background colors (`bg-white`, `#F1F5F9`) or dark-only text colors (`#0F172A`) without `[data-bs-theme="dark"]` override mappings.
- **Don't** place an unstyled `<form>` tag directly between `.modal-dialog` and `.modal-content` (violates Bootstrap 5 modal hierarchy and collapses modal widths).
- **Don't** output raw un-decoded HTML entities or HTML markup inside `<textarea>` inputs or plain text table snippets.
- **Don't** introduce external CDN scripts or Tailwind CSS utilities; stick strictly to local Bootstrap 5.3 assets and `assets/css/app.css`.
