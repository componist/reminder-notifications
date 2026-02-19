<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('dashboard/reminder-notification')->name('package.reminder-notification.')->group(function () {
    Route::livewire('/', \Componist\ReminderNotifications\Livewire\ReminderNotification\Index::class)->name('index');
    Route::livewire('/create', \Componist\ReminderNotifications\Livewire\ReminderNotification\Create::class)->name('create');
    Route::livewire('/{editElement}/edit', \Componist\ReminderNotifications\Livewire\ReminderNotification\Edit::class)->name('edit');
});
