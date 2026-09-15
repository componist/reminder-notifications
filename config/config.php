<?php

declare(strict_types=1);

use Componist\ReminderNotifications\Livewire\ReminderNotification\Create;
use Componist\ReminderNotifications\Livewire\ReminderNotification\Edit;
use Componist\ReminderNotifications\Livewire\ReminderNotification\Index;

return [

    'admin_user_ids' => array_values(array_filter(array_map(
        static fn (string $id): int => (int) trim($id),
        explode(',', (string) env('REMINDER_NOTIFICATIONS_ADMIN_IDS', ''))
    ))),

    'components' => [],

    'livewire' => [
        'reminder-notification.index' => Index::class,
        'reminder-notification.create' => Create::class,
        'reminder-notification.edit' => Edit::class,
    ],

    'prefix' => '',

    'assets' => [],

];
