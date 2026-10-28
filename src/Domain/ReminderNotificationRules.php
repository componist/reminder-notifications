<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Domain;

final class ReminderNotificationRules
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function scheduleAttributes(array $data): array
    {
        $type = (string) ($data['type'] ?? ReminderNotificationType::DAILY);

        return [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'email' => $data['email'],
            'type' => $type,
            'time' => $type === ReminderNotificationType::DAILY ? ($data['time'] ?? null) : null,
            'daily' => in_array($type, [ReminderNotificationType::MONTHLY, ReminderNotificationType::YEARLY], true)
                ? ($data['daily'] ?? null)
                : null,
            'monthly' => $type === ReminderNotificationType::YEARLY ? ($data['monthly'] ?? null) : null,
        ];
    }
}
