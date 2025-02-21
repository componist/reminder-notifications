<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
     */

    'components' => [

    ],
    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
     */

    'livewire' => [
        'reminder-notification.index' => Componist\ReminderNotifications\Livewire\ReminderNotification\Index::class,
        'reminder-notification.create' => Componist\ReminderNotifications\Livewire\ReminderNotification\Create::class,
        'reminder-notification.edit' => Componist\ReminderNotifications\Livewire\ReminderNotification\Edit::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Components Prefix
    |--------------------------------------------------------------------------
     */

    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Third Party Asset Libraries
    |--------------------------------------------------------------------------
     */

    'assets' => [],

];
