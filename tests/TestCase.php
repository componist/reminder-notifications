<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        View::addNamespace('reminder-notifications-tests', __DIR__.'/views');
        config(['componist.template.dashboard' => 'reminder-notifications-tests::layouts.test']);
        config(['reminderNotificationConfig.admin_user_ids' => []]);
    }
}
