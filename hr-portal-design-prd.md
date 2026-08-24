# I2k2 HRM — Design PRD (Look & Feel)

**Version:** 0.2 — revised against the current portal and the five-role model
**Owner:** DEV08
**Scope:** Visual design, interaction design, information architecture, and design system for the I2k2 HRM Enterprise Portal (responsive web).
**Out of scope:** Backend architecture, payroll calculation logic, data model, integration contracts.

**What changed from v0.1:** role model expanded from two to five; information architecture rewritten against the existing menu tree; a current-state assessment added; dark theme promoted from "later" to a first-class deliverable, since the product already ships dark.

---

## 1. Context

This is a redesign of a working product, not a greenfield build. The portal already has a complete module set — self-service, directories, attendance, payroll, recruitment, training, performance, assets, tickets, reports, and a full admin surface including a dynamic sidebar menu manager. The functionality is largely there. What is missing is a point of view about **who each screen is for**.

That gap becomes expensive at five roles. A menu tree that is organised by module works when there are two kinds of user. With employees, managers, recruiters, HR, and super admins sharing one shell, module-shaped navigation means every role has to learn the whole system's layout in order to find their small corner of it.

The redesign's central move: **reorganise around roles and tasks, keep the modules underneath.** Routes stay; the shell that exposes them changes.

---

## 2. Current-state assessment

Findings from the existing portal. Each maps to a change in this document.

| # | Finding | Why it matters | Addressed in |
|---|---|---|---|
| F1 | Navigation is grouped by system module (*Core HR Directories*, *Operations & Finance*, *Talent & Development*) rather than by role or task | An employee must understand the system's internal shape to find *My Leaves*; a recruiter must know that hiring lives under "Talent & Development" | §4 |
| F2 | **No recruiter home.** Employees have *My Self-Service Hub*, managers have *Manager Team Hub*, recruiters have six items scattered inside a category shared with training and appraisals | Recruiters are a primary role with the highest daily usage after HR. They currently have the weakest surface | §4.4, §8.3 |
| F3 | **Three separate ticket systems** — *HR Tickets*, *Support Tickets*, *Admin Tickets* — in three different categories | Same object, three homes. Users guess; tickets land in the wrong queue | §4.6 |
| F4 | The same domain is named three different ways: *My Leaves* / *Leave Management* / *Team Leave Approvals*. Same for attendance and payroll (*My Payslips* vs *Payroll Disbursements*) | Users can't build a mental model when the vocabulary shifts by role | §5 |
| F5 | Invented vocabulary: *Workstation*, *Hub*, *Disbursements*, *Interactive Menu Workstation* | Reads as system-authored, not user-authored. "Hub" appears three times meaning three different things | §5 |
| F6 | Destructive delete buttons are the most visually prominent element on the page — solid red, repeated once per row | Loudest control should be the most common safe action, not the irreversible one | §7.4, §9 |
| F7 | Route identifiers (`my-portal.payslips`, `recruitment-applications.index`) are surfaced in the UI | Correct on a super-admin config screen. Must never appear anywhere else | §8.5 |
| F8 | Saturated blue links and labels on dark navy, much of it at 12–13px | Several combinations fall below 4.5:1. Needs an audited dark palette, not a hue inversion | §6.1, §10 |
| F9 | *My Self-Service Hub* is a flat list of twelve items with no frequency hierarchy — *My Leaves* sits at the same weight as *Book Conference Room* | The two or three things people actually come for are buried in a list | §4.3 |
| F10 | *Dashboard* is a single top-level orphan shared by all roles | Five roles need five homes, not one generic dashboard | §7.2 |
| F11 | Seven top-level categories, one expanding to twelve children, all in one scrolling rail | At full expansion the rail exceeds the viewport. Navigation becomes a scroll problem | §7.1 |
| F12 | No density or layout differentiation between an employee's occasional visit and an HR admin's full working day | Both get the same spacing. One is under-served, the other over-served | §6.3 |

