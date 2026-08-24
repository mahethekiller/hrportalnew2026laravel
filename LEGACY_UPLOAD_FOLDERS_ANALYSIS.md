# Legacy CodeIgniter 3 HR Portal Upload Directory & Media Asset Analysis

This document provides a comprehensive analysis of all 31 legacy upload subdirectories used in the original CodeIgniter 3 HR Portal (`D:\SOFTWARES\xampp\htdocs\hrsale\uploads\`), their corresponding database tables, columns, legacy naming conventions, fallback assets, and the unified strategy for maintaining 100% backward compatibility in Laravel.

---

## 📁 Complete Upload Subdirectory Mapping Matrix

| # | Upload Subdirectory (`uploads/{folder}`) | Target Module / Business Function | Database Table(s) | Target Column(s) | Legacy File Naming Pattern | Fallback / Default Asset |
|:---|:---|:---|:---|:---|:---|:---|
| 1 | `accounting` | Finance Deposits & Transfers | `xin_finance_deposit`, `xin_finance_transfer` | `deposit_file`, `transfer_file` | `deposit_TIMESTAMP.pdf` | `NULL` |
| 2 | `album_images` | Gallery & Media Albums | `xin_company_album_images` | `image_name` | `album_TIMESTAMP.jpg` | `NULL` |
| 3 | `announcements` | Company Announcements | `xin_announcements` | `image` | `announcement_TIMESTAMP.png` | `NULL` |
| 4 | `asset_image` | Asset & Inventory Photos | `xin_assets` | `asset_image` | `asset_TIMESTAMP.jpg` | `assets/images/default_asset.png` |
| 5 | `attachments` | General Task/Project Attachments | `xin_task_attachment`, `xin_project_attachment` | `attachment_file` | `file_TIMESTAMP.pdf` | `NULL` |
| 6 | `award` | Employee Awards & Certificates | `xin_awards` | `award_photo` | `award_TIMESTAMP.jpg` | `NULL` |
| 7 | `chat_sound` | Real-time Chat Notification Audio | `xin_chat_messages` | `sound_file` | `notification.mp3` | `NULL` |
| 8 | `clients` | Client Logos & Profile Images | `xin_clients` | `client_profile` | `client_TIMESTAMP.png` | `NULL` |
| 9 | `company` | Company Policies & Identity Docs | `xin_company_policy` | `document` | `policy_TIMESTAMP.pdf` | `NULL` |
| 10 | `corporate_benefits` | Corporate Perks & Benefits Docs | `xin_corporate_benefits` | `benefit_file` | `benefit_TIMESTAMP.pdf` | `NULL` |
| 11 | `csv` | Bulk Import / Export Data CSVs | `xin_csv_imports` | `file_name` | `import_TIMESTAMP.csv` | `NULL` |
| 12 | `dbbackup` | System Database Backups | `xin_database_backups` | `backup_file` | `backup_DATE.sql.gz` | `NULL` |
| 13 | `document` | Employee Identity & Qualification Docs | `xin_documents`, `xin_employee_documents` | `file_name` | `doc_TIMESTAMP.pdf` | `NULL` |
| 14 | `employee_verification` | Background Check & Verification | `xin_employee_verifications` | `verification_file` | `verify_TIMESTAMP.pdf` | `NULL` |
| 15 | `employers` | Vendor & External Partner Logos | `xin_emp_vendors` | `vendor_logo` | `vendor_TIMESTAMP.png` | `NULL` |
| 16 | `expense` | Expense Claims & Receipt Bills | `xin_expenses`, `xin_finance_expense` | `bill_copy`, `expense_file` | `expense_TIMESTAMP.png` | `NULL` |
| 17 | `files` | Internal File Manager Storage | `xin_file_manager` | `file_name` | `file_TIMESTAMP.doc` | `NULL` |
| 18 | `files_manager` | Public Downloadable File Manager | `xin_files` | `attachment` | `public_TIMESTAMP.pdf` | `NULL` |
| 19 | `income_docs` | Tax Declaration & Proof Files | `xin_system_setting` | `income_dec_file`, `income_dec_file_roi` | `filei2k2_TIMESTAMP.doc` | `NULL` |
| 20 | `job_req_file` | Job Requisition Request Docs | `xin_job_requests` | `job_req_file` | `jobreq_TIMESTAMP.pdf` | `NULL` |
| 21 | `languages_flag` | Language Flag Country Icons | `xin_languages` | `flag_icon` | `flag_code.png` | `NULL` |
| 22 | `logo` | System & Company Branding Logos | `xin_companies`, `xin_system_setting` | `logo`, `job_logo`, `payroll_logo` | `logo_TIMESTAMP.png` | `uploads/logo/default_logo.png` |
| 23 | `mail` | Email Campaign & Template Files | `xin_email_history` | `mail_attachment` | `mail_TIMESTAMP.pdf` | `NULL` |
| 24 | `profile` | Employee Profile Photos | `xin_employees` | `profile_picture` | `profile_TIMESTAMP.jpg` | `uploads/profile/default_male.jpg` |
| 25 | `project` | Project Documentation & Attachments | `xin_projects` | `project_file` | `project_TIMESTAMP.zip` | `NULL` |
| 26 | `resignations` | Employee Resignation Letters | `xin_employee_resignations` | `resignation_file` | `resignation_TIMESTAMP.pdf` | `NULL` |
| 27 | `resume` | Candidate CVs & Job Applications | `xin_job_applications` | `job_resume` | `resume_TIMESTAMP.pdf` | `NULL` |
| 28 | `task` | Task Attachments & Screenshots | `xin_tasks` | `task_file` | `task_TIMESTAMP.png` | `NULL` |
| 29 | `ticket` | Support & HR Ticket Attachments | `xin_support_tickets`, `xin_ticket_attachments` | `attachment_file` | `ticket_TIMESTAMP.png` | `NULL` |
| 30 | `users` | User Account Avatars | `xin_users` | `user_photo` | `user_TIMESTAMP.jpg` | `uploads/profile/default_male.jpg` |
| 31 | `wfhactivities` | Work-From-Home Activity Screenshots | `xin_wfh_clocking` | `activity_file` | `wfh_TIMESTAMP.png` | `NULL` |

---

## 🛠️ CodeIgniter 3 Legacy URL Construction Logic

In the old CI3 application (`D:\SOFTWARES\xampp\htdocs\hrsale\application\controllers\admin\`):
```php
// Employee Profile Picture Output Pattern (Employees.php):
if ($r->profile_picture != '' && $r->profile_picture != 'no file') {
    $de_file = base_url() . 'uploads/profile/' . $r->profile_picture;
} else {
    if ($r->gender == 'Male') {
        $de_file = base_url() . 'uploads/profile/default_male.jpg';
    } else {
        $de_file = base_url() . 'uploads/profile/default_female.jpg';
    }
}

// Company Logo Output Pattern (Company.php):
if ($r->logo != '' && $r->logo != 'no file') {
    $logo_file = base_url() . 'uploads/logo/' . $r->logo;
} else {
    $logo_file = base_url() . 'uploads/logo/default_logo.png';
}
```

---

## 🚀 Unified Laravel Architecture & Compatibility Strategy

### 1. Directory Structure Mirroring
Ensure all 31 upload subdirectories exist inside `public/uploads/` in Laravel.

### 2. Universal `UploadHelper` Service (`App\Helpers\UploadHelper`)
Provide a single, centralized helper with methods:
- `UploadHelper::url(string $type, ?string $filename, ?string $gender = null): string`
  - Automatically resolves relative database filenames to full `asset('uploads/{type}/{filename}')` URLs.
  - Automatically handles fallbacks for missing/empty profile pictures (`default_male.jpg` / `default_female.jpg`) and company logos.
- `UploadHelper::upload(UploadedFile $file, string $type): string`
  - Stores uploaded files directly into `public_path('uploads/' . $type)` using the legacy timestamp naming pattern (`{type}_{timestamp}.{ext}`).
  - Returns the raw filename to be saved in the database column.

---

## 📄 Implementation Plan Summary

1. Create all 31 required directories under `public/uploads/`.
2. Implement `App\Helpers\UploadHelper` with type mapping & fallbacks.
3. Register global Blade helper directives or Eloquent Model accessors (e.g. `$employee->profile_picture_url`, `$company->logo_url`).
4. Update Controllers (`ProfileController`, `CompanyController`, `SupportTicketController`, `LeaveApplicationService`) to consume `UploadHelper`.
