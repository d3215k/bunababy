<?php

namespace App\Models;

use Database\Factories\PlaceFactory;
use App\Enums\PlaceType;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    /** @use HasFactory<PlaceFactory> */
    use HasFactory;

    protected $casts = [
        'active' => 'boolean',
        'type' => PlaceType::class,
    ];

    protected static function booted(): void
    {
        static::addGlobalScopes([
            ActiveScope::class,
            SortScope::class
        ]);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class, 'prices');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class);
    }

    public function scopeClinics($query)
    {
        return $query->where('type', PlaceType::CLINIC);
    }
}