**What is working and should be kept:** the dynamic menu manager (rare and genuinely useful — it makes role-based navigation configurable rather than hard-coded), the dark theme as a real product choice, the global quick-search with `Ctrl K`, and the breadth of the module set.

---

## 3. Roles

Five roles, one shell. Roles are **additive** — a manager is an employee with an extra section, not a different product.

| Role | Volume | Primary device | Session shape | Home |
|---|---|---|---|---|
| **Employee** | Highest headcount | Mobile-first | 2–6 visits/month, one task in mind | My day |
| **Manager** | ~10% of headcount | Desktop + mobile | Short, approval-driven, often on the move | My team |
| **Recruiter** | Small team | Desktop | Hours daily, pipeline-shaped, high context-switching | Pipeline |
| **HR** | Small team | Desktop | Hours daily, record- and queue-shaped | Today's queue |
| **Super admin** | 1–3 people | Desktop | Infrequent, high-consequence | System health |

### 3.1 Role composition rules

1. **Additive stacking.** Employee is the base layer everyone gets. Manager = Employee + Team. Recruiter = Employee + Hiring. HR = Employee + People Ops. Super admin = HR + System.
2. **One person, multiple roles.** A recruiter who manages two juniors holds Employee + Manager + Recruiter simultaneously. Navigation shows all three sections. There is no role *switcher* for held roles — everything is present at once.
3. **Acting on behalf is different from holding a role.** Super admin impersonation is a distinct, loud, logged mode (§8.5).
4. **Permission changes visibility, not layout.** If a role lacks access to an item, the item is absent from navigation entirely — not greyed. Deep links to forbidden routes show a stated reason and a route to request access, never a bare 403.
5. **Density is a role default, not a separate design.** Employee and Manager default to comfortable (16px cells); Recruiter, HR, and Super admin default to compact (8px). Same tokens, one step apart. User-overridable.

---

## 4. Information architecture

The existing menu nodes are re-grouped by role. **Route names are preserved** so this is implementable through the existing Sidebar Menu Manager without touching the router.

### 4.1 Shape

```
Rail                     Visible to
──────────────────────────────────────────────
■ My day                 everyone
  Leave · Attendance · Pay · Performance
  Benefits · Profile · Requests

■ My team                Manager
  Team · Approvals · Attendance · Performance

■ Hiring                 Recruiter, HR
  Pipeline · Openings · Candidates · Referrals

■ People ops             HR
  Employees · Organization · Attendance · Leave
  Payroll · Performance · Training · Assets
  Announcements · Tickets · Reports

■ System                 Super admin
  Roles & access · Settings · Navigation
  Email templates · API keys · Webhooks · Audit log
```

Maximum two levels. No role sees more than four top-level sections. An employee sees exactly one.

### 4.2 Employee — "My day"

Twelve flat items collapse into seven destinations. Low-frequency items become tabs or actions inside the destination that owns them, not rail entries.

| Destination | Absorbs (existing routes) |
|---|---|
| Home | `my-portal.index` |
| Leave | `my-portal.leaves` |
| Attendance | `my-portal.attendance` |
| Pay | `my-portal.payslips`, `my-portal.tax_documents`, `my-portal.conveyance` |
| Performance | `my-portal.performance_feedback` |
| Profile | `my-portal.profile-update` |
| Requests | `my-portal.referrals`, `my-portal.meetings`, `my-portal.resignation`, `hr-tickets.index`, `support-tickets.index` |

*Corporate Benefits* (`my-portal.benefits`) becomes a card on Home rather than a rail item — it is read-mostly and visited rarely.

**Rationale for the "Requests" grouping:** referring a candidate, booking a room, raising a ticket, and filing a resignation are all *"I want to ask the company for something."* They share a mental model even though they hit five different modules. Grouping them by user intent rather than by owning module is the single highest-leverage IA change for employees.

*Resignation Notice* gets special treatment inside Requests — it is not a peer of "book a room." It sits below a divider, in neutral styling, with a confirmation flow (§9).

