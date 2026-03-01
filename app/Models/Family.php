<?php

namespace App\Models;

use App\Enums\FamilyType;
use App\Support\DateTime;
use Database\Factories\FamilyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Family extends Model
{
    /** @use HasFactory<FamilyFactory> */
    use HasFactory;

    protected $casts = [
        'dob' => 'date',
        'type' => FamilyType::class,
    ];

    protected $appends = ['age'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getAgeAttribute()
    {
        return DateTime::calculateAge($this->dob);
    }
}
