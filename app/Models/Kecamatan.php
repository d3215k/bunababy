<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Database\Factories\KecamatanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    /** @use HasFactory<KecamatanFactory> */
    use HasFactory;

    protected $casts = [
        'distance' => 'integer',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveScope);
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function midwives(): BelongsToMany
    {
        return $this->belongsToMany(Midwife::class);
    }
}
