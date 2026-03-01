<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SlotPart: int implements HasColor, HasLabel
{
    case MORNING = 1;
    case AFTERNOON = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::MORNING => 'Pagi',
            self::AFTERNOON => 'Sore',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MORNING => 'success',
            self::AFTERNOON => 'warning',
        };
    }
}
