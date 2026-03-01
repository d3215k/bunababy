<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserType: int implements HasColor, HasLabel
{
    case CUSTOMER = 1;
    case MIDWIFE = 2;
    case ADMIN = 3;
    case OWNER = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::CUSTOMER => 'Pelanggan',
            self::MIDWIFE => 'Bidan',
            self::ADMIN => 'Admin',
            self::OWNER => 'Owner',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CUSTOMER => 'primary',
            self::MIDWIFE => 'success',
            self::ADMIN => 'danger',
            self::OWNER => 'warning',
        };
    }
}
