# Deploying Feimso to cPanel (kmhub-new)

Repo: https://github.com/umer1351/feimso (branch `main`). Needs PHP >= 8.1 and MySQL.

## 1. Subdomain + folder
cPanel -> Domains (or Subdomains) -> Create: subdomain `kmhub-new`.
Set the document root to `kmhub-new` (folder in the home directory, NOT public_html/...).
The project's root `.htaccess` already rewrites everything into `public/`, so the docroot is the repo root.
Select PHP 8.1+ for it: cPanel -> MultiPHP Manager.

## 2. Free SSL
cPanel -> SSL/TLS Status -> tick the new subdomain -> Run AutoSSL. (Wait a few minutes.)

## 3. Database
cPanel -> MySQL Databases:
1. Create database `<cpuser>_feimso`.
2. Create a user `<cpuser>_feimso` with a strong password.
3. Add the user to the database with ALL PRIVILEGES.
Then phpMyAdmin -> select the database -> Import -> `database/dump/feimso.sql` (if > upload limit, use the Terminal: `mysql -u USER -p DB < database/dump/feimso.sql`).

## 4. Code from GitHub
cPanel -> Git Version Control -> Create:
- Clone URL: `https://github.com/umer1351/feimso.git` (private repo: use a deploy key or token URL)
- Repository path: `/home/<cpuser>/kmhub-new`
Later updates: Git Version Control -> Manage -> "Update from Remote" (= git pull) — or in Terminal: `cd ~/kmhub-new && git pull`.

## 5. Server .env (never in git)
Copy `.env.example` to `.env` in `~/kmhub-new` and set:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kmhub-new.<yourdomain>
DB_HOST=localhost
DB_DATABASE=<cpuser>_feimso
DB_USERNAME=<cpuser>_feimso
DB_PASSWORD=...
```
Then in Terminal:
```
cd ~/kmhub-new
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan storage:link
chmod -R 775 storage bootstrap/cache
php artisan config:cache   # only after .env is final
```
## 6. Update workflow
Local: commit + `git push`. Server: `git pull`, then `composer install --no-dev` if composer.json changed and `php artisan migrate --force` if migrations were added.
