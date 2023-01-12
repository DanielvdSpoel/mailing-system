<?php

namespace App\Jobs;

use App\Models\Email;
use App\Models\EmailAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CollectCCAddressesFromEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public Email $email;
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $connection = $this->email->inbox->getClientConnection();
        $header = imap_rfc822_parse_headers(imap_fetchheader($connection, $this->email->message_uid, FT_UID));

        foreach ($header->cc as $ccAdress) {
            $ccEmailAdress = EmailAddress::firstOrCreate(
                ['email' => $ccAdress->mailbox . '@' . $ccAdress->host],
                [
                    'label' => $ccAdress->personal ?? $ccAdress->mailbox . '@' . $ccAdress->host,
                    'mailbox' => $ccAdress->mailbox,
                    'domain' => $ccAdress->host
                ]
            );
            $this->email->addresses()->attach($ccEmailAdress, ['type' => 'cc']);
        }
    }
}
