<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'color',
        'parent_id',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Label::class, 'parent_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Label::class, 'parent_id');
    }

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class);
    }
}
