# NeonBill

Subscription & expense manager with a dark neon UI. Track recurring bills, switch display currencies in real time, and manage subscriptions per user account.

![Laravel](https://img.shields.io/badge/Laravel-13-red) ![PHP](https://img.shields.io/badge/PHP-8.3+-blue) ![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38bdf8)

## Features

- User registration, login, logout (multi-tenant data)
- CRUD subscriptions (name, price, currency, category, billing cycle, due date, active/paused)
- Dashboard with monthly spend total and subscription cards
- Real-time currency conversion (IDR, USD, EUR, GBP, SGD, JPY) without page reload
- Category filter and spending chart
- Password visibility toggle on auth forms
- Thousand-separator price input (e.g. `65.000`)
- Profile settings (name, default currency, password)

## Tech stack

- **Backend:** Laravel 13, PHP 8.3+
- **Frontend:** Blade, Tailwind CSS v4, Vite
- **Database:** SQLite (local) or MySQL (production recommended)

## Local setup

### Requirements

- PHP 8.3+, Composer
- Node.js 18+, npm
- MySQL 8+ (optional; SQLite works out of the box)

### Install

```bash
git clone <your-repo-url> NeonBill
cd NeonBill

composer install
cp .env.example .env
php artisan key:generate
```

**SQLite (quickest):**

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

**MySQL:** set `DB_*` in `.env`, then:

```bash
php artisan migrate
php artisan db:seed
```

### Frontend assets

```bash
npm install
npm run build
# development with hot reload:
npm run dev
```

### Run

```bash
php artisan serve
# or both server + Vite:
composer dev
```

Open [http://localhost:8000](http://localhost:8000)

## Password reset email setup

Forgot password pages:

| Page | URL |
|------|-----|
| Request link | `/forgot-password` (also linked from login) |
| Set new password | `/reset-password/{token}?email=...` (from email) |

### 1. Configure `.env` mail (SMTP)

`MAIL_MAILER=log` only writes to `storage/logs` — **no real inbox**. For real emails use SMTP.

**Mailtrap (recommended for local testing, free):**

1. Register at [mailtrap.io](https://mailtrap.io)
2. Open **Email Testing → Inboxes → SMTP**
3. Copy credentials into `.env`:

```env
APP_URL=http://localhost:8000

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=paste_from_mailtrap
MAIL_PASSWORD=paste_from_mailtrap
MAIL_FROM_ADDRESS=noreply@neonbill.test
MAIL_FROM_NAME="NeonBill"
```

4. Apply config:

```bash
php artisan config:clear
```

### 2. Test the flow

1. Open [http://localhost:8000/forgot-password](http://localhost:8000/forgot-password)
2. Enter a registered email (e.g. `demo@neonbill.test`)
3. Click **Email password reset link**
4. Open Mailtrap inbox — email **Reset your NeonBill password**
5. Click **Reset password** → set new password → login

### 3. Production mail (Gmail / hosting SMTP)

Example Gmail App Password:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_SCHEME=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="NeonBill"
```

Use your host’s SMTP settings on cPanel/VPS if available.

### Demo account

| Field | Value |
|-------|--------|
| Email | `demo@neonbill.test` |
| Password | `password` |

## Tests

```bash
php artisan test
```

## Production deployment

### 1. Server requirements

- PHP 8.3+ with extensions: `pdo`, `pdo_mysql` (or `pdo_sqlite`), `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- MySQL 8+ or MariaDB
- Composer, Node.js (build step only)
- Web server (Nginx/Apache) pointing document root to `public/`

### 2. Environment (`.env`)

```env
APP_NAME=NeonBill
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=neonbill
DB_USERNAME=your_user
DB_PASSWORD=your_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (required for password reset)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Deploy commands

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set folder permissions: `storage/` and `bootstrap/cache/` writable by the web server.

### 4. Nginx snippet

```nginx
root /var/www/neonbill/public;
index index.php;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### 5. Free hosting (share with users online)

NeonBill is a full Laravel app (PHP + MySQL + build step). **Shared PHP hosts (InfinityFree, 000webhost)** usually cannot run `composer`, `artisan migrate`, or Vite builds — avoid them.

| Platform | Free tier | Best for |
|----------|-----------|----------|
| **[Railway](https://railway.app)** | ~$5 credit/month | Easiest GitHub deploy + MySQL |
| **[Render](https://render.com)** | Free web service (sleeps when idle) | Demo / low traffic |
| **[Fly.io](https://fly.io)** | Small free allowance | Docker / global edge |
| **[Koyeb](https://www.koyeb.com)** | Free hobby tier | Simple container deploy |

**Recommended: Railway (step-by-step)**

> **Build failed (PHP 8.3 vs 8.4)?** Railway defaults to PHP 8.3. This repo pins **PHP 8.4** via `.php-version` — commit and redeploy.

1. Push NeonBill to **GitHub**.
2. Sign up at [railway.app](https://railway.app) → **New Project** → **Deploy from GitHub** → select the repo.
3. Add **MySQL** (Railway dashboard → **+ New** → **Database** → MySQL). Copy `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD` into the web service variables.
4. Set **Variables** on the web service:

```env
APP_KEY=base64:...          # run: php artisan key:generate --show
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR-APP.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_pass
MAIL_FROM_ADDRESS=noreply@your-app.up.railway.app
MAIL_FROM_NAME=NeonBill
```

5. **Settings** → **Deploy** → **Pre-deploy command** (or use repo `railway.toml`):

```bash
chmod +x ./railway/init-app.sh && ./railway/init-app.sh
```

Leave **Custom start command** empty — Railway runs Laravel via php-fpm + Caddy. Domain port = **8080** (Railway `PORT`).

6. **Settings** → **Networking** → **Generate Domain**. Set `APP_URL` to that exact URL (e.g. `https://neonbill-production.up.railway.app`).

**500 Server Error checklist**

| Check | Fix |
|-------|-----|
| No MySQL service | **+ New** → **Database** → **MySQL**, link variables to NeonBill |
| `APP_KEY` empty | Terminal: `php artisan key:generate --show` → paste into Variables |
| `APP_URL` wrong | Must match your Railway domain exactly |
| Migrations not run | Pre-deploy command above, then **Redeploy** |
| See real error | Deployments → latest → **View logs**; or set `APP_DEBUG=true` briefly |
| `/up` works but `/` is 500 | Run `npm run build` in deploy — `public/build` is not in git; set **Build command** in `railway.toml` |
7. After first deploy, open the URL → **Register** or use demo account from seeder (`demo@neonbill.test` / `password` if you ran `migrate:fresh --seed` once via Railway shell).

**Render (alternative):** Create **Web Service** + **PostgreSQL** or external MySQL; build: `composer install --no-dev && npm ci && npm run build`; start: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT`. Free instances **sleep** after ~15 min idle (first visit may take 30–60s).

**Email on production:** Use [Mailtrap](https://mailtrap.io) (testing) or [Brevo](https://www.brevo.com) / Gmail SMTP so **Forgot password** sends real mail. Set `MAIL_MAILER=smtp` and run `php artisan config:clear` after changing env.

**Do not** use SQLite in production for multi-user traffic (data loss risk on ephemeral disks).

### 6. Mobile-friendly UI

The app includes a bottom navigation bar on phones (Home / Add / Profile), larger touch targets, full-width filters, and safe-area padding for notched devices. Open the deployed URL on your phone or use Chrome DevTools → device toolbar to preview.

## Project structure

```
app/
  Http/Controllers/     # Subscription, Auth, Profile
  Models/               # User, Subscription
  Utilities/            # Currency, Date, Subscription helpers
resources/views/        # Blade (layouts, subscriptions, auth)
database/migrations/    # Schema
tests/                  # Feature & unit tests
```

## License

MIT
