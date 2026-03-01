<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogLastUserLogedIn
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $event->user->update([
            'last_login' => now(),
        ]);
    }
}
