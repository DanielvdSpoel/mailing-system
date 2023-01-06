<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sagalbot\Encryptable\Encryptable;
use \Webklex\PHPIMAP\Message;

class Email extends Model
{
    use HasFactory, SoftDeletes;

    //, Encryptable;

    protected $fillable = [
        'subject',
        'text_body',
        'html_body',
        'sender_address_id',
        'received_at',
        'archived_at',
        'deleted_at',
        'read_at',
        'inbox_id',
    ];


    /*protected array $encryptable = [
        'subject',
        'text_body',
        'html_body',
    ];*/

    public function inbox(): BelongsTo
    {
        return $this->belongsTo(Inbox::class);
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(EmailAddress::class);
    }

    public function senderAddress(): BelongsTo
    {
        return $this->belongsTo(EmailAddress::class, 'sender_address_id');
    }

    public function getRecipientAddresses(): BelongsToMany
    {
        return $this->addresses()->wherePivot('type', 'to');
    }

    public function ccAddresses(): BelongsToMany
    {
        return $this->addresses()->wherePivot('type', 'cc');
    }

    public function bbcAddresses(): BelongsToMany
    {
        return $this->addresses()->wherePivot('type', 'bcc');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }


    public static function createFromImap($connection, $imapId, Inbox $inbox): ?Email
    {
        //Create email object
        $email = new Email();
        $email->message_id = $imapId;

        //collect necessary parts of the email
        $structure = imap_fetchstructure($connection, $imapId);
        $header = imap_rfc822_parse_headers(imap_fetchheader($connection, $imapId));

        //collect the body
        EmailSupport::handlePart($structure, null, $connection, $imapId, $email);

        //Collect all other things
        $email->subject = $header->subject;

        $email->received_at = Carbon::parse($header->date)->setTimezone(config('app.timezone'))->toDateTimeString();
        $email->inbox_id = $inbox->id;

        $sender = $header->from[0];
        $senderEmailAddress = EmailAddress::firstOrCreate(
            ['email' => $sender->mailbox . '@' . $sender->host],
            [
                'label' => $sender->personal ?? $sender->mailbox . '@' . $sender->host,
                'mailbox' => $sender->mailbox,
                'domain' => $sender->host
            ]
        );
        $email->sender_address_id = $senderEmailAddress->id;

        $reply_to = $header->reply_to[0];
        $replyToEmailAddress = EmailAddress::firstOrCreate(
            ['email' => $reply_to->mailbox . '@' . $reply_to->host],
            [
                'label' => $reply_to->personal ?? $reply_to->mailbox . '@' . $reply_to->host,
                'mailbox' => $reply_to->mailbox,
                'domain' => $reply_to->host
            ]
        );
        $email->reply_to_address_id = $replyToEmailAddress->id;
        try {
            $email->save();
            return $email;
        } catch (\Exception $e) {
            Log::critical("We could not save the email with id " . $email->message_id . " from inbox " . $email->inbox_id);
            Log::critical("Email was send by " . $email->senderAddress->email);
            Log::critical("Email subject was " . $email->subject);
            return null;
        }
    }

}
