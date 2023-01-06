<?php

namespace App\Jobs;

use App\Models\Email;
use App\Models\Inbox;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webklex\IMAP\Commands\ImapIdleCommand;

class ProcessIncomingMail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Inbox $inbox;

    public function __construct(Inbox $inbox)
    {
        $this->inbox = $inbox;
    }

    public function handle()
    {
        $connection = $this->inbox->getClientConnection();
        if ($connection) {

            //collect all emails and loop over them
            $emailData = imap_search($connection, '');

            foreach ($emailData as $imapEmail) {
                if (Email::where('inbox_id', $this->inbox->id)->where('message_id', $imapEmail)->exists()) {
                    continue;
                }
                Email::createFromImap($connection, $imapEmail, $this->inbox);
            }
        }
    }
}