### 4.3 Frequency hierarchy

Within *My day*, the rail order is fixed by observed frequency, not alphabetically: **Leave → Pay → Attendance → Performance → Profile → Requests.** Leave and Pay account for the large majority of employee sessions and additionally appear as quick actions on Home.

### 4.4 Recruiter — "Hiring" (new)

The role's first real home. Built from existing routes plus two gaps.

| Destination | Route | Note |
|---|---|---|
| Pipeline | `recruitment-applications.index` | The recruiter's home. Kanban by stage, table view toggle |
| Openings | `recruitment-job-posts.index` | |
| Candidates | *(gap)* | A candidate-centric view distinct from application-centric. Same person across multiple openings |
| Referrals | *(recruiter-side view of `my-portal.referrals`)* | Employee submissions currently have no visible recruiter queue |
| Interviews | *(gap; partially `my-portal.meetings`)* | Scheduling currently borrows the conference-room booking flow |

*Job Code Tags* (`recruitment-job-codes.index`) moves to Settings — it is configuration, not daily work.

### 4.5 HR — "People ops"

Largely the existing *Core HR Directories* + *Operations & Finance* + the non-recruitment half of *Talent & Development*, merged and renamed.

*Departments*, *Designations*, and *Companies* collapse into a single **Organization** destination with three tabs. They are three views of one structure and do not each warrant a rail slot.

### 4.6 Tickets — one system, three queues

The three ticket modules unify into **one ticket object with a category field**, presented as:

- **Employee:** a single "Raise a request" entry inside Requests. The employee chooses a category in plain words (*HR question*, *IT / system issue*, *Something else*) and never learns that three backends exist.
- **HR / Super admin:** one Tickets destination with a category filter, defaulting to the queue that role owns.

If the three cannot be merged in the backend for v1, the UI must still present a single entry point and route on category behind the scenes.

---

## 5. Vocabulary

One name per concept, everywhere, regardless of role. The current portal uses a different word for the same thing depending on which module you are standing in.

| Concept | Current names | Standardised |
|---|---|---|
| Time away from work | My Leaves / Leave Management / Team Leave Approvals | **Leave** (context comes from the section, not the label) |
| Time worked | My Attendance / Attendance & Timesheets / Team Attendance | **Attendance** |
| Payment to employees | My Payslips / Payroll Disbursements | **Pay** (employee) / **Payroll** (HR) — the only permitted split, and only because the tasks genuinely differ |
| Grouped nav section | Hub / Workstation / Directories / Manager | **Section.** Never appears in a label |
| Hiring | Recruitment / Talent & Development | **Hiring** |
| Reports | Executive Reports Hub | **Reports** |
| Menu configuration | Sidebar Dynamic Menu Manager / Navigation Menu Manager / Interactive Menu Workstation | **Navigation** |

**Rules:** sentence case everywhere. No *Hub*, *Workstation*, *Center*, *Portal*, or *Manager* in navigation labels — a section is named for its content, not for the fact that it is a section. Drop *My* where the section already establishes ownership (*My day → Leave*, not *My day → My Leaves*).

---

## 6. Design tokens

Tokens are the contract between design and code. No raw hex in components. **Both themes are authored together** — dark is already the product's character and stays a first-class theme, not an afterthought.

### 6.1 Colour

