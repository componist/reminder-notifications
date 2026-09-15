<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Livewire\ReminderNotification;

use Componist\Core\Traits\addLivewireControlleFunctions;
use Componist\ReminderNotifications\Application\ReminderNotificationService;
use Componist\ReminderNotifications\Domain\ReminderNotificationRules;
use Componist\ReminderNotifications\Support\AuthorizesReminderNotifications;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    use addLivewireControlleFunctions;
    use AuthorizesReminderNotifications;

    private string $routeIndex = 'package.reminder-notification.index';

    private string $isRoute = 'package.reminder-notification.create';

    public string $title = '';

    public ?string $description = '';

    public string $type = 'daily';

    public string $time = '';

    public ?int $daily = null;

    public ?int $monthly = null;

    public string $email = '';

    public function mount(): void
    {
        $this->authorizeManage();
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return ReminderNotificationRules::rules($this->type);
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return ReminderNotificationRules::messages();
    }

    public function render(): View
    {
        return view('remindernotifications::livewire.reminder-notification.create')
            ->layout(config('componist.template.dashboard'));
    }

    public function store(): void
    {
        $this->authorizeManage();
        $this->validate();

        ReminderNotificationService::create([
            'title' => $this->title,
            'description' => $this->description,
            'email' => $this->email,
            'type' => $this->type,
            'time' => $this->time,
            'daily' => $this->daily,
            'monthly' => $this->monthly,
        ]);

        $this->flashMessage('success', 'Eintrag wurde erfolgreich gespeichert.');
    }
}
