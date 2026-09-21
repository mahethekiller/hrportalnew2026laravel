# Using `design-taste-frontend` in I2U2 Antigravity HR Portal

This guide explains how to activate, configure, and apply the **`design-taste-frontend`** skill across Blade views, dashboards, self-service portals, and management interfaces in this project.

---

## 1. Where the Skill is Located

The complete skill specification is installed directly in your workspace and globally:
- **Workspace Path:** [`.agents/skills/design-taste-frontend/SKILL.md`](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/.agents/skills/design-taste-frontend/SKILL.md)
- **Global Path:** `C:\Users\user\.agents\skills\design-taste-frontend\SKILL.md`

Because it is installed in `.agents/skills/`, Antigravity automatically detects it and can load it whenever mentioned in your prompt.

---

## 2. Core Philosophy: What `design-taste-frontend` Does

`design-taste-frontend` is an **anti-slop frontend engineering methodology**. It prevents AI from generating generic, templated UI (such as repetitive purple gradient blobs, 3 identical cards with icons, generic glassmorphism, and uninspired system fonts).

### The Three Dials
Every screen or redesign is controlled by three configurable dials:

| Dial | Scale | What It Controls |
|---|---|---|
| **`DESIGN_VARIANCE`** | 1 to 10 | **1** = strict grid symmetry, **10** = expressive editorial/organic layout |
| **`MOTION_INTENSITY`** | 1 to 10 | **1** = static/restrained, **10** = kinetic, fluid micro-interactions |
| **`VISUAL_DENSITY`** | 1 to 10 | **1** = airy consumer showcase, **10** = dense enterprise data cockpit |

---

## 3. Recommended Dial Presets for HR Portal Modules

In an Enterprise HR portal, different pages serve different user mindsets. Use these pre-calibrated presets:

### A. Auth, Login & Public Pages (High Brand Impact)
* **Dials:** `VARIANCE: 7` | `MOTION: 5` | `DENSITY: 4`
* **Focus:** Deep corporate brand identity, mesh background accents, intentional typography, smooth input transitions.
* **Example:** The redesigned login page (`resources/views/auth/login.blade.php`).

### B. Employee Self-Service (ESS) & Profile (`/my-portal`)
* **Dials:** `VARIANCE: 6` | `MOTION: 4` | `DENSITY: 3`
* **Focus:** Warm, personal, frictionless user experience. Generous whitespace, clear summary cards, legible leave balance rings.
* **Pages:** `my_portal/index.blade.php`, `my_portal/leaves.blade.php`, `my_portal/attendance.blade.php`.

### C. Admin Data Tables & Heavy Management
* **Dials:** `VARIANCE: 3` | `MOTION: 2` | `DENSITY: 8`
* **Focus:** High scannability, tight compact spacing, zero motion distraction, high contrast badges, instant search.
* **Pages:** `leaves/index.blade.php`, `employees/index.blade.php`, `payroll/index.blade.php`.

### D. Analytics & Executive Dashboards
* **Dials:** `VARIANCE: 6` | `MOTION: 4` | `DENSITY: 6`
* **Focus:** Metric cards with directional deltas (+12%), subtle gradient chart fills, distinct typography hierarchy.
* **Pages:** `dashboard/super_admin.blade.php`, `manager_portal/index.blade.php`.

---

## 4. How to Prompt Antigravity to Use This Skill

Whenever you want to build or polish a view, simply reference the skill name or dials in your prompt:

### Example Prompts:

#### 1. Redesigning an Existing Screen
```text
Redesign the Employee Self-Service Leaves view (resources/views/my_portal/leaves.blade.php) using design-taste-frontend.
Set VARIANCE=6, MOTION=4, DENSITY=3.
Ensure it maintains dark/light mode compatibility and uses our DaisyUI + Tailwind setup.
```

#### 2. Building a New Component
```text
Create a Leave Balance widget using design-taste-frontend.
Make it feel like a modern SaaS dashboard widget (Linear/Stripe aesthetic), with subtle hover lifts and clean circular progress meters.
```

#### 3. Polishing Data Tables
```text
Audit and polish the Recruitment Candidates table using design-taste-frontend with DENSITY=8 and VARIANCE=3.
Preserve Rule 8 (action buttons in Column 1) and Rule 9 (Select2 dropdowns).
```

---

## 5. Portal Rules Compatibility Checklist

When `design-taste-frontend` generates code for this portal, it adheres to our master constraints:

- [x] **Dark/Light Mode Compliance:** Uses theme variables (`bg-base-100`, `bg-base-200`, `text-body-emphasis`) instead of hardcoded hex colors.
- [x] **Zero Remote CDNs:** All styles are compiled via Vite (`resources/css/app.css`) or stored locally in `public/assets/`.
- [x] **Tailwind CSS v4 & daisyUI v5:** Native classes rather than bloated custom CSS.
- [x] **Rule 8 Column-1 Action Buttons:** Action buttons stay compact and icon-only in column 1 with 6px spacing.
- [x] **Rule 12 Form Submit Loaders:** Submit buttons retain `.btn-loader` and spinner states.

---

## 6. Quick Verification Commands

To check the skill files or verify Tailwind/Vite builds after applying designs:

```bash
# Verify skill file exists in project
Get-Item .agents/skills/design-taste-frontend/SKILL.md

# Compile frontend assets
npm run build

# Clear Laravel view caches to inspect design changes
php artisan view:clear
```