| Token | Light | Dark | Use |
|---|---|---|---|
| `--canvas` | `#F4F6F5` | `#0E1620` | App background |
| `--surface` | `#FFFFFF` | `#17222E` | Cards, tables, sheets |
| `--surface-raised` | `#FFFFFF` | `#1E2B39` | Modals, dropdowns, rail |
| `--ink` | `#16202B` | `#EDF1F4` | Primary text |
| `--slate` | `#5B6B78` | `#93A3B1` | Secondary text, labels |
| `--line` | `#E2E7E5` | `#2A3846` | Borders, dividers, rules |
| `--fern` | `#2F7A63` | `#4FA98C` | Primary action, active nav, focus ring |
| `--fern-wash` | `#E4F0EA` | `#1B3A33` | Selected rows, primary tint |
| `--marigold` | `#B07A14` | `#E8B44A` | Pending / attention status |
| `--marigold-wash` | `#FBF1DC` | `#3A2F17` | Warning banners |
| `--clay` | `#BE4A3F` | `#E4796D` | Destructive, error, rejected |
| `--clay-wash` | `#FBE9E7` | `#3B1F1C` | Error banners |
| `--harbor` | `#3A6EA5` | `#6FA3D8` | Informational status only |

**Decision — primary colour.** The current portal uses saturated blue as its action colour on dark navy. Two problems: blue-on-navy has almost no luminance headroom (several current combinations sit below 4.5:1 at 13px), and blue is simultaneously the brand chrome, so nothing distinguishes "this is clickable" from "this is our logo."

Recommendation: **keep navy as the dark canvas — it is the product's existing character and worth preserving — and move the action colour to fern.** Green separates cleanly from navy at both ends of the scale, tests well for the common colour-vision deficiencies against blue-heavy chrome, and leaves the blue free to remain brand identity in the logo and header. The I2k2 mark is unchanged.

*Alternative if brand mandates blue:* shift the primary to `#4C8DD8` in dark and `#1F5FA8` in light, and drop navy surfaces one step darker to buy contrast. This is workable but noticeably less legible, and it keeps the "everything is blue" ambiguity.

**Rules**

- `--fern` is the **only** interactive colour. Links, primary buttons, active nav, focus rings, selected rows.
- Status colours are semantic only. Marigold never becomes decoration.
- Colour never carries meaning alone — every status pairs a dot glyph, a colour, and a word.
- Destructive red is reserved for **irreversible** actions. Ordinary delete uses a ghost button that turns clay on hover (§9).

### 6.2 Typography

| Role | Face | Fallback | Usage |
|---|---|---|---|
| Display | **Fraunces** (variable, `soft` 40, 500–600) | Georgia, serif | Page titles, the Day greeting, empty-state headlines. Never below 24px, never for UI labels |
| Body / UI | **Figtree** (400/500/600) | -apple-system, Segoe UI, sans-serif | Everything else |
| Data | **IBM Plex Mono** (400/500) | ui-monospace, monospace | Currency, employee IDs, dates in tables, balances, route identifiers on admin screens |

Fraunces appears roughly once per screen. The contrast between a soft serif headline and a clean geometric UI face is where the human quality comes from — not from illustration or rounded corners.

**Scale** (1.25 ratio, rem-based, 16px root)

| Token | Size / line-height | Face |
|---|---|---|
| `display-lg` | 40 / 46 | Fraunces 600 |
| `display-md` | 32 / 38 | Fraunces 600 |
| `title` | 24 / 32 | Figtree 600 |
| `heading` | 18 / 26 | Figtree 600 |
| `body` | 16 / 26 | Figtree 400 |
| `body-sm` | 14 / 22 | Figtree 400 |
| `label` | 13 / 18, +0.04em, uppercase | Figtree 600 |
| `data` | 14 / 20, tabular figures | Plex Mono 500 |

`font-variant-numeric: tabular-nums` is mandatory in every table and balance display. **Minimum text size anywhere is 13px** — the current portal drops below this in badges and route tags.

### 6.3 Spacing, radius, elevation

- **Spacing scale:** 4, 8, 12, 16, 24, 32, 48, 64. No arbitrary values.
- **Density:** comfortable = 16px cell padding; compact = 8px. Set by role default (§3.1), overridable per user, persisted.
- **Radius:** `--r-sm` 8px (inputs, buttons, chips), `--r-md` 12px (cards, rows), `--r-lg` 20px (modals, the Day surface).
- **Elevation:** two levels only. `--e1` for cards at rest, `--e2` for modals and dropdowns. In dark theme, elevation is expressed by surface lightness (`--surface` → `--surface-raised`), not by shadow — shadows are invisible on navy, which is why the current portal's cards read as flat outlines.
- **Content width:** 1160px max for admin views, 880px for employee reading, 640px per form column.

