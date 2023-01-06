<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'label',
        'mailbox',
        'domain',
        'email',
    ];

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'sender_address_id');
    }
}
