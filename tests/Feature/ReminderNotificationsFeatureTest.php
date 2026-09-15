<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Tests\Feature;

use App\Models\User;
use Componist\ReminderNotifications\Jobs\ReminderNotificationsDailyJob;
use Componist\ReminderNotifications\Jobs\ReminderNotificationsTimesJob;
use Componist\ReminderNotifications\Livewire\ReminderNotification\Create;
use Componist\ReminderNotifications\Livewire\ReminderNotification\Edit;
use Componist\ReminderNotifications\Livewire\ReminderNotification\Index;
use Componist\ReminderNotifications\Models\ReminderNotification;
use Componist\ReminderNotifications\Notifications\ReminderNotificationNotification;
use Componist\ReminderNotifications\Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

class ReminderNotificationsFeatureTest extends TestCase
{
    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('package.reminder-notification.index'))->assertRedirect();
        $this->get(route('package.reminder-notification.create'))->assertRedirect();
    }

    public function test_non_admin_gets_forbidden(): void
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)
            ->get(route('package.reminder-notification.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_index_without_description_column(): void
    {
        $admin = $this->adminUser();

        $this->createReminder([
            'title' => 'Alpha Reminder',
            'description' => 'SECRET_DESCRIPTION_BODY',
        ]);

        $this->actingAs($admin)
            ->get(route('package.reminder-notification.index'))
            ->assertOk()
            ->assertSee('Alpha Reminder', false)
            ->assertSee('Titel', false)
            ->assertDontSee('SECRET_DESCRIPTION_BODY', false);
    }

    public function test_admin_can_search_by_title(): void
    {
        $admin = $this->adminUser();
        $this->createReminder(['title' => 'Needle']);
        $this->createReminder(['title' => 'Haystack']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'Need')
            ->assertSee('Needle')
            ->assertDontSee('Haystack');
    }

    public function test_toggle_only_allows_status_field(): void
    {
        $admin = $this->adminUser();
        $reminder = $this->createReminder(['status' => 1]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('toggle', $reminder->id, 'email')
            ->assertOk();

        $this->assertDatabaseHas('reminder_notifications', [
            'id' => $reminder->id,
            'email' => $reminder->email,
            'status' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('toggle', $reminder->id, 'status');

        $this->assertDatabaseHas('reminder_notifications', [
            'id' => $reminder->id,
            'status' => 0,
        ]);
    }

    public function test_admin_can_soft_delete(): void
    {
        $admin = $this->adminUser();
        $reminder = $this->createReminder(['title' => 'Delete me']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('delete', $reminder->id)
            ->assertOk();

        $this->assertSoftDeleted('reminder_notifications', [
            'id' => $reminder->id,
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(Create::class)
            ->call('store')
            ->assertHasErrors(['title' => 'required', 'email' => 'required']);
    }

    public function test_create_rejects_invalid_type(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(Create::class)
            ->set('title', 'Test')
            ->set('email', 'test@example.com')
            ->set('type', 'hourly')
            ->set('time', '12:00')
            ->call('store')
            ->assertHasErrors(['type']);
    }

    public function test_admin_can_create_daily_reminder(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(Create::class)
            ->set('title', 'Test Titel')
            ->set('description', 'Test Beschreibung')
            ->set('email', 'test@example.com')
            ->set('type', 'daily')
            ->set('time', '12:34')
            ->call('storeAndIndex')
            ->assertRedirect(route('package.reminder-notification.index'));

        $this->assertDatabaseHas('reminder_notifications', [
            'title' => 'Test Titel',
            'email' => 'test@example.com',
            'type' => 'daily',
            'time' => '12:34',
        ]);
    }

    public function test_admin_can_update_reminder(): void
    {
        $admin = $this->adminUser();
        $reminder = $this->createReminder([
            'title' => 'Alt',
            'email' => 'old@example.com',
            'type' => 'monthly',
            'time' => null,
            'daily' => 5,
        ]);

        Livewire::actingAs($admin)
            ->test(Edit::class, ['editElement' => $reminder])
            ->set('title', 'Neu')
            ->set('email', 'new@example.com')
            ->set('type', 'yearly')
            ->set('daily', 10)
            ->set('monthly', 12)
            ->call('update')
            ->assertOk();

        $this->assertDatabaseHas('reminder_notifications', [
            'id' => $reminder->id,
            'title' => 'Neu',
            'email' => 'new@example.com',
            'type' => 'yearly',
            'daily' => '10',
            'monthly' => '12',
        ]);
    }

    public function test_times_command_dispatches_job(): void
    {
        Bus::fake();

        $this->artisan('app:get-times-reminder-notifications-commands')
            ->assertSuccessful();

        Bus::assertDispatched(ReminderNotificationsTimesJob::class);
    }

    public function test_daily_command_dispatches_job(): void
    {
        Bus::fake();

        $this->artisan('app:get-daily-reminder-notifications-commands')
            ->assertSuccessful();

        Bus::assertDispatched(ReminderNotificationsDailyJob::class);
    }

    public function test_times_job_sends_daily_notifications_matching_current_time(): void
    {
        Notification::fake();

        $this->createReminder([
            'title' => 'Daily',
            'description' => 'Desc',
            'email' => 'daily@example.com',
            'type' => 'daily',
            'time' => date('H:i'),
            'status' => 1,
        ]);

        (new ReminderNotificationsTimesJob)->handle();

        Notification::assertSentOnDemand(ReminderNotificationNotification::class, function (ReminderNotificationNotification $notification, array $channels, object $notifiable): bool {
            return in_array('mail', $channels, true)
                && method_exists($notifiable, 'routeNotificationFor')
                && $notifiable->routeNotificationFor('mail') === 'daily@example.com';
        });
    }

    public function test_daily_job_sends_monthly_and_yearly_notifications_matching_today(): void
    {
        Notification::fake();

        $this->createReminder([
            'title' => 'Monthly',
            'email' => 'monthly@example.com',
            'type' => 'monthly',
            'time' => null,
            'daily' => (int) date('j'),
            'status' => 1,
        ]);

        $this->createReminder([
            'title' => 'Yearly',
            'email' => 'yearly@example.com',
            'type' => 'yearly',
            'time' => null,
            'daily' => (int) date('j'),
            'monthly' => (int) date('n'),
            'status' => 1,
        ]);

        (new ReminderNotificationsDailyJob)->handle();

        Notification::assertSentOnDemand(ReminderNotificationNotification::class, 2);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createReminder(array $overrides = []): ReminderNotification
    {
        return ReminderNotification::query()->create(array_merge([
            'title' => 'Reminder',
            'description' => null,
            'email' => 'reminder@example.com',
            'type' => 'daily',
            'time' => '08:00',
            'daily' => null,
            'monthly' => null,
            'status' => 1,
        ], $overrides));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function verifiedUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ], $overrides));
    }

    private function adminUser(): User
    {
        $admin = $this->verifiedUser([
            'name' => 'Reminder Admin',
            'email' => 'reminder-admin@example.com',
        ]);

        config(['reminderNotificationConfig.admin_user_ids' => [$admin->id]]);

        return $admin;
    }
}
