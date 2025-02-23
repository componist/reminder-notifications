<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications;

use Componist\ReminderNotifications\Commands\getDailyReminderNotificationsCommands;
use Componist\ReminderNotifications\Commands\getTimesReminderNotificationsCommands;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ReminderNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'reminderNotificationConfig');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Route::group(['middleware' => ['web']], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'remindernotifications');

        $this->commands([
            getTimesReminderNotificationsCommands::class,
            getDailyReminderNotificationsCommands::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // livewire componente
        $this->bootLivewireComponents();

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('app:get-times-reminder-notifications-commands')->everyMinute();
            $schedule->command('app:get-daily-reminder-notifications-commands')->dailyAt('01:00');
        });
    }

    private function bootLivewireComponents(): void
    {
        foreach (config('reminderNotificationConfig.livewire', []) as $alias => $component) {
            Livewire::component(config('reminderNotificationConfig.prefix').$alias, $component);
        }
    }
}