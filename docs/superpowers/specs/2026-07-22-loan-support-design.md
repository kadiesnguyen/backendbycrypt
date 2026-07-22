# Loan Support (Vay hỗ trợ) — Design Spec

Date: 2026-07-22  
Status: Approved for planning  
Repos: `bycrypt` client + Laravel API + admin-frontend

## Goal

Wire the existing client screens **Vay hỗ trợ** and **Lịch sử vay** to real APIs, and add admin management for loan review + package settings. Full lifecycle: apply → admin approve/reject → credit USDT → auto-collect principal+interest at due date.

## Decisions (locked)

| Topic | Choice |
|---|---|
| On approve | Credit USDT + create repayment obligation |
| On due | Auto-debit `repay_amount` from USDT; if insufficient → `overdue`, retry later |
| Amount | User enters within admin min–max; other terms from config |
| Concurrency | At most one loan in `pending` / `active` / `overdue` |
| Eligibility | KYC approved (`rzstatus === 2`) required; form still uploads 2 images |
| Architecture | Dedicated `loans` table + settings + scheduled settle command |

## Out of scope

- Multi-product loan catalog / installment schedules
- Partial repayments / early payoff UI
- Interest compounding after overdue (rate frozen at approve/submit snapshot)
- Changing existing `users.loan` / `users.img_loan` profile fields (document-type KYC helpers; unrelated to applications)

## Data model

### Table `loans`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `user_id` | bigint | FK logic to `tw_user.id` |
| `username` | string | denormalized for admin list |
| `amount` | decimal | principal USDT |
| `duration_days` | int | snapshot from settings |
| `daily_interest_rate` | decimal | decimal fraction, e.g. `0.0004` for 0.04%/day |
| `lender_name` | string | snapshot |
| `interest_amount` | decimal | `amount * daily_interest_rate * duration_days` |
| `repay_amount` | decimal | `amount + interest_amount` |
| `status` | string | see lifecycle |
| `note` | text nullable | admin note (shown on history) |
| `img_front` | string | public storage URL/path |
| `img_back` | string | public storage URL/path |
| `approved_at` | datetime nullable | |
| `due_at` | datetime nullable | `approved_at + duration_days` |
| `repaid_at` | datetime nullable | |
| `created_at`, `updated_at` | datetime | |

Indexes: `(user_id, status)`, `(status, due_at)`.

### Table `loan_settings` (singleton row id=1)

| Column | Type | Default (seed) |
|---|---|---|
| `enabled` | bool | true |
| `min_amount` | decimal | 1000 |
| `max_amount` | decimal | 200000 |
| `duration_days` | int | 7 |
| `daily_interest_rate` | decimal | 0.0004 |
| `lender_name` | string | ICICI BANK |
| `updated_at` | datetime | |

### Status lifecycle

```
pending ──approve──► active ──due settle OK──► repaid
   │                    │
   │                    └── insufficient ──► overdue ──retry settle OK──► repaid
   └──reject──► rejected
```

Open statuses blocking a new application: `pending`, `active`, `overdue`.

## Wallet & ledger

Reuse existing patterns from deposit/withdraw:

- Wallet: `tw_user_coin.usdt`
- Ledger: `Bill` rows with distinct `type` / `remark` for loan credit and loan repayment
- Notifications: `Notice` on approve, reject, repay success, overdue

Approve (transaction):

1. Lock loan (`pending`) + user coin
2. Set `status=active`, `approved_at=now`, `due_at=now+duration_days`, optional `note`
3. Increment `usdt` by `amount`
4. Create Bill (loan disbursement) + Notice

Reject (transaction):

1. Lock loan (`pending`)
2. Set `status=rejected`, optional `note`
3. Notice only (no wallet change)

Settle cron (transaction per loan):

