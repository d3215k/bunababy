<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Database\Factories\KabupatenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabupaten extends Model
{
    /** @use HasFactory<KabupatenFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveScope);
    }

    public function kecamatans(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }
}
