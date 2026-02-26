<?php

namespace App\Models;

use Database\Factories\SlotFactory;
use App\Enums\SlotPart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slot extends Model
{
    /** @use HasFactory<SlotFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'part' => SlotPart::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope('time', function (Builder $builder) {
            $builder->orderBy('time');
        });
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
