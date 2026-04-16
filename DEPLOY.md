# Deploying WACA to Coolify with Nixpacks

This repo is pre-configured for a Nixpacks build on Coolify. No Dockerfile
is required — Nixpacks auto-detects Laravel from `composer.json` and we
extend it via `nixpacks.toml` and `Procfile`.

## What's in the repo

| File | Purpose |
|---|---|
| `nixpacks.toml` | Pins Node 22 alongside the PHP 8.3 provider, runs `composer install`, `npm ci`, `npm run build`, and prepares storage dirs. |
| `Procfile` | Defines a `release:` hook that runs `migrate --force` and rebuilds Laravel's config / route / view / event caches on every deploy. |
| `.env.production.example` | Template for the env vars to set in Coolify's UI. Never commit filled-in values. |

The web process (nginx + php-fpm pointed at `public/`) is supplied by the
Nixpacks PHP provider — we don't override it.

## Coolify setup

1. **Create the database service first.** In your Coolify project, add a
   new *MariaDB* (or MySQL) resource. Note its internal hostname,
   database name, user, and password — you'll plug those into the app's
   env vars.

2. **Create the application.** New Resource → *Public/Private Git
   Repository* → point at this repo. Set Build Pack to **Nixpacks**.

3. **Set environment variables.** Copy every key from
   `.env.production.example` into Coolify's *Environment Variables*
   panel and fill in real values. In particular:
   - `APP_KEY` — generate locally with `php artisan key:generate --show`
     and paste it in.
   - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — use the
     MariaDB service's internal credentials from step 1.
   - `APP_URL` — the domain Coolify will serve this app on.
   - Any `VITE_*` vars must be present at **build time**, not just
     runtime, since Vite bakes them into the compiled JS.

4. **Set the port.** Coolify's *Network* tab should expose port **80**
   (the Nixpacks PHP provider serves nginx on 80 by default).

5. **Deploy.** First deploy will:
   - Run `composer install --no-dev --optimize-autoloader`
   - Run `npm ci && npm run build`
   - Fire the `release:` command → migrations + config/route/view/event
     caches + `storage:link`
   - Boot nginx + php-fpm

## Verifying the deploy

Check the deploy logs for these markers:

- `Migrating: ...` / `Migrated: ...` — migrations applied.
- `Configuration cached successfully.` — config cache built.
- `Route cache cleared.` / `Routes cached successfully.` — route cache built.
- nginx and php-fpm startup lines at the end.

If the `release:` command doesn't run automatically in your Coolify
version, paste its contents into Coolify's **Pre-deployment Command**
field as a fallback.

## Runtime notes

- **Filesystem:** `FILESYSTEM_DISK=s3`, so uploaded files go to S3 — no
  persistent volume is required on Coolify. If you change this to
  `local`, you must mount `/app/storage/app` as a persistent volume or
  uploads vanish on redeploy.
- **Sessions / cache / queue:** all use the database by default. Jobs
  will queue up in a `jobs` table but nothing processes them until you
  add a queue worker (run `php artisan queue:work` in a second Coolify
  resource or add a worker process here).
- **Logs:** `LOG_CHANNEL=stderr` in the production template so Coolify's
  log viewer captures everything.
- **Trusted proxies:** `TRUSTED_PROXIES=*` so Laravel respects the
  `X-Forwarded-*` headers from Coolify's reverse proxy (needed for
  secure-cookie and `APP_URL`-scheme detection to work).

## Local development is unaffected

DDEV (`.ddev/`) and `.env` still work the same. None of the files added
here interfere with `ddev start` or `composer dev`.
