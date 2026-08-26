# 🚀 Antigravity HR Portal - Server Deployment Guide

This document contains step-by-step instructions for deploying application updates to live/staging server environments containing existing production database tables (`i2u2_db_laravel` / `xin_*` tables).

> [!IMPORTANT]
> **Zero Data Modification Principle**: All deployment procedures outlined in this guide MUST BE NON-DESTRUCTIVE to existing database tables, employee records, roles, permissions, or transactional data.

---

## 📋 Standard Deployment Workflow

### 1. Pull Latest Code Base
SSH into your server, navigate to the portal root directory, and pull the latest code from the `main` branch:
```bash
cd /path/to/your/portal
git pull origin main
```

---

### 2. Update Database & Navigation Menus (Non-Destructive)
Run database seeders that execute `firstOrCreate()` / safe updates without wiping or mutating existing records:

#### A. Seed Sidebar Navigation Menus
Populates navigation links (`xin_navigation_menus` table) for new features:
```bash
php artisan db:seed --class=NavigationMenuSeeder
```

#### B. Seed Dynamic Roles & Spatie Permissions
Updates Spatie permissions and role mappings safely without altering existing user assignments:
```bash
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder
```

---

### 3. Clear & Rebuild Production Caches
Flush outdated route, view, and config caches, then rebuild compiled production optimizations:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild Production Caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### 4. Verify Storage Symlinks & File Permissions
Ensure public storage symlinks exist and required writable directories have proper permissions (`775`):
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

### 5. Verify Background Task Scheduler
Ensure the Laravel Task Scheduler is configured in the server's crontab (`crontab -e`) to support automated routines (such as post-exit account revocation `php artisan portal:revoke-exited-employees`):
```bash
* * * * * cd /path/to/your/portal && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🛠️ Feature-Specific Deployment Steps & History

### Version 2.1.0 - Resignation & Departmental Clearance Hub
- **Database Table Dependencies**: `xin_employee_resignations`, `xin_employee_resignations_logs`
- **Seeder Requirements**: `NavigationMenuSeeder` (adds *Resignation & Exit*, *Team Resignations*, and *No-Dues Clearance Hub*).
- **Configuration Dependencies**:
  1. System Settings > SMTP Profiles & Routing (`resignation` module extra CC routing).
  2. No-Dues Clearance Hub > Default Clearance Officers Configuration (`default_clearance_officers`).
### Version 2.2.0 - Candidate Interview Email Invitations & Dynamic SMTP
- **Database Table Dependencies**: `xin_email_template` (Template code: `candidate_interview_scheduled`).
- **Seeder Requirements**: `EmailTemplateSeeder` (`php artisan db:seed --class=EmailTemplateSeeder`).
- **Configuration Dependencies**:
  1. System Settings > Email Notifications: Ensure global mail toggle is enabled and module switch `"recruitment": true` is enabled in `storage/app/settings/mail_system_config.json`.
  2. SMTP Settings: Ensure active SMTP profile (Host, Port, Username, Password, From Address) is configured in **System Settings -> SMTP Settings**.

---

## 🔒 Master Rules for Future Updates
1. **Never drop or recreate existing database tables**.
2. **Always update this `SERVER_DEPLOYMENT_GUIDE.md` file whenever adding new feature deployment requirements**.
3. **Always use safe Eloquent methods** (`firstOrCreate()`, `updateOrCreate()`) in seeders.
