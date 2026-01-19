# Laravel Student Appointment System ✅

A compact Laravel application for students to request document collection appointments and for admins to assign dates and time slots. This README is a full reference for setup, configuration, usage, and recommended fixes after a full repository review.

---

## Table of Contents

1. [Project summary](#project-summary) 🎯
2. [Features](#features) ✨
3. [Tech stack](#tech-stack) 🔧
4. [Quick start](#quick-start) 🚀
5. [Environment variables](#environment-variables) 🧩
6. [Database & migrations](#database--migrations) 🗂️
7. [Running locally](#running-locally) 🏃
8. [Testing](#testing) ✅
9. [Architecture & important code paths](#architecture--important-code-paths) 📐
10. [Security & recommended fixes](#security--recommended-fixes) ⚠️
11. [Known issues](#known-issues) 🐞
12. [Contributing](#contributing) 🤝
13. [License](#license) 📜

---

## Project summary 🎯

This project provides a minimal appointment booking flow:

- Students can submit appointment requests with student ID, name, phone number, branch, and purpose.
- Admins can log in via a simple admin panel to review pending requests, assign an appointment date and a time slot, or delete requests.
- When an appointment is approved, an SMS confirmation is optionally sent via an SMS gateway integration.

No external contact or dummy personal data is added to this README. All configuration that might contain secrets must be stored in environment variables and not committed.

## Features ✨

- Student booking form with validation
- Admin dashboard with filters (All / Pending / Approved)
- Server-side appointment scheduling and deletion
- SMS integration via `App\Services\SmsService` with a `test_mode` for local/dev
- Pagination for admin list, accessible UI built with Tailwind + Blade

## Tech stack 🔧

- PHP 8.2
- Laravel Framework ^12.0
- MySQL (or other supported DB via DB_CONNECTION)
- Tailwind CSS, Vite for frontend assets

## Quick start 🚀

Prerequisites:

- PHP >= 8.2, Composer
- Node.js + npm
- A database (MySQL / MariaDB)

1. Clone the repository

    git clone https://github.com/codezelat/laravel-student-appointment-system.git
    cd laravel-student-appointment-system

2. Install PHP dependencies

    composer install

3. Install JS dependencies and build assets (dev)

    npm install
    npm run dev

4. Copy environment and generate app key

    cp .env.example .env
    php artisan key:generate

5. Configure `.env` (see next section for required keys)

6. Run migrations and seeders

    php artisan migrate --seed

7. Serve the app (optional)

    php artisan serve

Open http://localhost:8000 and you will be redirected to the student appointment form.

## Environment variables 🧩

Set these in your `.env` (do NOT commit secrets):

- APP_ENV (local / production)
- APP_URL
- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

SMS settings

- SMS_API_URL (optional; defaults to provider endpoint)
- SMS_USERNAME
- SMS_PASSWORD
- SMS_SOURCE
- SMS_TEST_MODE (true|false) — when true SMSs are only logged

Admin credentials

- ADMIN_USERNAME
- ADMIN_PASSWORD
    - NOTE: The system currently expects a plaintext password in config for direct comparison (see "Known issues" below). Ensure you read the Security section.

## Database & migrations 🗂️

Key model and migration:

- `Appointment` model (`app/Models/Appointment.php`) — fields: student_id, full_name, phone_number, address, branch, purpose (JSON), appointment_date, time_slot, status
- Primary migration: `database/migrations/2025_02_20_000001_create_appointments_table.php`

Seeding:

- `DatabaseSeeder` creates a single test user (`test@example.com`) by default. Adjust or add seeders as needed.

## Running locally 🏃

- Development server: `php artisan serve`
- Queue worker (if required for background jobs): `php artisan queue:work`
- Assets: `npm run dev` (or `npm run build` for production)

## Testing ✅

Run PHPUnit via the Laravel test runner:

php artisan test

Add tests under `tests/Feature` and `tests/Unit` as needed.

## Architecture & important code paths 📐

- Controllers:
    - `AppointmentController` — student-facing book/create/store flow
    - `AdminController` — admin authentication (session-based), dashboard, update (assign date/time) and destroy
- Middleware:
    - `App\Http\Middleware\AdminAuthMiddleware` — protects admin routes via session
- Services:
    - `App\Services\SmsService` — encapsulates SMS gateway; honors `app.env === local && sms.test_mode` for safe local testing
- Routes: `routes/web.php` (student routes + admin prefix `sitc-admin-area`)
- Views: Blade templates in `resources/views/student` and `resources/views/admin`

## Security & recommended fixes ⚠️

Important observations following a full repository review (please address in production deployments):

1. Admin password verification inconsistency
    - The `config/admin.php` suggests storing a _bcrypt_ hashed password and shows how to generate it (Hash::make), but the current authentication code in `AdminController::authenticate` compares the raw input directly to the config value via equality (`===`). If `ADMIN_PASSWORD` contains a bcrypt hash, the comparison will fail.
    - Recommended fix: store a bcrypt-hashed password in `ADMIN_PASSWORD` and update `AdminController::authenticate` to use Laravel's `Hash::check($request->password, $adminPassword)` to verify. Example:

```php
use Illuminate\\Support\\Facades\\Hash;

// ... inside authenticate()
if ($request->username === $adminUsername && Hash::check($request->password, $adminPassword)) {
    Session::put('admin_logged_in', true);
}
```

2. Sensitive defaults
    - The repository contained hard-coded defaults for SMS credentials in `config/sms.php`. These were removed and replaced with environment-based values to prevent accidental credential leaks. Make sure `SMS_USERNAME`, `SMS_PASSWORD`, and `SMS_SOURCE` are set in your `.env` and DO NOT get committed to source control.

3. Admin session auth
    - Current admin auth is a simple session flag. For production, consider using a proper user system with hashed passwords, roles, and Laravel's authentication guards.

## Known issues 🐞

- Admin password hashing vs authentication mismatch (see Security section).
- `database/seeders/AdminUserSeeder.php` is empty; no default admin user is created by seeders. The current admin login is configured via `ADMIN_USERNAME` and `ADMIN_PASSWORD` env variables.

## Contributing 🤝

- Fork, create a feature branch, add tests, and open a PR.
- Be mindful of not committing secrets or credentials to the repository.

## License 📜

This project is licensed under the MIT License — see the `LICENSE` file for details.

---

If you'd like, I can also:

- Create a small patch to fix the admin authentication to use `Hash::check`.
- Add automated checks to detect accidental credential defaults (e.g., a simple pre-commit hook).

If you want me to apply any of those, say which one and I will implement it. ✅
