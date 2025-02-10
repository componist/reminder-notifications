<?php

namespace Componist\ReminderNotifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Componist\ReminderNotifications\Models\ReminderNotification;
use Componist\ReminderNotifications\Notifications\ReminderNotificationNotification;

class ReminderNotificationsTimesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $time = date('H:i');
        
        if($result = ReminderNotification::where('time',$time)->where('type','daily')->where('status',1)->get()->toArray()){

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