# Referral Program API — Design Spec

Date: 2026-07-22  
Status: Approved for implementation

## Goal

Make `GET /api/user/referral` match the Invitation screen: totals, 3-level counts, and member list by level. Stop server QR generation that causes 500s.

## Decisions

| Topic | Choice |
|---|---|
| Approach | Extend existing `GET /api/user/referral?level=` |
| `total_bonus` | SUM `tw_bill.num` where `uid=me` and `type=9` |
| `total_deposit` | SUM approved recharges (`status=2`) of users with `invit_1|2|3 = me` |
| Level counts | All users in that invite slot (no KYC filter) |
| Commission payout | Out of scope — read existing bills only |
| QR | Client-only; API returns `invit` + `referral_url` |

## Response shape

See brainstorming section 1 (fields: `total_bonus`, `total_deposit`, `level_*_count`, `level`, `members[]`, `invit`, `referral_url`). Keep legacy `carr` for compatibility.

## Client

- Read `res.data.data` (axios)
- Wire level tabs → refetch `?level=`
- Render `members` rows; remove hardcoded duplicate “Số lượng hạng Ba”
