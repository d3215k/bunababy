<?php

namespace App\Models;

use Database\Factories\MidwifeFactory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Midwife extends Model
{
    /** @use HasFactory<MidwifeFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveScope);
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class);
    }

    public function kecamatans(): BelongsToMany
    {
        return $this->belongsToMany(Kecamatan::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

}
