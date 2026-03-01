<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PlaceType: int implements HasColor, HasLabel
{
    case HOMECARE = 1;
    case CLINIC = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::HOMECARE => 'Homecare',
            self::CLINIC => 'Klinik',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::HOMECARE => 'success',
            self::CLINIC => 'warning',
        };
    }
}
