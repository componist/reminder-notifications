<?php

declare(strict_types=1);

namespace Componist\ReminderNotifications\Application;

use Componist\ReminderNotifications\Models\ReminderNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ReminderNotificationListQuery
{
    /** @var list<string> */
    private const LIST_COLUMNS = [
        'id', 'title', 'email', 'type', 'time', 'daily', 'monthly', 'status', 'created_at',
    ];

    public static function paginate(?string $search, int $perPage = 25): LengthAwarePaginator
    {
        $query = ReminderNotification::query()
            ->select(self::LIST_COLUMNS)
            ->orderByDesc('created_at');

        if (filled($search)) {
            $term = str_replace(['%', '_'], ['\%', '\_'], trim($search));
            $query->where('title', 'like', '%'.$term.'%');
        }

        return $query->paginate($perPage);
    }
}
