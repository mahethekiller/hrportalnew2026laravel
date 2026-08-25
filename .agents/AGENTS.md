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

## 💾 Rule 4: Secure File Management
- All uploads must be validated against a strict mime-type list (e.g. PDF, PNG, JPG, Docx).
- File uploads must be stored in private storage (`storage/app/private/` or secure S3/Local disks).
- Access to uploaded files must be gated by Laravel Policies (e.g. `DocumentPolicy` ensures employees can only view their own documents).
- Files must never be uploaded directly to `public/` directories without proper authorization checks.

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

