# Feimso — local setup

Laravel 10 storefront (eMart) running on **MySQL** — no Firebase — plus an admin panel at `/admin`.

## Requirements
- PHP 8.1+ (8.4 tested) with `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`
- MySQL 8.x (InnoDB). WAMP's MySQL 8.4 works; MariaDB is **not** supported (the engine uses `CAST(... AS JSON)`).
- Composer

## First run
```bash
composer install
cp .env.example .env        # then set DB_*, APP_URL, ADMIN_PASSWORD, DEMO_CUSTOMER_PASSWORD
php artisan key:generate
mysql -uroot -e "CREATE DATABASE feimso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan migrate
php artisan db:seed         # settings, AED currency, UAE zone, demo stores/products, accounts
```
Seeded image URLs use `APP_URL`, so set it before seeding (re-run `php artisan db:seed` after changing it).

- Storefront: `APP_URL/` → pick an address + section
- Admin: `APP_URL/admin` → `ADMIN_EMAIL` / `ADMIN_PASSWORD` from `.env`

### WAMP (Apache)
The project root contains an `.htaccess` that routes into `public/`, so `http://localhost/feimso` works with `APP_URL=http://localhost/feimso`.

### Production
```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
Enable OPcache and disable Xdebug — on Windows CLI they are often the other way round, which makes every request ~3× slower.

## How the Firebase removal works
- The views still contain the original inline Firestore JS. `public/js/firebase-local.js` is a drop-in for the Firebase v8 SDK; every call goes to `POST /fs/batch` (calls made in the same tick are batched into one request).
- `App\Firestore\Engine` runs those queries on MySQL with Firestore semantics. Each collection is one table (`config/firestore.php`); fields without a column go to the row's `extra` JSON.
- Access rules per collection (`read`, `write`, `owner`, `protected`, `shared`) replace Firestore security rules.
- Auth = Laravel session (`/fs/auth/*`), uploads = `public/uploads/`.
- Server-Timing header on `/fs/batch` shows query time in DevTools.

## Tests
```bash
mysql -uroot -e "CREATE DATABASE feimso_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php vendor/bin/phpunit
```

## Not wired up (need real providers)
- Google sign-in (was Firebase Auth popup)
- SMS OTP — codes are written to `storage/logs/laravel.log` (`FsAuthController::sendSms`)
- Push notifications (FCM)
- Mail defaults to the `log` driver
