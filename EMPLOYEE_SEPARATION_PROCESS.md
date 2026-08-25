# Employee Separation Process Specification – i2u2 HR Portal

## 📌 Document Overview
- **Module Name:** Employee Separation & Resignation Lifecycle
- **Target Portal:** i2u2 HR Portal
- **Document Version:** 2.0.0
- **Status:** Architecture & Requirements Specification

---

## 🎯 Executive Overview
The Employee Separation Process automates and standardizes the end-to-end exit lifecycle of employees within the i2u2 HR Portal. It provides transparent tracking, automated notice period LWD calculation (in months), notice shortfall buyout calculations, company-specific admin-configurable resignation email recipients, automated email threading, assigned departmental clearance officers, reviewer audit trail tracking (saving "Reviewed By" person details & timestamps), HR email send/resend triggers, Last Working Day (LWD) exit triggers, employee exit forms, itemized asset handover checklists, a strict 4-stage sequential No-Dues approval workflow, automated PDF Relieving & Experience Letter generation, automated post-exit login revocation, and executive attrition analytics.

---

## 📑 Detailed Workflow Requirements

### 1. Employee Resignation Initiation & Automated LWD Calculation
- **Initiator:** Employee via the i2u2 Employee Self-Service (ESS) Portal.
- **Automated LWD Calculation Engine (In Months):**
  - The system automatically retrieves the employee's `notice_period` (stored in months) from the `xin_employees` table.
  - **Notice Calculation Logic:**
    - `notice_period` values (e.g., `1`, `2`, `3`) represent **Months**.
    - Auto-calculated LWD = `Notice Date + N Months` (e.g., `Notice Date + 1 Month` for `notice_period = 1`).
    - If `notice_period` is `0` or `NULL`: Defaults to standard **1 Month**.
  - The system displays a live info callout on the Resignation Form showing the configured Notice Period in months and auto-calculated LWD date.
- **Notice Shortfall / Buyout Calculator:**
  - If an employee requests an early exit date before the calculated LWD, the system automatically computes **Notice Shortfall Days** (e.g., 15 days shortfall).
  - Shortfall days are automatically passed to Accounts for Full & Final (FnF) salary recovery calculations.
- **Form Inputs:**
  - Notice Date (Defaults to today)
  - Requested Last Working Day (LWD) *(Pre-populated with auto-calculated date)*
  - Resignation Reason & Handover Summary
- **Admin-Configurable Email Notification Recipients (Global & Company-Specific):**
  - Configured directly via Admin Panel under **System Settings > SMTP Profiles & Routing (`/smtp-profiles`)**.
  - Automatically resolves recipients based on the employee's company entity:
    1. **Employee** (`$employee->email`)
    2. **Reporting Manager** (`$manager->email`)
    3. **Company-Specific Extra CC Recipients** (e.g., `company_hr@i2k2.com, kamal@i2k2.com`) or fallback to **Global Extra CC Recipients** (`priyanka@i2k2.com, kamal@i2k2.com`).
- **Notification Details:**
  - Contains a direct deep-link to the Separation/Resignation request inside the i2u2 Portal.

---

### 2. Admin Panel Resignation Email Configuration (Company-Specific & Global)
- **Location:** System Settings > SMTP Profiles & Mail Routing (`/smtp-profiles`)
- **Configurable Settings per Admin:**
  - **Resignation Module Enable/Disable Toggle**: Master switch to enable or pause resignation notification dispatches.
  - **SMTP Sender Profile Routing**: Map resignation emails to specific outgoing SMTP profiles.
  - **Global Resignation Extra CC Recipients**: Comma-separated email list (e.g., `kamal@i2k2.com, priyanka@i2k2.com`) automatically copied on all resignation lifecycle emails across all companies.
  - **Company-Specific Resignation Extra CC Overrides**: Set dedicated recipient emails per company entity (`xin_companies`).

---

### 3. Reporting Manager Response & Email Threading
- **Action:** Reporting Manager reviews request details, confirms/updates the Last Working Day (LWD), and records approval remarks.
- **Manager Choices:** `Approve / Accept` or `Reject / Retain`.
- **Automated Email Notification Recipients:**
  - Employee, Reporting Manager, and Company-Specific / Global Extra CC Recipients (Kamal Sir & Priyanka).
- **Single Email Threading Rule:**
  - All separation-related email communications must maintain the **same email thread/loop** via standard email headers (`Message-ID`, `In-Reply-To`, `References`).

---

### 4. Last Working Day (LWD) Exit Formalities & Asset Checklist
- **Trigger Condition:** Reaching the confirmed Last Working Day (LWD) recorded in the i2u2 Portal.
- **Employee Action Required on LWD:**
  - Complete and submit **Employee Exit Form** (feedback, experience rating, hand-over summary).
  - Complete and submit **No-Dues Form & Itemized Asset Return Checklist**:
    - Laptop / Desktop & Charger return status
    - ID Card & Access Badge return status
    - Company SIM / Mobile device return status
    - Cabinet keys & code/file repository handover status
  - Upload signed No-Dues document attachments.

