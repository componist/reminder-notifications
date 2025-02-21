<?php

namespace Componist\ReminderNotifications\Livewire\ReminderNotification;

use Componist\Core\Traits\addLivewireControlleFunctions;
use Componist\ReminderNotifications\Models\ReminderNotification;
use Livewire\Component;

class Create extends Component
{
    use addLivewireControlleFunctions;

    private string $routeIndex = 'package.reminder-notification.index';

    private string $isRoute = 'package.reminder-notification.create';

    public string $title = '';

    public ?string $description = '';

    public string $type = 'daily';

    public string $time = '';

    public ?int $daily = null;

    public ?int $monthly = null;

    public string $email = '';

    protected $rules = [
        'title' => 'required|string',
        'description' => 'nullable|string',
        'email' => 'required|email:rfc',
        'type' => 'required|string',
        'time' => 'nullable|string',
        'daily' => 'nullable|numeric',
        'monthly' => 'nullable|numeric',
    ];

    public function render()
    {
        return view('remindernotifications::livewire.reminder-notification.create')->layout(config('core.template.dashboard'));
    }

    public function store(): void
    {
        $this->validate();

        if (ReminderNotification::insert([
            'title' => $this->title,
            'description' => $this->description,
            'email' => $this->email,
            'type' => $this->type,
            'time' => $this->time,
            'daily' => $this->daily,
            'monthly' => $this->monthly,
            'created_at' => date('Y-m-d H:i:s'),
        ])) {
            $this->bannerMessage('success', 'Eintrag wurde erfolgreich gespeichert');
        } else {
            $this->bannerMessage('danger', 'Fehler beim speichern des Eintrags.');
        }

    }
}
