<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\PlaceType;
use App\Exceptions\NoSlotException;
use App\Filament\Resources\OrderResource;
use App\Services\OrderService;
use App\Support\DateTime;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Throwable;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->components([
                Wizard::make($this->getSteps())
                    // ->startOnStep($this->getStartStep())
                    // ->cancelAction($this->getCancelFormAction())
                    // ->submitAction($this->getSubmitFormAction())
                    // ->skippable($this->hasSkippableSteps())
                    // ->contained(false)
                    // ->skippable(false)
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button
                            wire:click="handleSubmit"
                            size="sm"
                        >
                            Submit Formulir
                        </x-filament::button>
                    BLADE))),
            ])
            ->columns(null);
    }

    public function handleSubmit()
    {
        $this->validate();

        try {
            $data = $this->form->getState();
            $order = (new OrderService)->create($data);

            Notification::make()->body('Berhasil disimpan.')->success()->send();

            return redirect()->route('filament.admin.resources.orders.edit', $order);
        } catch (NoSlotException $e) {
            return Notification::make()->title('Whoops!')->body($e->getMessage())->danger()->send();
        } catch (Throwable $th) {
            report($th);

            return Notification::make()->title('Whoops!')->body('Ada yang salah')->danger()->send();
        }
    }

    protected function afterCreate(): void
    {
        //
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Customer')
                ->schema([
                    Section::make()->schema(OrderResource::getDetailsFormSchema())->columns(),
                ])
                ->afterValidation(function () {
                    //
                }),
            Step::make('Skrining')
                ->schema([
                    Section::make()->schema([
                        OrderResource::getScreeningRepeater(),
                    ])->columns(1),
                ])
                ->afterValidation(function () {
                    //
                }),
            Step::make('Bidan')
                ->schema([
                    Section::make()->schema(OrderResource::getPlaceFormSchema())->columns(),
                ])
                ->afterValidation(function () {
                    //
                }),
            Step::make('Treatment')
                ->schema([
                    Section::make()->schema([
                        OrderResource::getItemsRepeater(),
                    ])->columns(1),
                ])
                ->afterValidation(function () {
                    //
                }),
            Step::make('Ringkasan')
                ->schema([
                    Section::make()->schema([
                        Fieldset::make('ringkasan.customer')
                            ->label('Customer')
                            ->schema([
                                Placeholder::make('ringkasan.customer.name')
                                    ->label('Nama')
                                    ->content(fn (Get $get) => $get('customer_name')),
                                Placeholder::make('ringkasan.customer.alamat')
                                    ->label('Alamat')
                                    ->content(fn (Get $get) => $get('full_address')),
                            ])
                            ->reactive(),
                        Fieldset::make('ringkasan.screening')
                            ->label('Skrining')
                            ->schema([
                                Placeholder::make('ringkasan.screening.keluhan')
                                    ->label('Keluhan')
                                    ->content(fn (Get $get) => collect($get('screening'))->map(fn ($screening) => $screening['keluhan'])->implode(', ')),
                                Placeholder::make('ringkasan.screening.penyakit_menular')
                                    ->label('Penyakit Menular')
                                    ->content(fn (Get $get) => collect($get('screening'))->map(fn ($screening) => (bool) $screening['penyakit_menular'] ? 'Ya' : 'Tidak')->implode(', ')),
                                Placeholder::make('ringkasan.screening.riwayat_imunisasi')
                                    ->label('Riwayat Imunisasi')
                                    ->content(fn (Get $get) => collect($get('screening'))->map(fn ($screening) => (bool) $screening['riwayat_imunisasi'] ? 'Ya' : 'Tidak')->implode(', ')),
                            ]),
                        Fieldset::make('ringkasan.bidan')
                            ->label('Bidan')
                            ->schema([
                                Placeholder::make('ringkasan.bidan.nama')
                                    ->label('Nama')
                                    ->content(fn (Get $get) => $get('midwife_name')),
                                Placeholder::make('ringkasan.bidan.date')
                                    ->label('Tanggal dan Waktu')
                                    ->content(fn (Get $get) => Carbon::parse($get('date'))->format('d/m/Y').', '.$get('start_time').' - '.$get('end_time').' WIB'),
                                Placeholder::make('ringkasan.bidan.tempat')
                                    ->label('Tempat')
                                    ->content(fn (Get $get) => $get('place_name').($get('place_type') === PlaceType::HOMECARE ? ', '.$get('full_address') : ', '.$get('room_name'))),
                            ])
                            ->reactive(),
                        Fieldset::make('ringkasan.treatments')
                            ->label('Treatment')
                            ->schema([
                                Placeholder::make('ringkasan.treatment.nama')
                                    ->label('Nama')
                                    ->content(fn (Get $get) => collect($get('treatments'))?->map(fn ($treatment) => isset($treatment['treatment_name']) ? $treatment['treatment_name'].' ('.(isset($treatment['family_name']) ? $treatment['family_name'] : '').(isset($treatment['family_dob']) ? ', '.Carbon::parse($treatment['family_dob'])->format('d/m/Y').', '.DateTime::calculateAge($treatment['family_dob']) : '').')' : '')?->implode(', '))
                                    ->hidden(fn (Get $get) => ! ($get('treatments'))),
                            ])
                            ->reactive(),
                    ])->columns(),
                ]),
        ];

    }
}
