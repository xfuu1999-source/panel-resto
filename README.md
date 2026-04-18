# RestoPanel

Backend control panel for the restaurant system, built with **Laravel 13** and **Orchid Platform 14**.

## Current stack

- Laravel 13
- Orchid Platform 14
- PHP 8.5
- PostgreSQL or SQLite
- Vite for asset build

## Main scope

- menu and category management
- promo banner and homepage content management
- voucher card management
- cashier account management
- order and payment monitoring
- reporting and operational overview

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan orchid:install
php artisan orchid:admin
php artisan serve
```

Open:

```text
http://127.0.0.1:8000/admin
```

## Notes

- `.env.example` is prepared for PostgreSQL-style deployment.
- local Docker-based setup was used during initial scaffolding because the host PHP environment did not include the required XML / DOM extensions.
- Orchid assets can be republished after updates with:

```bash
php artisan orchid:publish
php artisan view:clear
```
