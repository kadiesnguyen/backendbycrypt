# Loan Support Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Ship Vay hỗ trợ end-to-end: client apply/history, Laravel loan APIs + auto-repay cron, admin list/approve/reject + settings.

**Architecture:** `tw_loan` + singleton `tw_loan_setting`; `LoanService` owns apply/approve/reject/settle; client under `/finance/loan`; admin mirrors deposits under `/finance/loans` + `/finance/loan-settings`.

**Tech Stack:** Laravel 11, Next.js admin-frontend, Next.js client, MySQL `tw_*` tables, JWT auth.

**Spec:** `docs/superpowers/specs/2026-07-22-loan-support-design.md`

## Global Constraints

- Currency: USDT only
- Bill types: 18 disbursement, 19 repayment
- Open loan statuses blocking apply: `pending`, `active`, `overdue`
- KYC required: `rzstatus === 2`
- Tables: `tw_loan`, `tw_loan_setting`
- Overdue does not increase interest beyond snapshotted `interest_amount`
- Note set only on approve/reject (v1)

## File map

| Area | Create / Modify |
|---|---|
| Migration | `laravel/database/migrations/2026_07_22_130000_create_loan_tables.php` (+ seed settings + menu) |
| Models | `Loan.php`, `LoanSetting.php` |
| Service | `LoanService.php` |
| API | `Api/LoanController.php`, routes in `api.php` |
| Admin | `Admin/LoanController.php`, `Admin/LoanSettingController.php`, resources/requests, `admin.php`, dashboard badge |
| Cron | `ProcessLoanRepayments.php`, `LoanMathSelfCheck.php`, `console.php` |
| Admin FE | `features/finance/loans/*`, `features/finance/loan-settings/*`, pages, menu-routes, i18n, pending-counts |
| Client | `Loan.service.tsx`, wire `Loan.page.tsx`, `LoanHistoryPage.tsx`, detail page |

---

### Task 1: Schema + models + LoanService core

**Files:**
- Create: migration, `Loan.php`, `LoanSetting.php`, `LoanService.php`, `LoanMathSelfCheck.php`

- [ ] **Step 1:** Create `tw_loan` + `tw_loan_setting` (seed defaults + insert `Finance/loan` menu next to `Finance/myzc`)
- [ ] **Step 2:** Implement `LoanService` methods: `settings()`, `canApply()`, `apply()`, `approve()`, `reject()`, `processDueRepayments()`, interest math helpers
- [ ] **Step 3:** Add `php artisan app:loan-math-self-check` asserting interest + settle branches (mock or DB transaction)
- [ ] **Step 4:** Commit backend schema/service

### Task 2: Client + Admin API controllers

**Files:**
- Create: `Api/LoanController`, `Admin/LoanController`, `Admin/LoanSettingController`, resources, form requests
- Modify: `routes/api.php`, `routes/admin.php`, `DashboardController::pendingCounts`

- [ ] **Step 1:** Wire client routes under `finance/loan`
- [ ] **Step 2:** Wire admin loans + loan-settings + pending `loans` count
- [ ] **Step 3:** Commit

### Task 3: Settlement cron

**Files:**
- Create: `ProcessLoanRepayments.php`
- Modify: `routes/console.php`

- [ ] **Step 1:** Command calls `LoanService::processDueRepayments()`
- [ ] **Step 2:** Schedule every minute `withoutOverlapping`
- [ ] **Step 3:** Commit

### Task 4: Admin frontend

**Files:**
- Create: `features/finance/loans/*`, `features/finance/loan-settings/*`, dashboard pages
- Modify: `menu-routes.ts`, `pending-counts/api.ts`, `vi.ts`, `en.ts`, `FALLBACK_MENU_TREE`

- [ ] **Step 1:** List + approve/reject (optional note) + image preview
- [ ] **Step 2:** Settings form
- [ ] **Step 3:** Menu map `Finance/loan` → `/finance/loans`, badge key `loans`; settings via page tab or `/finance/loan-settings`
- [ ] **Step 4:** Commit

### Task 5: Client wire-up

**Files:**
- Create: `client/src/services/Loan.service.tsx`, history detail page
- Modify: `Loan.page.tsx`, `LoanHistoryPage.tsx`, export from User.service or dedicated service

- [ ] **Step 1:** Service methods for config/apply/history/detail
- [ ] **Step 2:** Form: amount input, live interest, submit multipart
- [ ] **Step 3:** History tabs + detail
- [ ] **Step 4:** Commit client

### Task 6: Verify

- [ ] Run `php artisan migrate` (or confirm migration SQL)
- [ ] Run `php artisan app:loan-math-self-check`
- [ ] Smoke: config → apply → admin approve → wallet +bill → force due → repay

---

## Spec coverage checklist

- [x] Apply with KYC + images + min/max
- [x] One open loan
- [x] Admin approve credits + due_at
- [x] Admin reject + note
- [x] Cron settle / overdue retry
- [x] History filters
- [x] Settings CMS
- [x] Pending badge
- [x] Client UI wiring
