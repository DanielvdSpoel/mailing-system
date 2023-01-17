<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sagalbot\Encryptable\Encryptable;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\MaskNotFoundException;

class Inbox extends Model
{
    use HasFactory, Encryptable;

    protected array $encryptable = [
        'imap_host',
        'imap_port',
        'imap_username',
        'imap_password',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'last_successful_connection_at'
    ];

    protected $fillable = [
        'label',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'same_credentials',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
    ];

    public function getClientConnection()
    {
        return imap_open( '{' . $this->imap_host . ':' . $this->imap_port .'/imap/' . $this->imap_encryption . '}INBOX', $this->imap_username, $this->imap_password);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }
}
