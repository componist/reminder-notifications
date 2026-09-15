<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Application;

use Componist\ReminderNotifications\Domain\ReminderNotificationType;
use Componist\ReminderNotifications\Models\ReminderNotification;
use Componist\ReminderNotifications\Notifications\ReminderNotificationNotification;
use Illuminate\Support\Facades\Notification;

final class ReminderDispatchService
{
    public static function dispatchDailyByTime(string $time): void
    {
        $reminders = ReminderNotification::query()
            ->where('time', $time)
            ->where('type', ReminderNotificationType::DAILY)
            ->where('status', 1)
            ->get();

        foreach ($reminders as $reminder) {
            self::notify($reminder);
        }
    }

    public static function dispatchMonthlyAndYearlyForToday(): void
    {
        $day = (int) date('j');
        $month = (int) date('n');

        $reminders = ReminderNotification::query()
            ->where('status', 1)
            ->where(function ($query) use ($day, $month): void {
                $query->where(function ($query) use ($day): void {
                    $query->where('daily', $day)
                        ->where('type', ReminderNotificationType::MONTHLY);
                })->orWhere(function ($query) use ($day, $month): void {
                    $query->where('daily', $day)
                        ->where('monthly', $month)
                        ->where('type', ReminderNotificationType::YEARLY);
                });
            })
            ->get();

        foreach ($reminders as $reminder) {
            self::notify($reminder);
        }
    }

    private static function notify(ReminderNotification $reminder): void
    {
        if (! filter_var((string) $reminder->email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $typeLabel = ReminderNotificationType::label((string) $reminder->type);

        Notification::route('mail', $reminder->email)->notify(new ReminderNotificationNotification([
            'title' => $typeLabel.' '.$reminder->title,
            'description' => $reminder->description,
            'email' => $reminder->email,
        ]));
    }
}
