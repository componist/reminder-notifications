<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Livewire\ReminderNotification;

use Componist\Core\Traits\addLivewireControlleFunctions;
use Componist\ReminderNotifications\Application\ReminderNotificationService;
use Componist\ReminderNotifications\Domain\ReminderNotificationRules;
use Componist\ReminderNotifications\Models\ReminderNotification;
use Componist\ReminderNotifications\Support\AuthorizesReminderNotifications;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
    use addLivewireControlleFunctions;
    use AuthorizesReminderNotifications;

    private string $routeIndex = 'package.reminder-notification.index';

    private string $isRoute = 'package.reminder-notification.create';

    public int $reminderId;

    public string $title = '';

    public ?string $description = '';

    public string $type = 'daily';

    public string $time = '';

    public ?int $daily = null;

    public ?int $monthly = null;

    public string $email = '';

    public function mount(ReminderNotification $editElement): void
    {
        $this->authorizeManage();

        $this->reminderId = (int) $editElement->id;
        $this->title = (string) $editElement->title;
        $this->description = $editElement->description;
        $this->type = (string) $editElement->type;
        $this->time = (string) ($editElement->time ?? '');
        $this->daily = $editElement->daily !== null ? (int) $editElement->daily : null;
        $this->monthly = $editElement->monthly !== null ? (int) $editElement->monthly : null;
        $this->email = (string) $editElement->email;
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
        return view('remindernotifications::livewire.reminder-notification.edit')
            ->layout(config('componist.template.dashboard'));
    }

    public function update(): void
    {
        $this->authorizeManage();
        $this->validate();

        if (! ReminderNotificationService::update($this->reminderId, [
            'title' => $this->title,
            'description' => $this->description,
            'email' => $this->email,
            'type' => $this->type,
            'time' => $this->time,
            'daily' => $this->daily,
            'monthly' => $this->monthly,
        ])) {
            $this->flashMessage('danger', 'Eintrag wurde nicht gefunden.');

            return;
        }

        $this->flashMessage('success', 'Eintrag wurde erfolgreich gespeichert.');
    }
}