### 6.4 Motion

| Token | Duration / easing | Use |
|---|---|---|
| `--m-fast` | 120ms `cubic-bezier(.2,0,.2,1)` | Hover, focus, chip toggle |
| `--m-base` | 200ms `cubic-bezier(.2,0,0,1)` | Dropdowns, expansion, tab change |
| `--m-sheet` | 280ms `cubic-bezier(.32,.72,0,1)` | Mobile sheets, drawers |

Motion shows where things came from. No page-load animation, no scroll reveals, no counting-up numbers — an animated payslip figure is unserious. `prefers-reduced-motion: reduce` replaces transforms with sub-100ms opacity fades. Drag-and-drop (menu manager, pipeline) is the one place where motion is load-bearing and gets a dedicated spec.

---

## 7. Layout & navigation

### 7.1 The rail

240px expanded, 64px collapsed, state persisted. Fixes F11:

- **Maximum two levels.** Current three-level nesting is removed.
- **One section expanded at a time** (accordion). Opening *People ops* collapses *My day*.
- **Sections are collapsed by default except the one containing the current page.**
- Active item marked with a `--fern` left bar **and** `--fern-wash` fill — never colour alone.
- Section headers are not clickable destinations; they toggle. Every leaf is a real page.

### 7.2 Five homes

`Dashboard` as a shared top-level orphan is removed (F10). Each role's first rail item **is** its home, and the home is role-specific:

| Role | Home | Answers |
|---|---|---|
| Employee | My day | "Is anything waiting on me?" |
| Manager | My team | "Who needs my approval, and who's out this week?" |
| Recruiter | Pipeline | "Which candidates are stalling?" |
| HR | Today's queue | "What's in the queue and what's ageing?" |
| Super admin | System health | "Is anything broken or unusual?" |

For a user holding several roles, the landing page is the highest-privilege home, with the others one click away.

### 7.3 Top bar (56px)

Global search (keep `Ctrl K` — it works), notifications, theme toggle, avatar menu. The search is upgraded from navigation-only to **entity search**: people, candidates, tickets, and documents, grouped by type, with the role's own entities ranked first.

### 7.4 Breakpoints

| Name | Range | Shell |
|---|---|---|
| `sm` | < 640px | Bottom tabs (5 max), single column, sheets for detail |
| `md` | 640–1023px | Collapsed icon rail, single column, drawers |
| `lg` | 1024–1439px | Expanded rail + content |
| `xl` | ≥ 1440px | Expanded rail + optional 320px context panel |

**Mobile:** bottom tabs are Home · Leave · Pay · Requests · More for employees; managers swap Requests for Approvals; recruiters get Pipeline · Candidates · Openings · Interviews · More. HR and super-admin surfaces are desktop-primary — on mobile they degrade to read + approve only, and say so rather than presenting a broken table.

Tables never scroll horizontally on mobile; they become stacked record cards with the two most important fields promoted. Tap targets ≥ 44×44px.

---

## 8. Screens by role

Each needs hi-fi designs at `sm` and `lg`, in both themes, with empty / loading / error / no-permission states.

### 8.1 Employee

| Screen | Intent | Must include |
|---|---|---|
| **My day** | Signature surface (§9). Pending items first | Greeting, 0–4 action cards, leave balance, next holiday, quick actions |
| **Apply for leave** | Stepped, calendar-first | Team-overlap shading, balance shown against the request, policy note inline, half-day toggle, **approver named before submit** |
| **Pay** | Calm and precise, Plex Mono throughout | Latest payslip card, 12-month list, one-tap PDF, tabs for tax documents and conveyance claims |
| **Payslip detail** | Explains itself | Earnings/deductions breakdown, expandable "how this was calculated", download and email |
| **Profile** | Progressive disclosure by section | Edits requiring approval marked **before** saving, not after |
| **Requests** | One place to ask for things | Referral, room booking, ticket, resignation (below divider, neutral styling) |

