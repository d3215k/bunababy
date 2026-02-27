<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    public $timestamps = false;

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveScope);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class);
    }
}
