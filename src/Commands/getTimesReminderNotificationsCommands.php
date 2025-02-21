<?php

namespace Componist\ReminderNotifications\Commands;

use Componist\ReminderNotifications\Jobs\ReminderNotificationsTimesJob;
use Illuminate\Console\Command;

class getTimesReminderNotificationsCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-times-reminder-notifications-commands';

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
        ReminderNotificationsTimesJob::dispatch();

        return Command::SUCCESS;
    }
}
