<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Support\DateTime;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    const TRAVEL_TIME = 40;

    protected $casts = [
        'treatments' => 'array',
        'screening' => 'array',
        'report' => 'array',
        'transport' => 'integer',
        'adjustment_amount' => 'integer',
        'date' => 'date',
        'status' => OrderStatus::class,
        'finished_at' => 'datetime',
        'startDateTime' => 'datetime',
        'endDateTime' => 'datetime',
    ];

    public function getInvoice()
    {
        return 'INV/'.$this->date->format('Ymd').'/'.$this->id;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'place_id', 'id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function midwife(): BelongsTo
    {
        return $this->belongsTo(Midwife::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function scopeUnpaid($query)
    {
        $query->where('status', OrderStatus::PENDING);
    }

    public function scopeLocked($query)
    {
        $query->where('status', OrderStatus::BOOKED);
    }

    public function scopefinished($query)
    {
        $query->where('status', OrderStatus::COMPLETED);
    }

    public function getStartDateTimeAttribute()
    {
        return Carbon::parse(Carbon::parse($this->date)->toDateString().' '.$this->start_time);
    }

    public function getEndDateTimeAttribute()
    {
        return Carbon::parse(Carbon::parse($this->date)->toDateString().' '.$this->end_time)
            ->addMinutes($this->place->transport_duration);
    }

    public function scopeActiveBetween($query, $from, $to)
    {
        $query->whereNot('status', OrderStatus::CANCELLED)
            ->betweenTimes($from, $to);
    }

    public function scopeBetweenTimes($query, $from, $to)
    {
        $query->where(function ($query) use ($from, $to) {
            $query
                ->whereBetween('start_time', [$from, $to])
                ->orWhereBetween('end_time', [$from, $to])
                ->orWhere(function ($query) use ($from, $to) {
                    $query
                        ->where('start_time', '<', $from)
                        ->where('end_time', '>', $to);
                });
        });
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function pendingPayments(): HasMany
    {
        return $this->hasMany(Payment::class)->where('status', PaymentStatus::UNVERIFIED);
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class)->where('status', PaymentStatus::VERIFIED);
    }

    public function getVerifiedPayments()
    {
        return $this->payments()->verified()->pluck('value')->sum();
    }

    public function isPaid()
    {
        return $this->getVerifiedPayments() >= $this->getGrandTotal();
    }

    public function dp()
    {
        return $this->getVerifiedPayments() >= $this->getGrandTotal() * 50 / 100;
    }

    public function getDpAmount()
    {
        return $this->getGrandTotal() / 2;
    }

    public function getRemainingPayment()
    {
        return $this->getGrandTotal() - $this->verifiedPayments->pluck('value')->sum();
    }

    public function getListTreatmentsAttribute()
    {
        return collect($this->treatments)->pluck('treatment_name')->implode(', ');
    }

    public function getListTreatmentsWithFamilyAttribute()
    {
        return collect($this->treatments)->map(function ($treatment) {
            return $treatment['treatment_name'].' ('.$treatment['family_name'].' '.DateTime::calculateAge($treatment['family_dob']).')';
        })->implode(', ');
    }

    public function getTotalPriceAttribute()
    {
        return collect($this->treatments)->sum('treatment_price');
    }

    public function getTotalDurationAttribute()
    {
        return collect($this->treatments)->sum('treatment_duration');
    }

    public function getGrandTotal()
    {
        return $this->total_price + $this->transport + $this->adjustment_amount;
    }

    public function getTime()
    {
        return Carbon::parse($this->start_time)->format('H:i').' - '.Carbon::parse($this->end_time)
            ->format('H:i');
    }

    public function getLongTime()
    {
        return $this->getTime().' WIB';
    }

    public function getLongDate()
    {
        return Carbon::parse($this->date)->format('d/m/Y');
    }

    public function getShortDate()
    {
        return Carbon::parse($this->date)->format('d/m/Y');
    }

    public function getLongDateTime()
    {
        return $this->getLongDate().' '.$this->getLongTime();
    }

    public static function getCalculatedEndTime($date, $startTime, $treatments, $transportDuration = 0)
    {
        $startTime = Carbon::parse($date)->setTimeFrom(Carbon::parse($startTime));
        $totalDuration = collect($treatments)->sum('treatment_duration');
        $endTime = $startTime->addMinutes($totalDuration + $transportDuration);

        return $endTime->format('H:i');
    }

    public static function getCalculatedTransport($distance)
    {
        return match (true) {
            $distance <= 2 => 15000,
            $distance <= 3 => 18000,
            $distance <= 5 => 25000,
            $distance <= 7 => 30000,
            $distance <= 9 => 33000,
            $distance <= 20 => 38000,
            default => 40000,
        };
    }
}
