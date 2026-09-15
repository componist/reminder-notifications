<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Tests\Unit\Domain;

use Componist\ReminderNotifications\Domain\ReminderNotificationRules;
use Componist\ReminderNotifications\Domain\ReminderNotificationType;
use PHPUnit\Framework\TestCase;

class ReminderNotificationDomainTest extends TestCase
{
    public function test_toggle_field_allowlist(): void
    {
        $this->assertTrue(ReminderNotificationType::isAllowedToggleField('status'));
        $this->assertFalse(ReminderNotificationType::isAllowedToggleField('email'));
    }

    public function test_type_labels_are_german(): void
    {
        $this->assertSame('Tägliche Erinnerungsnachricht', ReminderNotificationType::label('daily'));
    }

    public function test_schedule_attributes_for_daily_type(): void
    {
        $attributes = ReminderNotificationRules::scheduleAttributes([
            'title' => 'Test',
            'email' => 'a@b.de',
            'type' => 'daily',
            'time' => '08:00',
            'daily' => 5,
            'monthly' => 3,
        ]);

        $this->assertSame('08:00', $attributes['time']);
        $this->assertNull($attributes['daily']);
        $this->assertNull($attributes['monthly']);
    }
}
