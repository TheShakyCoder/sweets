# Deploying Penwortham Lollipops with Docker

This repo ships a production-ready **Dockerfile** that builds a single
container running nginx, PHP-FPM, queue workers, and SSR — all managed
by Supervisor.

## What's in the repo

| File / Dir | Purpose |
|---|---|
| `Dockerfile` | Multi-stage build: Node 22 for frontend assets → PHP 8.4 + nginx + supervisor runtime. |
| `.docker/entrypoint.sh` | Container boot: runs migrations, rebuilds Laravel caches with runtime env vars, then hands off to supervisord. |
| `.docker/nginx.conf` | Nginx config — Laravel front-controller, Vite long-cache, reverse-proxy header trust. |
| `.docker/php.ini` | PHP overrides — OPcache + JIT, upload limits, memory limit. |
| `.docker/php-fpm.conf` | FPM pool tuning — dynamic PM, 50 max children. |
| `.docker/supervisord.conf` | Main supervisor config. |
| `.docker/supervisor/*.conf` | Individual worker configs: nginx, php-fpm, queue workers (×4), SSR (×1). |
| `.dockerignore` | Keeps `node_modules`, `vendor`, `.git`, etc. out of the build context. |
| `.env.production.example` | Template for production env vars. Never commit filled-in values. |

## Architecture

```
┌─────────────────────────────────────────────┐
│  Container (port 80)                        │
│                                             │
│  supervisord                                │
│   ├── nginx          → :80 (public)         │
│   ├── php-fpm        → 127.0.0.1:9000       │
│   ├── queue-worker   × 4 processes          │
│   └── ssr            × 1 (Node, Inertia)    │
└─────────────────────────────────────────────┘
```

## Build & run locally

```bash
docker build -t lollipops .
docker run -p 8080:80 --env-file .env.production lollipops
```

## Coolify setup

1. **Create the database service first.** Add a MariaDB (or MySQL)
   resource in your Coolify project. Note its internal hostname and
   credentials.

2. **Create the application.** New Resource → Git Repository → point at
   this repo. Set Build Pack to **Dockerfile**.

3. **Set environment variables.** Copy every key from
   `.env.production.example` into Coolify's Environment Variables panel
   and fill in real values:
   - `APP_KEY` — generate with `php artisan key:generate --show`
   - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — MariaDB
     service credentials from step 1
   - `APP_URL` — the domain Coolify will serve
   - Any `VITE_*` vars must be present at **build time**

4. **Expose port 80** in Coolify's Network settings.

5. **Deploy.** The build will:
   - Stage 1: `npm ci` + `npm run build` (Vite + SSR)
   - Stage 2: `composer install --no-dev --optimize-autoloader`
   - Boot: `entrypoint.sh` →
     - `php artisan migrate --force`
     - `php artisan storage:link`
     - `php artisan config/route/view/event:cache`
     - Start supervisord (nginx + php-fpm + queue + ssr)

## Verifying the deploy

Watch the runtime logs for these markers:

```
==> [startup] Running migrations
==> [startup] Rebuilding Laravel caches against runtime env
==> [startup] Starting supervisord
```

## Runtime notes

- **Filesystem:** `FILESYSTEM_DISK=s3` — uploads go to S3, no persistent
  volume required. If you switch to `local`, mount `/var/www/html/storage/app`
  as a persistent volume.
- **Sessions / cache / queue:** all use the database by default. The
  container runs 4 queue workers via supervisor.
- **SSR:** Inertia SSR runs as a supervisor process. If you don't need
  SSR, remove `.docker/supervisor/ssr.conf`.
- **Logs:** `LOG_CHANNEL=stderr` so your hosting platform's log viewer
  captures everything.
- **Trusted proxies:** `TRUSTED_PROXIES=*` so Laravel respects
  `X-Forwarded-*` headers from the reverse proxy.
- **Queue workers:** Default is 4 processes. Adjust `numprocs` in
  `.docker/supervisor/queue.conf` to taste.

## Troubleshooting

**"Connection refused" during boot.**
The DB service isn't reachable. Check both services share the same
network and `DB_HOST` matches the DB service's internal hostname.

**Container exits immediately.**
Usually a permissions issue on `storage/` or `bootstrap/cache/`. The
Dockerfile chmods both, but a mounted volume may override those
permissions.

**SSR not working.**
Check that `bootstrap/ssr/ssr.mjs` exists in the built image. The Vite
SSR build (`npm run build` runs both client and SSR builds) must
complete in stage 1.

## Local development

DDEV (`.ddev/`) and `.env` still work the same. The Docker files don't
interfere with `ddev start`.
