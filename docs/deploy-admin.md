# Deploy — Admin panel (Next.js + Laravel)

Replace the legacy ThinkPHP admin at **admin.wnskcex.com** with:

| Component | Location | URL |
|-----------|----------|-----|
| Admin API | Laravel (existing deploy) | `https://api.wnskcex.com/api/admin` |
| Admin UI | Next.js (`Backend/admin-frontend`) | `https://admin.wnskcex.com` |

The Laravel app at **api.wnskcex.com** already serves user-facing API routes; admin routes are registered under the `/api/admin` prefix (`routes/admin.php`).

---

## Prerequisites

- SSH access to the production server (aaPanel/BT Panel layout: `/www/wwwroot/`)
- MySQL database `wnskcexcom` (shared with existing API)
- Node.js 18+ (only if hosting the frontend on the same server)
- Git clone of this repo on the server or CI that can deploy artifacts

---

## 1. Laravel admin API (api.wnskcex.com)

**Document root:** `/www/wwwroot/api.wnskcex.com`

### 1.1 Environment

Copy the production template and fill secrets on the server:

```bash
cd /www/wwwroot/api.wnskcex.com
cp .env.production.example .env   # or merge new keys into existing .env
```

Key variables (see `Backend/laravel/.env.production.example`):

| Variable | Production value |
|----------|------------------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://api.wnskcex.com` |
| `FE_URL` | `https://admin.wnskcex.com` |
| `CORS_ALLOWED_ORIGINS` | `https://admin.wnskcex.com` |
| `JWT_SECRET` | Generate: `php artisan jwt:secret` |
| `APP_KEY` | Generate: `php artisan key:generate` (only on fresh install) |

Do not commit `.env` with real credentials.

### 1.2 Deploy code

```bash
cd /www/wwwroot/api.wnskcex.com
git pull origin main

composer install --no-dev --optimize-autoloader

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 1.3 Verify admin API

```bash
curl -s -X POST https://api.wnskcex.com/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"username":"<admin>","password":"<password>"}'
```

Expect JSON with `status: true` and a JWT `token`.

### 1.4 Scheduler (unchanged)

Cron should already be configured:

```bash
* * * * * cd /www/wwwroot/api.wnskcex.com && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

See `laravel/setup-cron.sh` and `laravel/SCHEDULE_DOCUMENTATION.md`.

---

## 2. Admin frontend (admin.wnskcex.com)

Source: `Backend/admin-frontend` (Next.js 14, port 3001 in dev).

Environment template: `Backend/admin-frontend/.env.production.example`

```env
NEXT_PUBLIC_ADMIN_API_URL=https://api.wnskcex.com/api/admin
```

Choose **one** hosting option below.

### Option A — Vercel (recommended)

1. Import the GitHub repo in [Vercel](https://vercel.com).
2. Set **Root Directory** to `Backend/admin-frontend`.
3. Framework preset: **Next.js**.
4. Add environment variable:
   - `NEXT_PUBLIC_ADMIN_API_URL` = `https://api.wnskcex.com/api/admin`
5. Deploy. Note the Vercel URL (e.g. `wnskcex-admin.vercel.app`).
6. In DNS, point `admin.wnskcex.com` CNAME to Vercel (or add the custom domain in Vercel dashboard).
7. Ensure Laravel `CORS_ALLOWED_ORIGINS` includes `https://admin.wnskcex.com`.

Build command (default): `npm run build`  
Output: Next.js serverless/edge (no manual Node process).

### Option B — Same server (Node + PM2)

```bash
cd /www/wwwroot/admin.wnskcex.com   # create site in panel first
git clone <repo-url> .
cd Backend/admin-frontend

cp .env.production.example .env.production.local
# edit NEXT_PUBLIC_ADMIN_API_URL if needed

npm ci
npm run build

npm install -g pm2
pm2 start npm --name wnskcex-admin -- start
pm2 save
pm2 startup
```

Configure nginx to proxy port 3001 — see `docs/nginx-admin.wnskcex.com.conf` (Option B).

### Option C — Same server (static export)

If you enable static export in `next.config.mjs`:

```js
const nextConfig = { output: 'export' };
```

Then build and copy `out/` to the web root:

```bash
npm run build
rsync -av out/ /www/wwwroot/admin.wnskcex.com/
```

Use nginx Option A in `nginx-admin.wnskcex.com.conf`.  
Note: only viable if all admin pages are statically exportable (no server-only features).

---

## 3. Nginx — admin.wnskcex.com

Full snippet: [`nginx-admin.wnskcex.com.conf`](./nginx-admin.wnskcex.com.conf)

Summary:

- **Vercel:** DNS only; no nginx on your server for the frontend.
- **Node on server:** reverse-proxy `admin.wnskcex.com` → `127.0.0.1:3001`.
- **Static:** serve `out/` with `try_files` fallback to `index.html`.

The admin UI calls `api.wnskcex.com` directly (cross-origin). CORS is handled by Laravel `config/cors.php` via `CORS_ALLOWED_ORIGINS`. An optional same-origin `/api/admin` proxy is documented in the nginx file if you prefer to avoid CORS.

---

## 4. Cutover checklist

1. [ ] Laravel `.env` updated: `FE_URL`, `CORS_ALLOWED_ORIGINS`, `JWT_SECRET`, `APP_DEBUG=false`
2. [ ] `php artisan migrate --force` and config/route cache on api.wnskcex.com
3. [ ] Admin API login tested via curl/Postman
4. [ ] Admin frontend built with correct `NEXT_PUBLIC_ADMIN_API_URL`
5. [ ] `admin.wnskcex.com` DNS/site points to new frontend (Vercel or nginx)
6. [ ] Browser login at `https://admin.wnskcex.com/login` — menus, users, finance pages load
7. [ ] Archive or disable old ThinkPHP tree at `/www/wwwroot/admin.wnskcex.com` (legacy PHP) after parity sign-off
8. [ ] Remove ThinkPHP from repo (`remove_thinkphp` migration task)

---

## 5. Rollback

- **Frontend:** revert DNS to old admin host or redeploy previous ThinkPHP backup from panel backup.
- **API:** admin routes are additive under `/api/admin`; user API at `/api/*` is unchanged. Roll back via `git checkout` + `php artisan config:cache` if needed.

---

## 6. Local reference

| Service | Local URL |
|---------|-----------|
| Laravel API | `http://localhost:8000` |
| Admin API | `http://localhost:8000/api/admin` |
| Admin frontend | `http://localhost:3001` |

Docker: `Backend/docker-compose.yml` (`docker compose up`).

Parity test report: [`parity-test-report.md`](./parity-test-report.md).
