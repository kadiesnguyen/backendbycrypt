# WNSKCEX Trade Backend

Monorepo for the new CMS stack:

- `laravel/` — API (user + admin routes)
- `admin-frontend/` — Next.js admin dashboard

## Local development

```bash
# API
cd laravel && composer install && cp .env.example .env && php artisan serve

# Admin
cd admin-frontend && npm install && cp .env.local.example .env.local && npm run dev
```

See `docker-compose.yml` for local MySQL.

## Production (cms.wnskcex.com)

- API: `/api/*` → Laravel (`laravel/public`)
- Admin: `/` → Next.js (PM2)

Copy `laravel/.env.production.example` and set DB/JWT/CORS on the server. Do not commit real `.env` files.
