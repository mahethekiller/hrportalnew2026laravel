---
name: researching-hr-portal-features
description: Performs deep-dive feature research, gap analysis, and competitive benchmarking for HR management portals. Compares existing codebase capabilities against top enterprise HRIS platforms (e.g. Workday, BambooHR, Darwinbox, Rippling) and produces structured feature specifications, ROI feasibility matrices, database schema blueprints, and UI/UX recommendations. Use when analyzing HR portal capabilities, researching new HR modules, conducting feature audits, or planning HR product roadmaps.
---

# Researching HR Portal Features & Innovations

Expert instructions for researching, auditing, benchmarking, and recommending high-value features and architectural enhancements for the Antigravity HR Portal.

---

## 1. Audit & Gap Analysis Workflow

When asked to research or suggest HR portal features, execute this 4-step systematic workflow:

```
┌──────────────────────────┐    ┌──────────────────────────┐
│ Step 1: Codebase Audit   │ ──>│ Step 2: Benchmarking     │
│ Inspect Models & Routes  │    │ Compare vs. Workday/Ripp │
└──────────────────────────┘    └──────────────────────────┘
                                             │
                                             ▼
┌──────────────────────────┐    ┌──────────────────────────┐
│ Step 4: Spec & Roadmap   │ <──│ Step 3: Gap & ROI Matrix │
│ Schema, Spatie, Blade    │    │ Categorize Impact/Effort │
└──────────────────────────┘    └──────────────────────────┘
```

---

## 2. Codebase Capability Mapping Matrix

Before recommending features, inspect the existing workspace modules:

| Core HR Domain | Existing Models & Services | Benchmark Capability Checklist |
| :--- | :--- | :--- |
| **Employee & Core HR** | `Employee`, `Department`, `Designation`, `Company` | Digital onboarding checklist, org charts, document expiration alerts, emergency contact verification |
| **Attendance & Time** | `EmpTodayAttendance`, `WfhClocking`, `OfficeLocation` | Geo-fenced mobile check-in, overtime calculation rules, shift roster planning, biometric sync API |
| **Leave Management** | `EmployeeLeave`, `LeaveType` | Encashment rules, compensatory off (Comp-Off) workflow, team calendar view, leave balance rollover |
| **Payroll & Compensation** | `PayrollPayment`, `SalaryHistory`, `MakePayment` | Automated tax projections (Old vs. New regime), payslip PDF generation, bonus/incentive calculations, statutory compliance (PF, ESI, LWF) |
| **Performance (PMS)** | `PerformanceAppraisal`, `PerformanceIndicator` | OKR tracking, 360-degree feedback, continuous 1-on-1 notes, self-review workflows |
| **Recruitment (ATS)** | `JobPost`, `JobApplication`, `JobInterview`, `Referral` | Candidate pipeline kanban board, resume parser, automated interview scheduling, offer letter builder |
| **Helpdesk & Tickets** | `SupportTicket`, `HrTicket` | SLA tracking, auto-assignment, priority matrix, resolution feedback |

---

## 3. High-Value HR Feature Categories

When formulating recommendations, draw inspiration from these enterprise-grade features:

### A. Employee Experience & Self-Service (ESS)
- **Interactive Org Chart**: Visual hierarchy tree with search, manager connections, and quick profile previews.
- **Smart Knowledge Base / FAQ**: Categorized company policy wiki with searchable articles and read-receipt confirmations.
- **Employee Wellness & Recognition**: Peer-to-peer appreciation badges (Kudos), birthday/anniversary spotlight feed, wellness survey trackers.
- **Document Self-Request Hub**: One-click requests for Salary Certificates, Bonafide Letters, and NOC documents with automated PDF generation.

### B. Manager Self-Service (MSS) & Analytics
- **Team Attrition Risk Predictor**: Early-warning indicators based on attendance trends, leave patterns, and appraisal scores.
- **Overtime & Shift Management**: Visual shift roster planner with drag-and-drop assignment and overtime authorization toggles.
- **Continuous 1-on-1 Check-ins**: Shared agenda notes, action item tracking, and private manager notes between review cycles.

### C. Compliance, Security & Automation
- **Statutory Audit Readiness**: Automated tracking of Provident Fund (PF), Employee State Insurance (ESI), and Professional Tax (PT) calculations.
- **Document Expiration Radar**: Proactive notifications for expiring passports, work visas, employment contracts, and mandatory certifications.
- **Audit Log Trail**: Complete historical changelog for salary modifications, role permission updates, and sensitive employee profile changes.

---

## 4. Output Specification Blueprint

Every feature recommendation output must follow this structured specification template:

```markdown
# [Feature Name] Feature Specification

## 1. Executive Summary & Value Proposition
- **Target Persona**: [Employee / Manager / HR Admin / Executive]
- **Problem Solved**: [Clear explanation of friction point or manual inefficiency]
- **Business Impact**: [ROI, time saved, compliance benefit]

## 2. Feature Capability Breakdown
- [ ] Core Requirement 1
- [ ] Core Requirement 2
- [ ] Advanced Requirement 3

## 3. Security & Permission Mapping (Spatie)
- **New Permissions Required**:
  - `view.[feature_name]`
  - `create.[feature_name]`
  - `edit.[feature_name]`
  - `delete.[feature_name]`
- **Role Assignments**:
  - `Super Admin`: Full Access
  - `HR Manager`: Create, Edit, View
  - `Employee`: View Own Only

## 4. Architectural & Schema Blueprint
- **Proposed/Modified Database Tables**:
  - Table: `[table_name]`
  - Key Columns: `[column_name]` (type, constraints)
- **Controllers & Services**:
  - Controller: `[FeatureController]`
  - Service: `[FeatureService]`
  - Repository: `[FeatureRepository]`

## 5. UI/UX & Theme Compliance
- 100% Bootstrap 5.3 compliance (`bg-body-tertiary`, `text-body-emphasis`).
- Dark mode adaptive (`data-bs-theme="dark"`).
- Key Components: KPI Metric Card, Action Header, Filterable Data Table, Modal Dialogs.

## 6. Implementation Feasibility & Priority Matrix
- **Priority**: [High / Medium / Low]
- **Estimated Effort**: [1-2 Days / 3-5 Days / 1-2 Weeks]
- **Dependencies**: [Existing models or services required]
```

---

## 5. Master Constraint Safeguards

Whenever generating feature recommendations or implementation code:
1. **Rule 1 (Asset Reuse)**: Always prefer extending existing tables (`xin_*` or standard tables) and models over redundant duplicate tables.
2. **Rule 2 (Zero CDNs / Bootstrap 5.3)**: Rely strictly on local Bootstrap 5.3 assets under `public/assets/`. Never recommend Tailwind CSS, Alpine.js, or external CDNs.
3. **Rule 3 (Dynamic Spatie Permissions)**: Include exact Spatie permission definitions and `@can` Blade directives for every new feature.
4. **Rule 4 (Secure Storage)**: File storage must use private local storage with Laravel Policy authorization checks.
5. **Rule 5 (Clean Architecture)**: Enforce Service/Repository pattern separation.
