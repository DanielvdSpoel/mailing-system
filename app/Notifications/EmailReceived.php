<?php

namespace App\Notifications;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmailReceived extends Notification
{
    use Queueable;

    private Email $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
