# USDT Exchange (Trao đổi) Design

**Date:** 2026-07-22  
**Status:** Approved  
**Scope:** Wire Convert UI to existing finance exchange APIs; add server quote; coin picker; confirm modal; history drawer.

## Goal

Cho phép user đổi **USDT ↔ coin khác** trên màn `/convert`, settle ngay sau confirm, phí 0. Danh sách coin lấy động từ `tw_coin`.

## Context

- UI sẵn: `client/src/pages/DepositWithdraw/Convert.tsx` (layout đúng, logic hỏng).
- API sẵn: `POST /api/finance/exchange`, `GET /api/finance/exchange/history`, `GET /api/finance/coins`, `GET /api/finance/balance`.
- Client helper sẵn: `apiExchange`, `getHistoryExchange`, `getFinaceCoin`, `getMyWallet` — Convert chưa dùng.
- `ConvertUSDT` → `/api/finance/convert-usdt` **không tồn tại** trên backend; bỏ khỏi Convert.

## Decisions

| Topic | Choice |
|---|---|
| Approach | Thin glue: reuse exchange + add quote |
| Coin list | Dynamic from `tw_coin` via `getFinaceCoin` |
| Direction | Two-way USDT ↔ coin (flip); always exactly one side is USDT |
| Settlement | Instant after confirm modal |
| Quote | Server-side quote, same Binance source as exchange |
| History | Drawer/popup on Convert page (not separate route) |
| Fee | Always 0 |

## Architecture

```
Convert UI
  ├─ getFinaceCoin()           → coin list (status=1)
  ├─ getMyWallet()             → from-coin balance
  ├─ POST exchange/quote       → estimated receive (debounced)
  ├─ confirm modal             → from/to/amount/received/rate/fee
  ├─ POST exchange             → settle + refresh wallet
  └─ history drawer            → GET exchange/history
```

Backend:
- Extract shared Binance USDT-rate helper used by quote + exchange.
- New `POST /api/finance/exchange/quote` — same validation as exchange except balance check; no balance mutation.
- Keep rule: one of `from`/`to` must be `usdt`.

## UI

| Component | Behavior |
|---|---|
| From / To | Bottom sheet picker from `getFinaceCoin`. Always exactly one side USDT. Selecting a non-USDT coin forces the other side to USDT. |
| Flip | Swap from/to; re-quote. |
| From input | Debounce ~300ms → quote → fill To (read-only). |
| Max | Fill from-wallet balance of from-coin. |
| Request | Validate amount > 0 and ≤ balance → open confirm modal. |
| Confirm | Show from/to/amount/received/rate/fee=0. Cancel / Confirm. Confirm → `exchange` → toast + refresh + clear inputs. |
| History | Clock icon opens drawer listing `IHistoryExchange`; paginate if more pages. |

Do not change bottom nav, Trade, or Perp.

## API

### `POST /api/finance/exchange/quote`

Request:
```json
{ "from": "usdt", "to": "sol", "amount": 100 }
```

Response 200:
```json
{
  "status": true,
  "data": {
    "from": "usdt",
    "to": "sol",
    "amount": "100.00000000",
    "received": "1.23456000",
    "from_rate_usdt": "1",
    "to_rate_usdt": "81.05",
    "fee": "0"
  }
}
```

### `POST /api/finance/exchange`

Existing behavior unchanged. Optional non-breaking addition: `fee: "0"` in success `data`.

### Errors (quote + exchange)

- 422: missing/invalid fields, amount ≤ 0, from=to, coin missing, neither side USDT, insufficient balance (exchange only), ticker unavailable
- 500: `BINANCE_TICKER_URL` not configured

### Client toast

- Quote fail → clear estimated `to`; do not block typing
- Exchange fail → keep modal, show server message
- Success → toast + refresh balance

## Files (expected)

**Backend**
- Create: `backend/laravel/app/Services/BinanceTickerService.php` (or equivalent small helper)
- Modify: `backend/laravel/app/Http/Controllers/Api/FinanceController.php`
- Modify: `backend/laravel/routes/api.php`

**Client**
- Modify: `client/src/pages/DepositWithdraw/Convert.tsx`
- Modify: `client/src/services/User.service.tsx` (add `apiExchangeQuote`)
- Optional small components colocated under Convert or DepositWithdraw for picker / confirm / history drawer

## Out of scope

- Quote lock / quoteId TTL
- Coin↔coin without USDT
- Admin approval workflow
- Separate history route
- Changing fee model

## Testing

- Backend self-check / unit-style: rate helper returns 1 for usdt; quote math `received = amount * fromRate / toRate`
- Manual: USDT→SOL quote → confirm → balances update; flip SOL→USDT; history drawer shows new row; insufficient balance blocked; picker enforces USDT-one-side rule
