<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Models;

use Componist\ReminderNotifications\Domain\ReminderNotificationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReminderNotification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'email',
        'type',
        'time',
        'daily',
        'monthly',
        'status',
    ];

    public static function getNotificationType(string $type): string
    {
        return ReminderNotificationType::label($type);
    }
}
