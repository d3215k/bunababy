<?php

namespace App\Livewire\Midwife;

use App\Models\Order;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Schemas\Schema;
use Livewire\Component;

class ScreeningOrderComponent extends Component implements HasActions, HasForms, HasInfolists
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithInfolists;

    public Order $order;

    public function screeningInfolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->order)
            ->components([
                RepeatableEntry::make('screening')
                    ->schema([
                        TextEntry::make('keluhan'),
                        IconEntry::make('penyakit_menular')
                            ->boolean()
                            ->inlineLabel(),
                        IconEntry::make('riwayat_imunisasi')
                            ->boolean()
                            ->inlineLabel(),
                    ]),
            ]);
    }

    public function render()
    {
        return view('livewire.midwife.screening-order-component');
    }
}
