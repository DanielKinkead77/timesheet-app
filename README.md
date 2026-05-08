# Timesheet App

A timesheet management application built with Laravel 11, Blade and Tailwind CSS.

## Overview

This app allows users to log working hours against projects, with support for normal, overtime and double time rate codes that map to an external payroll system. Users manage their own time entries while admin accounts have full visibility and control over all users' data.

Hours are calculated automatically from start and end times, including shifts that cross midnight. Projects can be active or archived, and all timesheet views include date range filtering and sorting.

## Stack

Laravel 11, PHP 8.3, Blade templating, Tailwind CSS, Laravel Breeze for authentication, SQLite for local development, Vite for asset bundling.

## Getting started

Clone the repo and install dependencies:

```bash
git clone https://github.com/DanielKinkead77/timesheet-app.git
cd timesheet-app
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run dev
```

Then visit the app via Laravel Herd at `http://timesheet-app.test` or run `php artisan serve` and visit `http://localhost:8000`.

The seeder pre-populates three rate codes: NRM (Normal Rate), OVT (Overtime) and DBL (Double Time).

## Admin access

To promote a user to admin, update the email address in `database/seeders/DatabaseSeeder.php` and run `php artisan db:seed`.

## A few notes on the design

Hourly rates are stored as a lookup table rather than monetary values. The intention is that rate codes map to an external payroll system which holds the actual rates — the app just records which rate type applies to each entry.

Overnight shift support works by detecting when the end time is earlier than the start time and adding 24 hours to the calculation automatically.

Admin authorisation is handled through a Laravel Gate defined in `AppServiceProvider` rather than inline checks, so the rule lives in one place.

All timesheet list queries use eager loading to fetch related project and rate data in a single query rather than hitting the database once per row.

## License

MIT