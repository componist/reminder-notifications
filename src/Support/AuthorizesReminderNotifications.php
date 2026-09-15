<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Support;

use Illuminate\Support\Facades\Gate;

trait AuthorizesReminderNotifications
{
    protected function authorizeManage(): void
    {
        abort_unless(Gate::allows('manage-reminder-notifications'), 403);
    }
}
