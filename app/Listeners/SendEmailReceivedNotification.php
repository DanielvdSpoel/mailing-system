<?php

namespace App\Listeners;

use App\Events\EmailReceived;
use App\Models\User;
use App\Notifications\EmailReceived as EmailReceivedNotification;
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
     */
    public function handle(EmailReceived $event): void
    {
        Notification::send(User::all(), new EmailReceivedNotification($event->email));
    }
}
