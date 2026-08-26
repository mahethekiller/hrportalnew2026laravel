# Workspace Agent Guidelines

## Theme & Dark/Light Mode Compliance
- Always ensure all Blade views, components, tables, forms, badges, and cards are 100% compatible with both Light Mode and Dark Mode (`data-bs-theme="dark"`).
- Never use hardcoded light backgrounds (e.g. `bg-white`, `bg-light`, `#F1F5F9`) or hardcoded dark text colors (`color: #0F172A`, `text-gray-900`) without corresponding `[data-bs-theme="dark"]` override rules or theme variables (`bg-body`, `bg-body-tertiary`, `text-body-emphasis`, `text-body-secondary`).
- Ensure all `.table`, `.table th`, `.table td`, and `.card` elements maintain high-contrast legibility in both Light and Dark modes.

---

# Antigravity HR Portal Master Rules

These rules represent the absolute constraints of the project. Any deviation from these rules by any agent will result in a validation failure and must be rejected during code review.

---

## 🔒 Rule 1: Existing Assets Reuse
- The database schema is already loaded in `i2u2_db_laravel`.
- The Eloquent models are already generated in `laravel_files/app/Models/`.
- Do not create migrations to rebuild tables.
- If a model is missing relationships or has incorrect properties, refactor it carefully rather than rewriting it.

## 📦 Rule 2: Zero CDNs & Tailwind CSS
- All CSS, JS, and font files must be stored locally under the `public/assets/` directory.
- No remote content delivery networks (CDNs) are allowed.
- The UI framework is Bootstrap 5.3.
- Tailwind CSS, Flowbite, and Alpine.js are strictly forbidden.

## 🛡️ Rule 3: Dynamic Spatie Permissions
- Do not hardcode permissions or roles anywhere in controllers or blade files.
- Use `@can('permission.name')` in Blade views.
- Use `$this->authorize('permission.name')` or Spatie middleware in routes.
- When generating new features:
  1. Add permissions to `PermissionSeeder`.
  2. Map permissions to roles in `RoleSeeder`.
  3. Ensure a database seed updates these records safely without wiping existing user assignments.

## 💾 Rule 4: Secure File Management & Descriptive Naming
- All uploads must be validated against a strict mime-type list (e.g. PDF, PNG, JPG, Docx).
- All uploaded files must use structured, human-readable file naming with `storeAs()` (e.g. `Resume_Rahul_Sharma_20260826_124950.pdf` or `Clearance_Emp102_20260826.pdf`) instead of random hash strings, enabling easy manual and programmatic file identification.
- File uploads must be stored in secure storage (`storage/app/private/` or dedicated storage disks).
- Access to uploaded files must be gated by Laravel Policies (e.g. `DocumentPolicy` ensures employees can only view authorized documents).
- Files must never be uploaded directly to `public/` root directories without proper authorization checks.

## ♻️ Rule 5: Zero Duplicate Logic
- Business logic must reside strictly in **Services**.
- DB queries must reside strictly in **Repositories**.
- Shared functions must be placed in reusable **Traits** or **Helpers**.
- If two modules require similar components (like a profile widget or file uploader), create a reusable Blade Component instead of duplicating HTML.

## 🧹 Rule 6: Mandatory Content Sanitization & Clean Text Rendering
- All ticket descriptions, comments, resolution remarks, announcements, and user text inputs must use `App\Traits\HasCleanContent` or `HasCleanContent::sanitizeContent($value, false)` before saving to the database.
- In Blade views, never output raw database strings containing un-decoded HTML entities (`&lt;p data-start=...`) or raw HTML tags (`<p><br></p>`) inside `<textarea>` inputs or plain text display blocks.
- Use `{!! $ticket->clean_description !!}` / `{!! $ticket->clean_remarks !!}` for rich view display and `{{ $ticket->plain_remarks }}` / `{{ $ticket->plain_description }}` for `<textarea>` form controls and truncated table snippets.

## 🚀 Rule 7: Server Deployment Guide Maintenance
- Whenever creating or modifying features that require database seeders, configuration changes, permission updates, or background commands:
  1. Ensure all database changes are 100% non-destructive to existing production database records.
  2. Maintain and document the exact step-by-step deployment instructions in `SERVER_DEPLOYMENT_GUIDE.md` in the project root.
  3. Never drop, truncate, or overwrite existing production database tables or user records.

## ⚡ Rule 8: First-Column Action Buttons in Data Tables
- All data tables across Blade views (e.g. Clearance Hub, Employee Directory, Resignations, Leave Management, Payroll, Roles) must place Action Buttons (`.btn-group`, action dropdowns, or action icons) in **Column 1** (the very first table column on the left).
- Table headers (`<thead>`) must have `<th>Actions</th>` as the first column.
- This ensures zero horizontal scrolling is needed to trigger actions and maximizes HR power-user efficiency.

## 🔍 Rule 9: Searchable Select Dropdowns in Form Modals
- All modal forms and complex selection fields (e.g. Job Requisition, Candidate, Employee, Department, Designation, Role, Leave Type) must include `class="select-search"` or `data-control="select2"` and a blank default option (`<option value=""></option>`).
- This automatically activates live search filtering via Select2, ensuring users can instantly search and filter options.

## 🛑 Rule 10: In-Modal Error Alerts & Input State Retention
- All modal forms across all Blade views must automatically remain/re-open open upon validation error or exception (`@if($errors->any() || session('error'))` script auto-trigger).
- All form inputs inside modals must retain user-entered values using `value="{{ old('field_name') }}"`, retain select option states (`old('field_name') == $val ? 'selected' : ''`), and retain checkbox states (`old('field_name', '1') == '1' ? 'checked' : ''`).
- Form validation/exception errors must be rendered directly inside the modal body (`@if(session('error'))` / `@if($errors->any())` alert banners) and inline using `@error('field_name')` directives and `is-invalid` classes.

## 📐 Rule 11: Strict Bootstrap 5 Modal Markup Compliance
- All modal dialogs across all Blade views must follow strict Bootstrap 5 HTML element hierarchy:
  - `.modal-dialog` MUST have `.modal-content` as its direct child (`.modal-dialog > .modal-content` or `.modal-dialog > form.modal-content`).
  - Never place an unstyled `<form>` tag between `.modal-dialog` and `.modal-content` (`.modal-dialog > form > .modal-content` is strictly forbidden). Doing so breaks Bootstrap flexbox width inheritance and causes `modal-lg` and `modal-xl` viewport sizing to collapse back to 500px.
  - Always use `<form class="modal-content" ...>` or place `<form>` inside `.modal-content` (`.modal-content > form`).
  - Complex modal forms containing email previews, WYSIWYG editors, or multi-step fields must use `.modal-xl` with a clean 2-Column Side-by-Side Widescreen Studio Layout (`col-lg-5` form parameters on left, `col-lg-7` preview/studio canvas on right).

## ⏳ Rule 12: Mandatory Form Submit Disabling & Loading Spinner State
- All form submissions across all Blade views and modal dialogs must automatically disable the primary submit button (`disabled="disabled"`) and display a visual loading spinner indicator (`<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Processing...`).
- Enforced globally via `<head>` helper `onclick="submitWithLoader(this)"` or by adding `class="btn-loader"` / `class="submit-loader"` to submit buttons, preventing duplicate form submissions and double API calls.


