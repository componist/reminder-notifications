<?php

namespace Componist\ReminderNotifications\Commands;

use Componist\ReminderNotifications\Jobs\ReminderNotificationsDailyJob;
use Illuminate\Console\Command;

class getDailyReminderNotificationsCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-daily-reminder-notifications-commands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ReminderNotificationsDailyJob::dispatch();

        return Command::SUCCESS;
    }
}