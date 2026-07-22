# Admin Perp Positions — design summary

See implementation in:

- Migration: `laravel/database/migrations/2026_07_22_150000_add_perp_admin_columns.php`
- Service: `laravel/app/Services/PerpAdminService.php`
- Admin API: `laravel/app/Http/Controllers/Admin/PerpPositionController.php`
- Admin UI: `admin-frontend/src/features/trading/perp-positions/`
- Routes: `/trading/perp`, alert poll `/perp-positions/pending-count`

Win settle: credit = margin + margin × `perp_win_rate`%. Loss: forfeit margin (`usdt_d` release). Normal settle: market close via `PerpTradingService::closePosition`.
