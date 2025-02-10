<?php

namespace  Componist\ReminderNotifications\Livewire\ReminderNotification;

use Livewire\Component;
use Livewire\WithPagination;
use Componist\ReminderNotifications\Models\ReminderNotification;
use Componist\Core\Traits\addLivewireControlleFunctions;

class Index extends Component
{
    use addLivewireControlleFunctions;
    use WithPagination;

    public ?string $search = null;

    public function render()
    {
        $content = ReminderNotification::where('title', 'LIKE', '%'.trim($this->search).'%')->paginate(25);

        return view('remindernotifications::livewire.reminder-notification.index', compact('content'))->layout(config('core.template.dashboard'));
    }

    public function toggle(int $id, string $field): void
    {
        $temp = ReminderNotification::findOrFail($id);
        $temp->$field = ! $temp->$field;
        $temp->save();
    }

    public function delete(int $id): void
    {
        if (ReminderNotification::find($id)->delete()) {
            $this->bannerMessage('success', 'Eintrag wurde erfolgreich gelöscht');
        } else {
            $this->bannerMessage('danger', 'Fehler beim löschen des Eintrags.');
        }
    }
}