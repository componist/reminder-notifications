# Componist Reminder Notifications

`componist/reminder-notifications` provides **email reminder notifications** for Laravel applications, with a small **Livewire v4** dashboard to manage reminders.

It supports three reminder types:

- **daily**: send at a specific time (HH:MM)
- **monthly**: send on a specific day of the month (1–30)
- **yearly**: send on a specific day + month (day 1–30, month 1–12)

## Requirements

- Laravel (this monorepo uses Laravel 12)
- Livewire **v4** (`livewire/livewire: ^4.0`)
- A working mail configuration
- A running queue worker (notifications are dispatched via queued jobs)

## Installation

In this monorepo the package is loaded via Composer path/PSR-4 autoloading. After pulling changes, run:

```bash
composer dump-autoload
php artisan migrate
```

## What the package registers

### Service Provider

The package registers `Componist\ReminderNotifications\ReminderNotificationsServiceProvider`.

It will:

- **load migrations** from `database/migrations`
- **load routes** from `routes/web.php` (inside a `web` middleware group)
- **load views** from `resources/views` under the namespace `remindernotifications::`
- **register artisan commands**
- **register Livewire components** based on `config('reminderNotificationConfig.livewire')`

### Routes (Dashboard)

The package defines authenticated dashboard routes:

- `GET /dashboard/reminder-notification` → index
- `GET /dashboard/reminder-notification/create` → create
- `GET /dashboard/reminder-notification/{editElement}/edit` → edit

Named routes:

- `package.reminder-notification.index`
- `package.reminder-notification.create`
- `package.reminder-notification.edit`

### Livewire components

Configured in `config/config.php`:

- `Componist\ReminderNotifications\Livewire\ReminderNotification\Index`
- `Componist\ReminderNotifications\Livewire\ReminderNotification\Create`
- `Componist\ReminderNotifications\Livewire\ReminderNotification\Edit`

The views live in:

- `remindernotifications::livewire.reminder-notification.index`
- `remindernotifications::livewire.reminder-notification.create`
- `remindernotifications::livewire.reminder-notification.edit`

## Data model

The package creates a `reminder_notifications` table with (simplified) fields:

- `title` (string)
- `description` (text, nullable)
- `email` (string)
- `type` (string): `daily|monthly|yearly`
- `time` (string, nullable): used for `daily` (format `HH:MM`)
- `daily` (string, nullable): used for `monthly` and `yearly` (day of month)
- `monthly` (string, nullable): used for `yearly` (month number)
- `status` (int, default `1`): `1 = active`, `0 = inactive`

Model:

- `Componist\ReminderNotifications\Models\ReminderNotification`

## Scheduling & execution

The package uses two artisan commands which dispatch queued jobs:

- `app:get-times-reminder-notifications-commands` → dispatches `ReminderNotificationsTimesJob`
  - checks **daily** reminders that match the current server time (`date('H:i')`)
- `app:get-daily-reminder-notifications-commands` → dispatches `ReminderNotificationsDailyJob`
  - checks **monthly** and **yearly** reminders that match the current day/month

### Important: scheduler is registered by the package

In `ReminderNotificationsServiceProvider::boot()` the package adds schedule entries:

- every minute: `app:get-times-reminder-notifications-commands`
- daily at 01:00: `app:get-daily-reminder-notifications-commands`

That means you still need a running Laravel scheduler in your environment, e.g. one of:

- `php artisan schedule:work`
- a system cron calling `php artisan schedule:run` every minute

### Queue worker

Both commands dispatch jobs implementing `ShouldQueue`. Ensure a queue worker is running, e.g.:

```bash
php artisan queue:work
```

## Email content

Emails are sent via Laravel Notifications using:

- `Componist\ReminderNotifications\Notifications\ReminderNotificationNotification`

Current mail output:

- **Subject**: a localized label + reminder title (e.g. “Tägliche Erinnerungsnachricht …”)
- **Body**: the `description` field

## Configuration

The package config key is **`reminderNotificationConfig`**.

To override values, create:

- `config/reminderNotificationConfig.php`

Example (minimal):

```php
<?php

return [
    'prefix' => '',
    'livewire' => [
        // override aliases/components if needed
    ],
];
```

Available defaults are in `packages/componist/reminder-notifications/config/config.php`.

## Troubleshooting

- **No emails are sent**
  - Check `MAIL_*` configuration
  - Ensure `php artisan queue:work` is running
  - Ensure the scheduler runs (`schedule:work` or cron + `schedule:run`)
- **Daily reminders don’t trigger**
  - `time` must match the server time format `HH:MM` (24h)
  - The “every minute” command checks `date('H:i')`
- **Monthly/yearly reminders don’t trigger**
  - Monthly/yearly are evaluated in the “daily” job, scheduled at **01:00**
  - Make sure `daily` (day-of-month) and `monthly` (month) are set correctly

## License

MIT. See `LICENSE`.