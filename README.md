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
  - Implements a granular **Permissions Grid Table** (View/Create/Edit/Delete) for all 12 system modules.
  - Dynamic global interceptors map capability gates (`@can`) to logged-in user profile roles instantly.
- **📁 Recruitment & Applicant Workflows**: Job code tag registers, candidate job post requisitions, interview schedule managers, applicant status trackers, and automatic convert-to-employee onboarding controls.
- **🎓 Training Sessions**: Register company trainers, schedule training classes, track session status, and monitor trainee lists.
- **🧭 Dynamic Navigation Menu Manager**: Re-arrange and drag-and-drop the sidebar accordion menu items dynamically with access keys mapped to roles.
- **🔑 Super Admin API & Docs**: Access tokens manager, webhooks listener toggle, and interactive REST API documentation.

---

## 🛠️ Setup & Installation Instructions

Follow these instructions to run the Antigravity HR Portal on your local environment:

### Prerequisites
- **PHP**: `^8.2`
- **Composer**: Dependency Manager
- **Database**: MySQL / MariaDB (e.g. via XAMPP)
- **Node.js & npm**: For compilation of assets

---

### Step-by-Step Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/mahethekiller/hrportalnew2026laravel.git
   cd hrportalnew2026laravel
   ```

2. **Install Composer Dependencies**
   ```bash
   composer install
   ```

3. **Install npm Dependencies & Build Assets**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup**
   Copy the example environment file and configure your database parameters:
   ```bash
   cp .env.example .env
   ```
   Open the `.env` file and configure your database connection parameters:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=i2u2_db_laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations & Seed Database**
   Run the setup script or run migrations and seeders manually:
   ```bash
   php artisan migrate
   php artisan db:seed --class=NavigationMenuSeeder
   ```

7. **Clear Compiled Views & Caches**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

8. **Start Local Development Server**
   ```bash
   php artisan serve
   ```
   The portal is now running locally at `http://127.0.0.1:8000`.

---

## 🔑 Login Credentials

Log in to the dashboard using the seeded administrator credentials:
- **Username / Employee ID**: `super`
- **Password**: `254032`

---

## 🧪 Running Automated Tests

Run the complete feature test suite to verify code compliance and authorization boundaries:
```bash
php artisan test
```
*(All 106 tests with 278 assertions should pass cleanly).*
