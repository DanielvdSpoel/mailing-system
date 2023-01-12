<?php

namespace App\Jobs;

use App\Models\Email;
use App\Supports\EmailSupport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SaveEmailAttachments implements ShouldQueue
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
        $structure = imap_fetchstructure($connection, $this->email->message_uid, FT_UID);

        self::handlePart($structure, null, $connection, $this->email->message_uid, $this->email);

        dd($structure);
    }

    public function handlePart($part, $partNumber, $connection, $message_uid, $email )
    {
        if ($part->ifdisposition == 1 && $part->disposition == 'ATTACHMENT') {
            $filename = $part->dparameters[0]->value;
            $attachment = imap_fetchbody($connection, $message_uid, $partNumber, FT_UID);
            $attachment = base64_decode($attachment);

            $model = $email->attachments()->create([
                'filename' => $filename,
            ]);

            Storage::disk('local')->put('/attachments/' . $email->id . '/' . $model->id . '-' . $filename, $attachment);
            $model->path = '/attachments/' . $email->id . '/' . $model->id . '-' . $filename;
            $model->save();

        } else if (in_array($part->subtype, ['ALTERNATIVE', 'MIXED', 'RELATED'])) {
            for ($i = 1 ; $i < count($part->parts) + 1; $i++)
            {
                $subPart = $part->parts[$i - 1];
                $newPartNumber = $partNumber !== null ? $partNumber . '.' . $i : $i;
                self::handlePart($subPart, $newPartNumber, $connection, $message_uid, $email);
            }
        }
    }
}
