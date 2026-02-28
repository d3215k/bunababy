<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Family;
use App\Models\Place;
use App\Services\OrderService;
use App\Support\FormatNumber;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function getHeading(): string|Htmlable
    {
        return $this->getRecord()->id.' - '.$this->getRecord()->customer->name;
    }

    #[On('payment-updated')]
    public function justRefreshThePage()
    {
        //
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('chat')
                ->label('Chat WA')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->url('https://wa.me/'.FormatNumber::toWaIndo($this->getRecord()->customer->phone).'?text=Halo+'.urlencode($this->getRecord()->customer->name))
                ->openUrlInNewTab(),
            Action::make('invoice')
                ->label('Cetak Invoice')
                ->icon('heroicon-o-printer')
                ->url(route('order.invoice.print', $this->getRecord()))
                ->openUrlInNewTab(),
            Action::make('customer')
                ->url(route('filament.admin.resources.customers.edit', $this->getRecord()->customer))
                ->icon('heroicon-o-user'),
            ActionGroup::make([
                DeleteAction::make(),
            ]),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $place = Place::find($data['place_id']);
        $data['place_type'] = $place->type;
        $data['place_transport_duration'] = $place->transport_duration;

        // Convert treatments data to include family_id for repeater pre-population
        if (! empty($data['treatments']) && is_array($data['treatments'])) {
            $data['treatments'] = array_map(function ($treatment) use ($data) {
                // If family_id is not present, try to find it from family_name
                if (! isset($treatment['family_id']) && isset($treatment['family_name'])) {
                    $family = Family::where('customer_id', $data['customer_id'])
                        ->where('name', $treatment['family_name'])
                        ->first();
                    if ($family) {
                        $treatment['family_id'] = $family->id;
                    }
                }

                return $treatment;
            }, $data['treatments']);
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return (new OrderService)->update($record, $data);
        } catch (\Exception $e) {
            Notification::make()
                ->warning()
                ->title('Jadwal tidak tersedia!')
                ->body($e->getMessage())
                ->send();

            throw $e;
        }
    }
}
