<?php

namespace Componist\ReminderNotifications\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderNotification extends Model
{
    use HasFactory;

    public static function getNotificationType(string $type)
    {
        switch ($type) {
            case 'daily':
                return 'Tägliche Erinnerungsnachricht';
                break;
            case 'monthly':
                return 'Monatliche Erinnerungsnachricht';
                break;
            case 'yearly':
                return 'Jährliche Erinnerungsnachricht';
                break;
        }
    }
}
