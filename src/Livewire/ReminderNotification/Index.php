<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Livewire\ReminderNotification;

use Componist\Core\Traits\addLivewireControlleFunctions;
use Componist\ReminderNotifications\Application\ReminderNotificationListQuery;
use Componist\ReminderNotifications\Application\ReminderNotificationService;
use Componist\ReminderNotifications\Domain\ReminderNotificationType;
use Componist\ReminderNotifications\Support\AuthorizesReminderNotifications;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use addLivewireControlleFunctions;
    use AuthorizesReminderNotifications;
    use WithPagination;

    public ?string $search = null;

    public function mount(): void
    {
        $this->authorizeManage();
    }

    public function render(): View
    {
        $content = ReminderNotificationListQuery::paginate($this->search);

        return view('remindernotifications::livewire.reminder-notification.index', compact('content'))
            ->layout(config('componist.template.dashboard'));
    }

    public function toggle(int $id, string $field): void
    {
        $this->authorizeManage();

        if (! ReminderNotificationType::isAllowedToggleField($field)) {
            $this->flashMessage('danger', 'Ungültiges Feld für die Statusänderung.');

            return;
        }

        if (! ReminderNotificationService::toggle($id, $field)) {
            $this->flashMessage('danger', 'Eintrag wurde nicht gefunden.');

            return;
        }

        $this->flashMessage('success', 'Status wurde aktualisiert.');
    }

    public function delete(int $id): void
    {
        $this->authorizeManage();

        if (ReminderNotificationService::delete($id)) {
            $this->flashMessage('success', 'Eintrag wurde erfolgreich gelöscht.');
        } else {
            $this->flashMessage('danger', 'Fehler beim Löschen des Eintrags.');
        }
    }
}
