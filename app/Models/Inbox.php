<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sagalbot\Encryptable\Encryptable;

class Inbox extends Model
{
    use HasFactory, Encryptable;

    protected $encryptable = [
        'imap_host',
        'imap_port',
        'imap_username',
        'imap_password',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
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
}
