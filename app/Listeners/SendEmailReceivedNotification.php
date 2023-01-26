<?php

namespace App\Listeners;

use App\Events\EmailReceived;
use \App\Notifications\EmailReceived as EmailReceivedNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class SendEmailReceivedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EmailReceived  $event
     * @return void
     */
    public function handle(EmailReceived $event): void
    {
        Notification::send(User::all(), new EmailReceivedNotification($event->email));



    }
}
