# Env: local vs production (bycrypt)

Keep **local** and **product** configs separate. `NEXT_PUBLIC_*` is baked at **build time** — wrong file = login/API hits the wrong host.

## Admin UI (`admin-frontend`)

| Env | File (gitignored) | Value |
|-----|-------------------|--------|
| Local | `.env.local` | `NEXT_PUBLIC_ADMIN_API_URL=http://localhost:8000/api/admin` |
| Prod | `.env.production.local` on server | `NEXT_PUBLIC_ADMIN_API_URL=https://cms.bycrypt.net/api/admin` |

Templates in repo:

- `.env.local.example` → local only
- `.env.production.example` → product only

### Rules

1. Never commit `.env.local` / `.env.production.local`.
2. Never put `localhost` in production env.
3. Production deploy must write `.env.production.local` **before** `npm run build` (see `deploy/pull.sh`).
4. After `git pull` on server, always use `deploy/pull.sh` — do not bare `npm run build` without the prod env file.

### Local

```bash
cd admin-frontend
cp .env.local.example .env.local
npm run dev   # port 3001
```

### Product

```bash
bash /www/wwwroot/cms.bycrypt.net/deploy/pull.sh
```

## Laravel API (`laravel`)

| Env | File |
|-----|------|
| Local | `laravel/.env` (from `.env.example`) |
| Prod | `laravel/.env` on server (from `.env.production.example`) |

Product must have correct `APP_URL`, `CORS_ALLOWED_ORIGINS`, `BINANCE_TICKER_URL` on its **own line**, mail SMTP, etc. Never merge two keys onto one line.

## Client site (`client` repo)

| Env | File | Value |
|-----|------|--------|
| Local | `.env.local` | `NEXT_PUBLIC_API_URL=http://localhost:8000` |
| Prod | `.env` / `.env.production.local` on server | `NEXT_PUBLIC_API_URL=https://cms.bycrypt.net` |

Deploy: `/www/wwwroot/bycrypt.net/deploy.sh` (must preserve prod env across `git reset`/`pull`).
