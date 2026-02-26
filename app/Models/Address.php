<?php

namespace App\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;

    const MAIN_ADDRESS = 1;

    protected $casts = [
        'is_main' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            $kecamatan = $model->kecamatan()->with('kabupaten')->first();

            $model->full_address =
                $model->address . " Desa/Kel. " .
                $model->desa . " Kec. " .
                optional($kecamatan)->name . " " .
                optional(optional($kecamatan)->kabupaten)->name;
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function scopeMainAddress($query)
    {
        return $query->where('is_main', true);
    }
}
