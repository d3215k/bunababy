<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: int implements HasColor, HasIcon, HasLabel
{
    case CANCELLED = 0;
    case PENDING = 1;
    case BOOKED = 2;
    case ON_HOLD = 4;
    case IN_SERVICE = 6;
    case FINISHED = 7;
    case COMPLETED = 9;

    public function getLabel(): ?string
    {

        return match ($this) {
            self::CANCELLED => 'Dibatalkan',
            self::PENDING => 'Pending',
            self::BOOKED => 'Dijadwalkan',
            self::ON_HOLD => 'Ditunda',
            self::IN_SERVICE => 'Mulai Treatment',
            self::FINISHED => 'Selesai Treatment',
            self::COMPLETED => 'Selesai',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CANCELLED => 'danger',
            self::PENDING => 'danger',
            self::BOOKED => 'success',
            self::ON_HOLD => 'warning',
            self::IN_SERVICE => 'info',
            self::FINISHED => 'primary',
            self::COMPLETED => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CANCELLED => 'heroicon-m-x-circle',
            self::PENDING => 'heroicon-m-exclamation-circle',
            self::BOOKED => 'heroicon-m-bookmark',
            self::ON_HOLD => 'heroicon-m-pause-circle',
            self::IN_SERVICE => 'heroicon-m-play-circle',
            self::FINISHED => 'heroicon-m-check-circle',
            self::COMPLETED => 'heroicon-m-check-badge',
        };
    }
}