1. Select `active` with `due_at <= now`, plus `overdue` rows
2. Lock loan + user coin
3. If `usdt >= repay_amount`: decrement, Bill, Notice, `status=repaid`, `repaid_at=now`
4. Else if currently `active`: set `overdue` + Notice; if already `overdue`, leave status and retry next run

## API

### Client (`auth:api`, prefix `/finance/loan`)

| Method | Path | Purpose |
|---|---|---|
| GET | `/finance/loan/config` | Settings + computed preview helpers; includes `can_apply` + reason if blocked |
| POST | `/finance/loan` | multipart: `amount`, `img_front`, `img_back` |
| GET | `/finance/loan/history?status=` | `all` \| `pending` \| `rejected` \| `active` \| `repaid` \| `overdue` |
| GET | `/finance/loan/{id}` | Detail for current user only |

Client history tabs map:

- Tất cả → `all`
- Đang được phê duyệt → `pending`
- Thất bại trong phê duyệt → `rejected`

(Other statuses still appear under `all`.)

### Admin

| Method | Path | Purpose |
|---|---|---|
| GET | `/admin/loans` | Paginated list; filter `username`, `status` |
| POST | `/admin/loans/{id}/approve` | Optional body `note` |
| POST | `/admin/loans/{id}/reject` | Optional body `note` |
| GET | `/admin/loan-settings` | Read settings |
| PUT | `/admin/loan-settings` | Update settings |
| Dashboard badge | pending loans count | Same pattern as deposits/withdrawals |

## Admin UI

- Sidebar item **Vay hỗ trợ** near finance (deposits/withdrawals)
- List page: username, amount, interest, repay, status, created, actions (approve/reject when pending), view images, edit note
- Settings page or section: min/max, days, daily rate, lender, enabled
- i18n vi/en keys for nav + page copy

## Client UI changes

- `Loan.page.tsx`: load config; editable amount within min–max; show live interest/repay; real POST with both images; gate UX when KYC incomplete or open loan exists
- `LoanHistoryPage.tsx`: fetch history by tab; remove hardcoded card; detail route uses `GET /finance/loan/{id}`
- New `Loan.service` (or finance service methods) following existing axios patterns

## Scheduler

- Command: `app:process-loan-repayments`
- Schedule: every minute, `withoutOverlapping()` in `routes/console.php`
- Self-check command or assert-based demo covering: create → approve → credit → force due → debit

## Validation & errors (422 unless noted)

- Settings disabled → cannot apply
- `rzstatus !== 2` → cannot apply
- Open loan exists → cannot apply
- Amount outside min–max → cannot apply
- Missing/invalid images → cannot apply
- Approve/reject non-pending → already processed
- Detail of another user's loan → 404

## File touch list (expected)

**Backend Laravel**

- migration(s): `loans`, `loan_settings`
- `app/Models/Loan.php`, `LoanSetting.php`
- `app/Services/LoanService.php` (apply/approve/reject/settle)
- `app/Http/Controllers/Api/LoanController.php`
- `app/Http/Controllers/Admin/LoanController.php`, `LoanSettingController.php`
- Form requests + resources
- `routes/api.php`, `routes/admin.php`, `routes/console.php`
- Console command + optional self-check
- Dashboard pending badge

**Admin frontend**

- `features/loans/*`, page under dashboard, nav, i18n, API client

**Client**

- loan pages + service wiring

## Testing plan

1. Unit/self-check: interest math + settle branches (enough / not enough balance)
2. Manual API: apply without KYC fails; apply twice while pending fails
3. Admin: approve credits wallet; reject does not; settings clamp client amount
4. Cron: due loan with balance → repaid; without → overdue then repaid after top-up
5. Client: history tabs + detail; mobile 375 / no horizontal scroll on loan screens

## Non-goals / explicit defaults

- Currency fixed to USDT
- Payment method fixed copy: one-time at maturity (no multi-cycle billing)
- Overdue does not increase interest beyond snapshotted `interest_amount`
- Admin cannot delete settled loans in v1 (list + approve/reject only)
