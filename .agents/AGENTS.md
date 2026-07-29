# Project Rules for Antigravity HR Portal

## 1. Live Deployment Script Synchronization Rule (`run_live_setup.php`)
- **MANDATORY**: Whenever you make any database schema alteration, add a new table, add a Spatie permission, create a new upload folder, or introduce a configuration step:
  - You **MUST** immediately update [run_live_setup.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/run_live_setup.php).
  - Ensure all database operations in `run_live_setup.php` remain non-destructive (e.g., using `CREATE TABLE IF NOT EXISTS`, checking `INFORMATION_SCHEMA.COLUMNS` before column modifications, using `firstOrCreate` for seeders).
  - Never execute `DROP TABLE`, `TRUNCATE`, or `DELETE` in `run_live_setup.php`.
