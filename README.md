# Daily Tracker

Daily Tracker is a small Laravel 13 personal tracking app. Users sign in with only a username, choose a calendar date, mark which activities they skipped, and review daily and monthly summaries.

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Environment configuration

The default `.env.example` uses SQLite for simple local development:

```env
DB_CONNECTION=sqlite
SESSION_DRIVER=database
CACHE_STORE=database
```

Create the SQLite database file if it does not already exist:

```bash
touch database/database.sqlite
```

## Database setup

Run migrations and seed the initial activities plus a demo user:

```bash
php artisan migrate --seed
```

Seeded activities:

- Breakfast
- Running

Optional demo username:

- `demo`

## Run locally

In one terminal, start Laravel:

```bash
php artisan serve
```

In another terminal, compile frontend assets:

```bash
npm run dev
```

Open the URL printed by `php artisan serve`, log in with any username, and start tracking skipped activities.

## Application structure

- `app/Models` contains `User`, `Activity`, and `DailySkip` Eloquent models and relationships.
- `app/Http/Controllers/AuthController.php` handles username-only session login and logout.
- `app/Http/Controllers/CalendarController.php` builds the calendar, daily state, and monthly summaries.
- `app/Http/Requests` contains validation for usernames and activity skip submissions.
- `database/migrations` defines users, sessions, activities, and daily skip records.
- `database/seeders/ActivitySeeder.php` creates the default trackable activities.
- `resources/views` contains Blade pages and layout components.
- `routes/web.php` defines login, logout, dashboard, calendar, and skip routes.

## How to add a new activity

Add a row to the `activities` table; no controller, route, or Blade changes are needed. For example:

```bash
php artisan tinker
```

```php
App\Models\Activity::create([
    'name' => 'Reading',
    'slug' => 'reading',
    'is_active' => true,
]);
```

The calendar, daily checklist, and monthly activity summaries automatically include every active activity.
