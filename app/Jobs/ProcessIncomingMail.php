<?php

namespace App\Jobs;

use App\Models\Inbox;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        dd($this->inbox);
    }
}
