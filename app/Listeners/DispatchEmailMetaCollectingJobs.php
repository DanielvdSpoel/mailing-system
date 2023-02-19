<?php

namespace App\Listeners;

use App\Events\EmailReceived;
use App\Jobs\CollectCCAddressesFromEmail;
use App\Jobs\FilterEmail;
use App\Jobs\SaveEmailAttachments;

class DispatchEmailMetaCollectingJobs
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
     * @return void
     */
    public function handle(EmailReceived $event)
    {
        CollectCCAddressesFromEmail::dispatch($event->email)->onQueue('email_meta');
        FilterEmail::dispatch($event->email)->onQueue('email_meta');
        SaveEmailAttachments::dispatch($event->email)->onQueue('email_meta');
    }
}