---

### 5. Assigned Clearance Officers & "Reviewed By" Audit Trail
- **Assigned Clearance Officers per Resignation:**
  - **Reporting Manager**: `manager_person` (Auto-assigned)
  - **IT Department Person**: `it_person` (Assigned IT officer)
  - **Accounts Department Person**: `account_per` (Assigned Accounts officer)
  - **HR Department Person**: `hr_person` (Assigned HR officer)
- **Saving "Reviewed By" Person Details:**
  - Saves Reviewer User ID (`manager_person`, `it_person`, `account_per`, `hr_person`), Reviewer Name, and exact timestamp.
  - Displays clearance badges (e.g., *"Cleared by Rajesh Kumar (IT Dept) on 25 Aug 2026, 05:30 PM"*).
- **HR Send / Resend Email Notification Action:**
  - HR/Admin can trigger a **"Send / Resend No-Dues Action Email"** with a **Direct Deep-Link** to `/settings/clearance?resignation_id={id}&stage={stage}` requiring login.

---

### 6. Sequential Departmental No-Dues Approval Workflow
Once the employee submits the No-Dues Form, the portal automatically triggers a 4-stage sequential approval workflow:

| Stage | Approving Department | Assigned Person Column | Saved Reviewer Audit Info |
| :--- | :--- | :--- | :--- |
| **1st Stage** | 👤 **Reporting Manager** | `manager_person` | Manager User ID, Name, Comment & Date |
| **2nd Stage** | 💻 **IT Department** | `it_person` | IT Officer User ID, Name, Comment & Date |
| **3rd Stage** | 💰 **Accounts Department** | `account_per` | Accounts Officer User ID, Name, Comment & Date |
| **4th Stage (Final)** | 📋 **HR Department** | `hr_person` | HR Officer User ID, Name, Comment & Date |

---

### 7. Post-Clearance Automation & Features

#### A. Automated Relieving & Experience Letter PDF Generation
- Upon Stage 4 HR clearance, the system enables 1-click **"Generate Relieving Letter"** and **"Generate Experience Certificate"**.
- Compiles an official branded PDF with company header/footer, designation, joining date, LWD, and HR signature.

#### B. Automated Post-Exit Portal Access Revocation (LWD 11:59 PM)
- On the employee's confirmed Last Working Day (LWD) at 11:59 PM, the system automatically deactivates user portal login (`is_active = 0`).

#### C. Executive Attrition & Exit Analytics Dashboard
- Provides HR & Leadership with graphical charts:
  - Attrition Rate & Resignation Trends by Department
  - Primary Reasons for Exit
  - Average Clearance Processing Time per Department (IT, Accounts, HR)

---

## 🔄 System Flowchart

```mermaid
flowchart TD
    Admin[Admin Configures Company-Specific & Global Resignation Extra CCs in Admin Panel /smtp-profiles] --> Init
    Init[Employee Opens Resignation Form] --> B[System Fetches notice_period in Months from xin_employees]
    B --> C[Auto-Calculates LWD & Notice Shortfall Days]
    C --> D[Employee Submits Resignation]
    D --> E[Automatic Email Sent to:<br/>Employee + Manager + Company-Specific Extra CCs]
    E --> F[Reporting Manager Reviews & Responds]
    F --> G[Automatic Email Sent to:<br/>Employee + Manager + Company-Specific Extra CCs<br/><i>(Maintains Single Email Thread)</i>]
    G --> H[LWD Reached & Employee Completes Exit Form + Itemized Asset Checklist + No-Dues Form]
    H --> I[HR Assigns Clearance Officers: IT + Accounts + HR]
    I --> J[HR Sends/Resends No-Dues Notification Email with Direct Deep-Link]
    
    subgraph NoDuesWorkflow [Sequential No-Dues Approval Workflow]
        J --> K[Stage 1: Reporting Manager Review]
        K -->|Cleared / Pending| L[Saves Manager Reviewed By Details + Date + Comment]
        L --> M[Stage 2: Assigned IT Officer Review]
        M -->|Cleared / Pending| N[Saves IT Reviewed By Details + Date + Comment]
        N --> O[Stage 3: Assigned Accounts Officer Review]
        O -->|Cleared / Pending| P[Saves Accounts Reviewed By Details + Date + Comment]
        P --> Q[Stage 4: Assigned HR Officer Final Review]
        Q -->|Cleared / Pending| R[Saves HR Reviewed By Details + Date + Comment]
    end

    R -->|All Departments Cleared| S[HR Completes & Closes Separation Process]
    S --> T[1. Generate Relieving & Experience Letter PDF]
    S --> U[2. Schedule Post-Exit Portal Access Revocation at LWD 11:59 PM]
    S --> V[3. Update Executive Exit & Attrition Analytics]
```