### 8.2 Manager

| Screen | Intent | Must include |
|---|---|---|
| **My team** | Who's out, who's waiting | This-week absence strip, pending approvals, team roster |
| **Approvals** | Fast, keyboard-driven | Leave + profile-change requests unified, inline approve/reject with reason, ageing indicator, `j/k` navigation, bulk select |
| **Team attendance** | Exceptions, not a grid | Anomalies surfaced first; full grid available but secondary |

*HR Profile Approvals* is renamed **Profile change requests** — the current name suggests HR is the actor when it is the manager.

### 8.3 Recruiter

| Screen | Intent | Must include |
|---|---|---|
| **Pipeline** | The role's home. Kanban by stage | Stage columns with counts, **stall indicator** (days in stage), drag to advance with a required outcome note, table-view toggle |
| **Candidate** | One person, all applications | Timeline, resume viewer, interview history, scorecards, source |
| **Openings** | Status at a glance | Open/on-hold/closed, days open, applicant count, hiring manager |
| **Referrals** | Close the employee loop | Recruiter queue for employee submissions, with status pushed back to the referrer |
| **Interviews** | Scheduling without leaving | Panel availability, room booking inline, candidate-facing confirmation |

Pipeline drag-and-drop is the recruiter's most repeated interaction and gets a dedicated motion and keyboard spec — every drag action must have a keyboard equivalent.

### 8.4 HR

| Screen | Intent | Must include |
|---|---|---|
| **Today's queue** | Density and keyboard speed | Category chips, bulk select, inline actions, ageing, saved views |
| **Employees** | The workhorse table | Sticky header, column chooser, saved filters, export, row → drawer |
| **Employee record** | One person, all tabs | Header with photo/status/manager, tabs, audit trail |
| **Payroll** | High-consequence, low-drama | Run status, exceptions before totals, locked states clearly marked |
| **Organization** | Departments, designations, companies as one | Three tabs, org chart view |
| **Reports** | Charts only where a decision follows | Headcount, attrition, leave liability, absence patterns; all exportable |

### 8.5 Super admin

Highest consequence, lowest volume. Design for **caution**, not speed.

| Screen | Intent | Must include |
|---|---|---|
| **System health** | Is anything wrong? | Failed jobs, webhook failures, integration status, recent config changes |
| **Roles & access** | Who can see what | Role matrix, permission diff preview **before** save, "what changes for whom" summary |
| **Navigation** | The menu manager (§8.6) | Per-role preview, drag-and-drop, unsaved-change guard |
| **Email templates** | Safe editing | Variable picker, live preview, test send |
| **API keys / Webhooks** | Credentials handled carefully | Masked by default, one-time reveal on creation, last-used timestamp, delivery log |
| **Audit log** | *(gap — new)* | Who did what to whom, filterable, exportable. Required for impersonation to be acceptable |

**Impersonation.** If super admins can view as another user, it needs: an explicit start action with a stated reason, a **persistent full-width bar** in `--marigold-wash` reading `Viewing as Anjali Rao. All actions are logged. [Stop]`, a hard block on payroll and destructive actions while impersonating, and an audit entry on start and stop. This is the single most sensitive interaction in the product.

### 8.6 Worked example — the Navigation screen

The screen shown is the right idea executed at the wrong visual weight. Specific changes:

