<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Jobs;

use Componist\ReminderNotifications\Application\ReminderDispatchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReminderNotificationsTimesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        ReminderDispatchService::dispatchDailyByTime(date('H:i'));
    }
}
