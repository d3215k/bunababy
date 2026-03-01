<?php

namespace App\Support;

class FormatCurrency
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function rupiah($value)
    {
        $isNegative = false;
        if ($value < 0) {
            $isNegative = true;
        }

        return ($isNegative ? '-Rp' : 'Rp').number_format(abs($value), 2, ',', '.');
    }
}
