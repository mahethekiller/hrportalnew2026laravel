# 🚀 Antigravity Enterprise HR Portal

Welcome to the **Antigravity Enterprise HR Portal** — a premium, modern, and light-weight Human Resources Management System (HRMS) built using Laravel, PHP, and Bootstrap.

This application provides a highly polished dashboard and fully integrated modules for employee lifecycle operations, department organization, payroll processing, performance appraisal logs, company assets inventory, training tracks, recruiter pipelines, and granular, database-driven security permissions.

---

## ✨ Core Modules & Features

- **👥 Employee Directory & Profiling**: Profile lifecycle controls, self-editing limits (employees can only edit their own profile), sub-resource updates (emergencies, bank accounts, qualifications, work experiences), and document folders.
- **🏢 Department & Company Organization**: Structures for managing parent companies, corporate departments, designation positions, office locations, and reporting manager trees.
- **📅 Leaves & Attendance Tracking**: Submit and approve leave applications, configure custom leave types, view work shifts, and check real-time attendance clock-in/out logs.
- **💳 Payroll & Compensation Hub**: Process monthly salary payroll, view historical pay registers, download printable PDF payslips, and manage corporate grade templates.
- **📈 Performance Management**: Create appraisals, manage indicators, track goals, and generate visual, user-friendly performance report cards.
- **🛡️ Custom Security Roles & Permissions Matrix**:
  - Powered by a custom database table (`portal_roles`) completely independent of external packages.
  - Implements a granular **Permissions Grid Table** (View/Create/Edit/Delete) for all system modules.
  - Dynamic global interceptors map capability gates (`@can`) to logged-in user profile roles instantly.
- **📁 Recruitment & Applicant Workflows**: Job code tag registers, candidate job post requisitions, interview schedule managers, applicant status trackers, and automatic convert-to-employee onboarding controls.
- **🎓 Training Sessions**: Register company trainers, schedule training classes, track session status, and monitor trainee lists.
- **📢 Announcement System**: Corporate news broadcasts, policy updates, event announcements, and company/department targeting.
- **🎫 Multi-Department Ticket Hub**: Specialized support ticketing systems for general support, HR tickets, and Admin tickets.
- **🧭 Dynamic Navigation Menu Manager**: Re-arrange and drag-and-drop the sidebar accordion menu items dynamically with access keys mapped to roles.

---

## 🛠️ Setup & Deployment Guide (Live Server with Existing Legacy DB)

Follow these steps when deploying to a production Apache server (e.g. cPanel / VPS) that **already has an existing legacy database**:

> [!IMPORTANT]
> **Data Safety Guarantee**: Running `php artisan migrate --force` **never deletes or modifies any of your existing legacy tables or records**. It only creates missing framework tables (`cache`, `sessions`, `spatie_permissions`, `portal_roles`, `xin_navigation_menus`, etc.).

### Step-by-Step Production Deployment

1. **Clone the Repository**
   ```bash
   git clone https://github.com/mahethekiller/hrportalnew2026laravel.git .
   ```

2. **Configure Production Environment (`.env`)**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and set your existing database credentials and URL:
   ```ini
   APP_NAME="HR Portal"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_existing_legacy_db_name
   DB_USERNAME=your_db_username
   DB_PASSWORD=your_db_password
   ```

3. **Install Dependencies & Generate Key**
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   ```

4. **Run Framework & Feature Migrations**
   ```bash
   # On cPanel server:
   /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force
   ```
   *(All migrations safely check `Schema::hasTable()` before creation. Existing legacy tables will be left completely untouched).*

5. **Seed Sidebar Navigation & Super Admin Permissions**
   ```bash
   # On cPanel server:
   /opt/cpanel/ea-php82/root/usr/bin/php artisan db:seed --class=NavigationMenuSeeder --force
   ```

6. **Create Public Storage Symlink & Set Permissions**
   ```bash
   php artisan storage:link
   chmod -R 775 storage bootstrap/cache
   ```

7. **Optimize Application Caching**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 💻 Local Development Setup

For running on a local development machine (e.g. XAMPP):

1. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Run Migrations & Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed --class=NavigationMenuSeeder
   ```

4. **Serve Application**
   ```bash
   php artisan serve
   ```

---

## 🔑 Default Login Credentials

- **Username / Employee ID**: `super`
- **Password**: `254032`

---

## 🧪 Running Automated Tests

Run the complete feature test suite (115 tests / 313 assertions):
```bash
php -d memory_limit=512M artisan test
```