1. **Delete is currently the loudest element on the page** — solid red, once per row, ~60 instances. It becomes a ghost icon button in `--slate`, turning `--clay` on hover, with a confirmation for any node that has children.
2. **Two different drag glyphs** (`≡` on categories, `⠿` on items) for the same affordance. One glyph, one size, `--slate`, revealed on row hover.
3. **Route badges** (`my-portal.payslips`) are correct here — this is the one screen where system language belongs. Set them in Plex Mono at 13px, `--slate` on `--surface-raised`, and drop the coloured pill treatment so they stop competing with content.
4. **Add a role preview.** A row of role chips at the top that filters the tree to what each role would see. Without it, an admin editing a five-role menu is working blind — this is the feature that makes the whole role model manageable.
5. **Unsaved-change state.** The save button currently sits at the bottom of a very long page. Make it a sticky footer bar that appears only when the tree is dirty, with a count: `12 changes. [Discard] [Save layout]`. Guard on navigate-away.
6. **Collapse categories by default.** The full tree at once is a ~2,500px scroll.
7. **Empty category** (the current *Dashboard* node shows an empty drop zone) gets copy: `No items yet. Drag a link here, or add one.`

---

## 9. Signature element — "My day"

One component, five variants, same geometry and type ramp. It is the thing the product is remembered by.

**Employee**

```
┌────────────────────────────────────────────────────┐
│  Good morning, Priya.                              │  ← Fraunces 32/38
│  Two things need you this week.                    │
│                                                    │
│  ┌──────────────────────┐ ┌─────────────────────┐  │
│  │ ● Timesheet due Fri  │ │ ● Confirm your PAN  │  │
│  │   4 days unfilled    │ │   Needed for payroll│  │
│  │   [Fill timesheet]   │ │   [Add details]     │  │
│  └──────────────────────┘ └─────────────────────┘  │
│                                                    │
│  Nothing else is waiting. You have 18 days of      │  ← Figtree 16/26
│  leave left, and the next holiday is in 12 days.   │
└────────────────────────────────────────────────────┘
```

**HR** — same component, same geometry, content becomes the queue:

```
│  Wednesday, 19 August.                             │
│  14 approvals waiting. 3 are older than 48 hours.  │
│  [Leave · 9] [Reimbursements · 3] [Profile · 2]    │
```

**Recruiter:** `6 candidates are waiting on you. 2 have been in Interview for over a week.`
**Manager:** `4 approvals waiting. Three of your team are out on Thursday.`
**Super admin:** `Everything is running. 2 webhook deliveries failed overnight.`

**Why this instead of a KPI tile grid:** a row of stat tiles is the default answer for every portal and tells an employee nothing actionable. This states the *job*, then offers the *action*. When nothing is pending it says so and stops — an empty My day is a feature, and its copy gets as much care as its full state.

---

## 10. Content & voice

Copy carries most of the warmth. Layout stays disciplined.

- **Sentence case everywhere.** No Title Case buttons, no ALL CAPS except the `label` token.
- **Name the action and keep the name.** *Apply for leave* produces *Leave applied*. Never *Submit*, never *OK*.
- **Say what happens next, with a time.** `Sent to Rahul Menon for approval. Most requests are answered within 2 working days.`
- **Errors explain and repair.** Not *Invalid input* but *Enter a date on or after your joining date, 14 March 2023.* No apologies, no vagueness.
- **Empty states invite.** Not *No records found* but *No leave requests yet. You have 18 days available.* with the action attached.
- **Plain words over HR jargon.** *Time off* not *absence management*. *Your manager* not *reporting authority*. *Take-home pay* alongside *net pay*.
- **Sensitive flows drop the warmth a notch.** Resignation, exit, disciplinary, and payroll-correction screens use neutral, precise language. Friendliness in the wrong moment reads as tone-deaf.

---

## 11. Accessibility

Non-negotiable, both themes.

- WCAG 2.2 AA on every shipped screen, audited before each release. **The dark theme is audited independently** — passing in light does not imply passing in dark.
- Visible `focus-visible` ring: 2px `--fern`, 2px offset. Never removed.
- Full keyboard operability including approval queues, tables, the pipeline board, and the menu manager. Every drag interaction has a keyboard equivalent.
- Persistent visible labels on all inputs; placeholders are not labels. Accessible names on all icon-only controls.
- Status and errors announced via `aria-live`. Modals trap and restore focus.
- 200% zoom and OS text scaling supported without content loss.
- Verified against the two most common colour-vision deficiencies; no state distinguishable by hue alone.

