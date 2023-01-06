<?php

namespace App\Jobs;

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
        try {
            $client = $this->inbox->getClientConnection();
            $folders = $client->getFolders();
            dd($folders);
            /** @var \Webklex\PHPIMAP\Folder $folder */
            foreach ($folders as $folder){
                dd($folder->messages()->all());
            }


        } catch (\Exception|\Throwable $e) {
            $this->fail($e);
        }
    }
}
