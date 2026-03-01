<?php

namespace App\Traits;

trait EnsureOnlyOwnerCanAccess
{
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isOwner;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isOwner;
    }
}
