<?php

namespace Componist\ReminderNotifications\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReminderNotification extends Model
{
    use HasFactory;
    use SoftDeletes;

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
