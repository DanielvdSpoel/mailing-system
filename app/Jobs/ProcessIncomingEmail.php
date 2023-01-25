<?php

namespace App\Jobs;

use App\Events\EmailReceived;
use App\Models\Email;
use App\Models\Inbox;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessIncomingEmail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Inbox $inbox;

    public function __construct(Inbox $inbox)
    {
        $this->inbox = $inbox;
    }

    public function handle()
    {
        $connection = $this->inbox->getClientConnection($this->inbox->getConnectionString());
        $folder_list = imap_list($connection, $this->inbox->getConnectionString() , "*");
        foreach ($folder_list as $folder) {
            $connection = $this->inbox->getClientConnection($folder);
            $flags = $this->inbox->getFolderFlagMapping($folder);

            $emailData = imap_search($connection, '');
            if ($emailData) {
                $this->handleFolder($connection, $emailData, $flags);
            }
        }

    }

    public function handleFolder($connection, $emailData, $flags)
    {
        foreach ($emailData as $imapEmail) {
            if (Email::where('inbox_id', $this->inbox->id)->where('message_uid', imap_uid($connection, $imapEmail))->exists()) {
                continue;
            }
            $email = Email::createFromImap($connection, imap_uid($connection, $imapEmail), $this->inbox, $flags);
            EmailReceived::dispatch($email);
        }
    }
}
