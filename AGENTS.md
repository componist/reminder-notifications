# AGENTS – Reminder Notifications

## Zweck

Dashboard-CRUD für zeitgesteuerte E-Mail-Erinnerungen (`daily` / `monthly` / `yearly`) inkl. Scheduler-Jobs.

## Grenzen & Abhängigkeiten

- Gehört rein: Reminder-CRUD, Versand-Jobs/Commands, Notification-Mail
- Gehört nicht: generische Inbox/Notifications (`reinholdjesse/notifications`)
- Abhängigkeit: `componist/core` (Flash, Layout, UI-Komponenten)

## Struktur

```
src/Domain/{ReminderNotificationRules,ReminderNotificationType}.php
src/Application/{ReminderNotificationService,ReminderNotificationListQuery,ReminderDispatchService}.php
src/Livewire/ReminderNotification/{Index,Create,Edit}.php
src/Jobs/ReminderNotifications{Daily,Times}Job.php
src/Support/AuthorizesReminderNotifications.php
config/config.php → reminderNotificationConfig
```

## Einbindung

- Provider: `ReminderNotificationsServiceProvider`
- Config: `config('reminderNotificationConfig')`
- Views: `remindernotifications::…`
- Routes: `package.reminder-notification.*`
- Env: `REMINDER_NOTIFICATIONS_ADMIN_IDS`

## Security

- Gate `manage-reminder-notifications` (Admin-IDs oder `users.isAdmin`)
- `toggle()` nur Feld `status`
- Index: `select()` ohne `description`
- Jobs: ungültige E-Mails überspringen

## Tests

```bash
php artisan test --compact --testsuite=ReminderNotifications
```

## Do / Don't

- Do: Admin-IDs setzen, bevor das Dashboard produktiv genutzt wird
- Don't: Client-gesteuerte Feldnamen in `toggle()` wieder zulassen