---

## 12. Component inventory

**Foundations:** button (primary / secondary / ghost / destructive), icon button, input, textarea, select, combobox, date picker, date-range picker, checkbox, radio, switch, file upload, form field wrapper.

**Display:** card, status chip, badge, avatar + group, table (sortable, selectable, sticky header), record card (mobile table substitute), list row, tabs, accordion, timeline, empty state, skeleton.

**Feedback:** toast, inline alert (info / attention / error / success), confirmation modal, destructive confirmation (type-to-confirm for irreversible actions), impersonation bar, unsaved-changes bar, progress, stepper.

**Navigation:** rail, bottom tabs, top bar, breadcrumb, pagination, filter bar, drawer, mobile sheet, role preview chips.

**Domain:** leave calendar, balance meter, payslip breakdown, approval row, pipeline board + card, candidate header, org chart node, document tile, ticket row, menu tree node.

Every component ships with default, hover, focus-visible, active, disabled, loading, error, and both density variants — in both themes.

---

## 13. Success metrics

| Metric | Target |
|---|---|
| Leave request completion (started → submitted) | ≥ 92% |
| Median time to download a payslip from landing | ≤ 20s |
| Mobile share of employee sessions | ≥ 45% by month 3 |
| Median time per approval (manager & HR) | ≤ 15s |
| Recruiter: median time to advance a candidate | ≤ 10s |
| Tickets filed in the wrong queue | ≤ 5% (from current baseline — measure first) |
| Support tickets tagged "can't find / how do I" | −40% |
| Automated accessibility violations, both themes | 0 critical, 0 serious |
| SUS score, employee cohort | ≥ 80 |

---

## 14. Phases

**Phase 0 — Baseline (3 days).** Instrument the current portal: which routes are actually used, by which role, how often. The IA proposal in §4 is reasoned from structure; it should be confirmed with data before it is locked.

**Phase 1 — Direction (1–2 weeks).** Two visual directions applied to *My day* and the leave flow, at `sm` and `lg`, in both themes. Pick one. Lock tokens.

**Phase 2 — System (2–3 weeks).** Figma library: tokens as variables, both themes, both densities, full state matrices. Coded token file published alongside.

**Phase 3 — Screens (3–4 weeks).** Hi-fi for all screens in §8. Interactive prototypes for the leave flow, the approval queue, and the recruiter pipeline.

**Phase 4 — Handoff & QA.** Component documentation with usage rules and do/don't examples, design QA against the build, accessibility audit in both themes.

**Suggested build order** (highest ratio of impact to effort): rail restructure → My day → leave flow → approvals → pipeline → HR tables → admin surfaces.

---

## 15. Open questions

1. **Are these five roles fixed, or is the role system itself configurable?** The presence of a *User Roles & Access* module suggests roles may be user-defined. If so, the navigation model must handle arbitrary roles, and §3 becomes a set of default templates rather than a fixed list.
2. **Can the three ticket modules be merged?** If not in v1, confirm the UI may present a single entry point and route on category.
3. **Region and language.** RTL and Indic/CJK coverage materially affect the type choices — Fraunces has no Devanagari.
4. **Does the brand mandate blue as the action colour?** §6.1 recommends otherwise and gives the fallback.
5. **Non-desk employees?** Would raise the priority of large tap targets, offline tolerance, and a kiosk mode.
6. **Scale of the employees table.** Above ~10k rows it needs virtualisation and a different filtering model.
7. **Does super-admin impersonation exist today, and is there an audit log behind it?** If impersonation ships without audit, that is a compliance issue before it is a design issue.
8. **Payroll visibility:** are figures shown to HR in full, or masked by default?
9. **Browser support floor**, and is there an existing component library (Tailwind, Bootstrap, custom) the system must extend rather than replace?
