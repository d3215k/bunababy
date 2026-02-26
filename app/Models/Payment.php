<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    protected $casts = [
        'value' => 'integer',
        'status' => PaymentStatus::class,
        'verified_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function verificator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }

    public function scopeVerified($query)
    {
        $query->where('status', PaymentStatus::VERIFIED);
    }

    public function scopeUnVerified($query)
    {
        $query->where('status', PaymentStatus::UNVERIFIED);
    }

    public function scopeRejected($query)
    {
        $query->where('status', PaymentStatus::REJECTED);
    }
}
