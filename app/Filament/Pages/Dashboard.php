<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function mount()
    {
        if (auth()->user()->isMidwife) {
            return to_route('midwife.dashboard');
        }
    }
}
