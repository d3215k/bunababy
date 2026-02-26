<?php

namespace App\Models;

use Database\Factories\TimetableFactory;
use App\Enums\TimetableType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    /** @use HasFactory<TimetableFactory> */
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'type' => TimetableType::class,
    ];

    public function midwife(): BelongsTo
    {
        return $this->belongsTo(Midwife::class);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
