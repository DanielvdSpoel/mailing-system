<?php

namespace App\Listeners;

use App\Events\EmailReceived;
use App\Jobs\FilterEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DispatchFilterEmailJob
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
    public function handle(EmailReceived $event)
    {
        //Todo put it in specialised queue
        FilterEmail::dispatch($event->email);
    }
}
