<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->prefix('dashboard/reminder-notification')->name('package.reminder-notification.')->group(function () {
  Route::get('/', \Componist\ReminderNotifications\Livewire\ReminderNotification\Index::class)->name('index');
  Route::get('/create', \Componist\ReminderNotifications\Livewire\ReminderNotification\Create::class)->name('create');
  Route::get('/{editElement}/edit', \Componist\ReminderNotifications\Livewire\ReminderNotification\Edit::class)->name('edit');
});