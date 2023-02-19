<?php

namespace App\Models;

use App\Supports\EmailSupport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Sagalbot\Encryptable\Encryptable;

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
        'is_draft',
        'snoozed_until',
        'email_send_by_us',
        'inbox_id',
        'conversation_id',
        'message_id',
        'message_uid',
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

    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public static function createFromImap($connection, $imapUid, Inbox $inbox, array $flags): ?Email
    {
        //Create email object
        $email = new Email();
        $email->message_uid = $imapUid;

        //collect necessary parts of the email
        $structure = imap_fetchstructure($connection, $imapUid, FT_UID);
        $header = imap_rfc822_parse_headers(imap_fetchheader($connection, $imapUid, FT_UID));
        //collect the body
        EmailSupport::handlePart($structure, null, $connection, $imapUid, $email);

        //Collect all other things
        $email->subject = $header->subject;
        $email->message_id = $header->message_id;

        $email->received_at = Carbon::parse($header->date)->setTimezone(config('app.timezone'))->toDateTimeString();
        $email->inbox_id = $inbox->id;

        $sender = $header->from[0];
        $senderEmailAddress = EmailAddress::firstOrCreate(
            ['email' => $sender->mailbox.'@'.$sender->host],
            [
                'label' => $sender->personal ?? $sender->mailbox.'@'.$sender->host,
                'mailbox' => $sender->mailbox,
                'domain' => $sender->host,
            ]
        );
        $email->sender_address_id = $senderEmailAddress->id;

        $reply_to = $header->reply_to[0];
        $replyToEmailAddress = EmailAddress::firstOrCreate(
            ['email' => $reply_to->mailbox.'@'.$reply_to->host],
            [
                'label' => $reply_to->personal ?? $reply_to->mailbox.'@'.$reply_to->host,
                'mailbox' => $reply_to->mailbox,
                'domain' => $reply_to->host,
            ]
        );
        $email->reply_to_address_id = $replyToEmailAddress->id;

        //Handle flags
        $overview = imap_fetch_overview($connection, $email->message_uid, FT_UID)[0];

        if ($overview->seen || $flags['seen']) {
            $email->read_at = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
        }
        if ($overview->deleted || $flags['deleted']) {
            $email->deleted_at = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
        }
        if ($overview->draft || $flags['draft']) {
            $email->is_draft = true;
        }

        //$inbox->senderAddresses()->pluck('email')->contains($email->senderAddress->email ||
        //check if email was send by us
        if ($flags['send']) {
            $email->email_send_by_us = true;
        }

        //Handle conversations
        if (property_exists($header, 'in_reply_to')) {
            $inReplyTo = Email::where('message_id', $header->in_reply_to)->first();
            if ($inReplyTo) {
                if ($inReplyTo->conversation_id) {
                    $email->conversation_id = $inReplyTo->conversation_id;
                } else {
                    $conversation = Conversation::create();
                    $inReplyTo->conversation_id = $conversation->id;
                    $inReplyTo->save();
                    $email->conversation_id = $conversation->id;
                }
            }
        }

        try {
            $email->save();

            return $email;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $email->html_body = null;
            $email->text_body = null;
            $email->save();

            return $email;
        }
    }
}
