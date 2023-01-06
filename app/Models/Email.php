<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sagalbot\Encryptable\Encryptable;
use \Webklex\PHPIMAP\Message;

class Email extends Model
{
    use HasFactory;//, Encryptable;

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



}
