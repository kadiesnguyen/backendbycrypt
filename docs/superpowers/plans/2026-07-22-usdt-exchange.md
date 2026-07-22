# USDT Exchange Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make `/convert` work for USDT ↔ other coins via quote + exchange APIs, with confirm modal and history drawer.

**Architecture:** Thin glue on existing finance exchange. Extract Binance rate helper; add quote endpoint; rewire Convert.tsx.

**Tech Stack:** Laravel API, Next.js client, MUI, existing `User.service` helpers.

## Global Constraints

- One of from/to must be `usdt`
- Fee always `0`
- Instant settle after confirm
- Coin list from `GET /api/finance/coins`
- No new npm/composer deps
- Do not commit unless user asks

## File map

| File | Role |
|---|---|
| `backend/laravel/app/Services/BinanceTickerService.php` | Shared USDT rate fetch |
| `backend/laravel/app/Http/Controllers/Api/FinanceController.php` | quote + refactor exchange |
| `backend/laravel/routes/api.php` | register quote route |
| `backend/laravel/app/Console/Commands/ExchangeQuoteSelfCheck.php` | assert quote math |
| `client/src/services/User.service.tsx` | `apiExchangeQuote` |
| `client/src/pages/DepositWithdraw/Convert.tsx` | full rewire |

### Task 1: BinanceTickerService + quote API

**Files:**
- Create: `backend/laravel/app/Services/BinanceTickerService.php`
- Create: `backend/laravel/app/Console/Commands/ExchangeQuoteSelfCheck.php`
- Modify: `FinanceController.php` exchange + new quote
- Modify: `routes/api.php`

- [ ] Extract `rateToUsdt(string $coin): ?float` (usdt → 1.0)
- [ ] Add `quote()` mirroring exchange validation without balance mutate
- [ ] Refactor `exchange()` to use service
- [ ] Route `POST /api/finance/exchange/quote`
- [ ] Self-check: received = amount * fromRate / toRate

### Task 2: Client service + Convert UI

**Files:**
- Modify: `User.service.tsx`
- Modify: `Convert.tsx`

- [ ] Add `apiExchangeQuote(from, to, amount)`
- [ ] Coin picker drawer, flip, debounce quote, max, confirm modal, history drawer
- [ ] Remove dead `ConvertUSDT` usage from Convert
- [ ] Balance from `getMyWallet` → `balance.available` by coin name

### Task 3: Verify

- [ ] Run ExchangeQuoteSelfCheck
- [ ] Lint/typecheck client if available
