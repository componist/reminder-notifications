<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Domain;

final class ReminderNotificationType
{
    public const DAILY = 'daily';

    public const MONTHLY = 'monthly';

    public const YEARLY = 'yearly';

    /**
     * @return list<string>
     */
    public static function allowed(): array
    {
        return [self::DAILY, self::MONTHLY, self::YEARLY];
    }

    public static function label(string $type): string
    {
        return match ($type) {
            self::DAILY => 'Tägliche Erinnerungsnachricht',
            self::MONTHLY => 'Monatliche Erinnerungsnachricht',
            self::YEARLY => 'Jährliche Erinnerungsnachricht',
            default => 'Erinnerungsnachricht',
        };
    }

    public static function isAllowedToggleField(string $field): bool
    {
        return $field === 'status';
    }
}
