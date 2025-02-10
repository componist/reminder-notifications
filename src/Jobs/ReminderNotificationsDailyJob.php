<?php

namespace Componist\ReminderNotifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Componist\ReminderNotifications\Models\ReminderNotification;
use Componist\ReminderNotifications\Notifications\ReminderNotificationNotification;

class ReminderNotificationsDailyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($result = ReminderNotification::where(function($query){
            $query->where(function($query){
                $query->where('daily',date('j'))->where('type','monthly');
            })->orWhere(function($query){
                $query->where('daily',date('j'))->where('monthly',date('n'))->where('type','yearly');
            });
        })->where('status',1)->get()->toArray()){

            foreach($result as $message){
                
                $type = ReminderNotification::getNotificationType($message['type']);

                $params = [
                    'title' => $type. ' '. $message['title'],
                    'description' => $message['description'],
                    'email' => $message['email'],
                ];
                
                Notification::route('mail', $params['email'])->notify(new ReminderNotificationNotification($params));
            }
        }
    }
}