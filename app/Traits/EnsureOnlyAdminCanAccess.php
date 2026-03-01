<?php

namespace App\Traits;

trait EnsureOnlyAdminCanAccess
{
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin;
    }
}
