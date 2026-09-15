<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications;

use App\Models\User;
use Componist\ReminderNotifications\Commands\getDailyReminderNotificationsCommands;
use Componist\ReminderNotifications\Commands\getTimesReminderNotificationsCommands;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ReminderNotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'reminderNotificationConfig');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Route::group(['middleware' => ['web']], function (): void {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'remindernotifications');

        $this->commands([
            getTimesReminderNotificationsCommands::class,
            getDailyReminderNotificationsCommands::class,
        ]);
    }

    public function boot(): void
    {
        Gate::define('manage-reminder-notifications', function (?User $user): bool {
            if ($user === null) {
                return false;
            }

            $adminIds = config('reminderNotificationConfig.admin_user_ids', []);

            if ($adminIds !== [] && in_array((int) $user->id, array_map('intval', $adminIds), true)) {
                return true;
            }

            if (Schema::hasColumn('users', 'isAdmin') && (int) ($user->getAttribute('isAdmin') ?? 0) === 1) {
                return true;
            }

            return false;
        });

        $this->bootLivewireComponents();

        $this->app->booted(function (): void {
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
