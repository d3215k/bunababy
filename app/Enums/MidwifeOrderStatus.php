<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum MidwifeOrderStatus: int implements HasColor, HasIcon, HasLabel
{
    case BOOKED = 2;
    case ON_HOLD = 4;
    case IN_SERVICE = 6;
    case FINISHED = 7;

    public function getLabel(): ?string
    {

        return match ($this) {
            self::BOOKED => 'Dijadwalkan',
            self::ON_HOLD => 'Ditunda',
            self::IN_SERVICE => 'Mulai Treatment',
            self::FINISHED => 'Selesai Treatment',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BOOKED => 'success',
            self::ON_HOLD => 'warning',
            self::IN_SERVICE => 'info',
            self::FINISHED => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BOOKED => 'heroicon-m-bookmark',
            self::ON_HOLD => 'heroicon-m-pause-circle',
            self::IN_SERVICE => 'heroicon-m-play-circle',
            self::FINISHED => 'heroicon-m-check-circle',
        };
    }
}
