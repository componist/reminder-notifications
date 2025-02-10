<?php

namespace Componist\ReminderNotifications\Livewire\ReminderNotification;

use Componist\ReminderNotifications\Models\ReminderNotification;
use Livewire\Component;
use Componist\Core\Traits\addLivewireControlleFunctions;

class Edit extends Component
{
    use addLivewireControlleFunctions;

    private string $routeIndex = 'package.reminder-notification.index';

    private string $isRoute = 'package.reminder-notification.create';

    public array $oldEntry = [];

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

    public function mount(ReminderNotification $editElement): void
    {
        $this->oldEntry = collect($editElement)->toArray();

        $this->title = $editElement['title'];
        $this->description = $editElement['description'];
        $this->type = $editElement['type'];
        $this->time = $editElement['time'];
        $this->daily = $editElement['daily'];
        $this->monthly = $editElement['monthly'];
        $this->email = $editElement['email'];

    }

    public function render()
    {
        return view('remindernotifications::livewire.reminder-notification.edit')->layout(config('core.template.dashboard'));
    }

    public function update(): void
    {
        $this->validate();

        if (ReminderNotification::where('id', $this->oldEntry['id'])->update([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'time' => $this->time,
            'daily' => $this->daily,
            'monthly' => $this->monthly,
            'email' => $this->email,
            'updated_at' => date('Y-m-d H:i:s'),
        ])) {
            $this->bannerMessage('success', 'Eintrag wurde erfolgreich gespeichert');
        } else {
            $this->bannerMessage('danger', 'Fehler beim speichern des Eintrags.');
        }

    }
}