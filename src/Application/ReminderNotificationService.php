<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Application;

use Componist\ReminderNotifications\Domain\ReminderNotificationRules;
use Componist\ReminderNotifications\Domain\ReminderNotificationType;
use Componist\ReminderNotifications\Models\ReminderNotification;

final class ReminderNotificationService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function create(array $data): ReminderNotification
    {
        return ReminderNotification::query()->create([
            ...ReminderNotificationRules::scheduleAttributes($data),
            'status' => 1,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function update(int $id, array $data): bool
    {
        $reminder = ReminderNotification::query()->find($id);

        if ($reminder === null) {
            return false;
        }

        return $reminder->update(ReminderNotificationRules::scheduleAttributes($data));
    }

    public static function toggle(int $id, string $field): bool
    {
        if (! ReminderNotificationType::isAllowedToggleField($field)) {
            return false;
        }

        $reminder = ReminderNotification::query()->find($id);

        if ($reminder === null) {
            return false;
        }

        $reminder->status = $reminder->status ? 0 : 1;

        return $reminder->save();
    }

    public static function delete(int $id): bool
    {
        $reminder = ReminderNotification::query()->find($id);

        return (bool) $reminder?->delete();
    }
}
