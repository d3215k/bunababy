<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $name;

    public string $desc;

    public string $address;

    public string $ig;

    public string $phone;

    public static function group(): string
    {
        return 'general';
    }
}
