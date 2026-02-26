<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy(CustomerObserver::class)]
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    protected $casts = [
        'dob' => 'date',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function latestOrder() : HasOne
    {
        return $this->hasOne(Order::class)->latestOfMany();
    }

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function getAddressAttribute()
    {
        return $this->addresses()->mainAddress()->select('id', 'kecamatan_id')->with('kecamatans:id,name')->first()
            ->kecamatans->name ?? NULL;
    }
}
