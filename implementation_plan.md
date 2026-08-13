# Implementation Plan - Computer/Device Hostname & Network Tracking for TEKKEN 7 Showdown

Implement **Computer/Device Hostname Resolution** (via Reverse DNS `gethostbyaddr` & NetBIOS `nbtstat`), **Local Network MAC Address Extraction** (`arp -a`), and **Anti-Fake Duplicate Detection** in `d:\SOFTWARES\xampp82new\hri2k2new\antigravity_portal`.

## Strategy Overview & Technical Architecture

> [!IMPORTANT]
> **How Device Tracking Will Work**:
> 1. **Computer Hostname Resolution (`device_name`)**:
>    - When a player submits a registration over the office network, the backend server performs a **Reverse DNS Lookup (`gethostbyaddr($ip)`)** and executes **Windows NetBIOS query (`nbtstat -A $ip`)**.
>    - Captures exact workstation computer name (e.g. `LAPTOP-MAHESH-PC`, `FINANCE-PRIYA`, `DESKTOP-ANKIT`).
> 2. **MAC Address Extraction (`mac_address`)**:
>    - Queries local Windows ARP table (`arp -a $ip`) to capture client physical network MAC address.
> 3. **Browser Device GUID (`device_hash`)**:
>    - Generates a persistent unique device GUID stored in browser `localStorage`.
> 4. **Strict Unique UTR Enforcement**:
>    - Enforces database `UNIQUE` index on `utr_number` to reject recycled or fake payment reference IDs.
> 5. **Admin Control Panel Fraud Alerts**:
>    - Displays **Device Hostname & Network Info** (`COMPUTER-NAME (IP / MAC)`) in the admin queue table ([`/tekken-showdown/admin`](http://localhost:8000/tekken-showdown/admin)).
>    - Automatically flags entries sharing the same Computer Hostname or IP address with a red `⚠️ Multiple Submissions from Device` alert.

---

## Proposed Changes

### Database Layer

#### [NEW MIGRATION] [2026_08_13_000001_add_device_tracking_to_tekken_registrations_table.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/database/migrations/2026_08_13_000001_add_device_tracking_to_tekken_registrations_table.php)
- Adds columns to `tekken_registrations`:
  - `ip_address` (string, nullable)
  - `mac_address` (string, nullable)
  - `device_name` (string, nullable - computer hostname from NetBIOS / DNS)
  - `device_hash` (string, nullable - browser device GUID)
  - `user_agent` (text, nullable)
- Modifies `utr_number` column to be `UNIQUE`.

### Application Backend (Laravel)

#### [MODIFY] [TekkenRegistration.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/app/Models/TekkenRegistration.php)
- Update `$fillable` array to include `ip_address`, `mac_address`, `device_name`, `device_hash`, and `user_agent`.

#### [MODIFY] [TekkenShowdownController.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/app/Http/Controllers/TekkenShowdownController.php)
- Add helper method `resolveDeviceHostname($ip)`:
  - Performs `gethostbyaddr($ip)`.
  - Executes Windows command `nbtstat -A $ip` to extract NetBIOS computer name.
- Add helper method `resolveMacAddress($ip)`:
  - Executes Windows command `arp -a $ip` to extract physical MAC address.
- Update `store(Request $request)`:
  - Add validation: `'utr_number' => 'required|string|min:6|unique:tekken_registrations,utr_number'`.
  - Capture client IP (`$request->ip()`), resolved Computer Hostname, resolved MAC address, and browser device hash.
  - Save all tracking parameters with registration record.
- Update `admin(Request $request)`:
  - Query duplicate IP / Hostname counts to flag suspicious repeat registrations.
- Update `export()`:
  - Include Computer Hostname, IP Address, MAC Address, and Device GUID columns in exported CSV.

### Frontend JS & Admin View

#### [MODIFY] [tekken.js](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/public/assets/js/tekken.js)
- Generate a persistent device GUID in `localStorage` (`tekken_device_guid`) and send as `device_hash` with form submission.

#### [MODIFY] [admin.blade.php](file:///d:/SOFTWARES/xampp82new/hri2k2new/antigravity_portal/resources/views/tekken/admin.blade.php)
- Add **Device & Network Info** column showing `Computer Hostname (IP / MAC)`.
- Display red warning badges `⚠️ Suspected Duplicate (Same Computer)` for multiple submissions from the same computer name or IP.

---

## Verification Plan

### Automated / Command Verification
1. Run new database migration:
   ```bash
   php artisan migrate --path=database/migrations/2026_08_13_000001_add_device_tracking_to_tekken_registrations_table.php
   ```

### Manual Verification
1. Submit a test registration from local machine (`127.0.0.1` / office IP).
2. Check database record in `tekken_registrations` to confirm `device_name` (computer name), `ip_address`, `mac_address`, and `device_hash` are recorded.
3. Try submitting a second registration with the same UTR ID -> Verify validation rejects duplicate UTR.
4. Access Admin Panel ([`/tekken-showdown/admin`](http://localhost:8000/tekken-showdown/admin)) with PIN `254032` -> Confirm Computer Hostname & Network Details display with warning badges on duplicate entries.
