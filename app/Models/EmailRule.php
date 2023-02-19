<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'conditions',
        'actions',
        'reversed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'conditions' => 'array',
        'actions' => 'array',
    ];
}
