<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sagalbot\Encryptable\Encryptable;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\MaskNotFoundException;

class Inbox extends Model
{
    use HasFactory, Encryptable, SoftDeletes;

    protected array $encryptable = [
        'imap_username',
        'imap_password',
        'smtp_username',
        'smtp_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'imap_username',
        'imap_password',
        'smtp_username',
        'smtp_password',
    ];


    protected $fillable = [
        'name',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'folder_to_flags_mapping',
        'same_credentials',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
        'template_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'folder_to_flags_mapping' => 'array',
    ];

    public function getClientConnection(string $connectionString): \IMAP\Connection|bool
    {
        return imap_open($connectionString, $this->imap_username, $this->imap_password);
    }

    public function getConnectionString(): string
    {
        return '{' . $this->imap_host . ':' . $this->imap_port .'/' . $this->imap_encryption . '}';
    }

    public function getFolders(): array
    {
        try {
            $connectionString = $this->getConnectionString();
            $connection = $this->getClientConnection($connectionString);
            $folders = imap_list($connection, $connectionString, '*');
            imap_close($connection);
            return $folders;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getFolderFlagMapping($folder): array
    {
        foreach ($this->folder_to_flags_mapping as $mapping) {
            if ($mapping['folder'] == $folder) {
                return $mapping;
            }
        }
        return [];
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InboxTemplate::class, 'template_id');
    }

    public function senderAddresses(): BelongsToMany
    {
        return $this->belongsToMany(EmailAddress::class);
    }
}
