<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use App\Enums\PlaceType;
use App\Filament\Resources\OrderResource;
use App\Models\Address;
use App\Models\Order;
use App\Models\Place;
use App\Support\FormatNumber;
use Filament\Actions;
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
        return $this->getRecord()->id . ' - ' . $this->getRecord()->customer->name;
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
                ->url('https://wa.me/' . FormatNumber::toWaIndo($this->getRecord()->customer->phone) . '?text=Halo+' . urlencode($this->getRecord()->customer->name))
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
        $place =  Place::find($data['place_id']);
        $data['place_type'] = $place->type;
        $data['place_transport_duration'] = $place->transport_duration;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $address = Address::find($data['address_id']);

        $place = Place::find($data['place_id']);

        if ($place->type === PlaceType::HOMECARE) {
            if ($record->place_id !== $data['place_id']) {
                $data['transport'] = Order::getCalculatedTransport($address->kecamatan->distance);
            }
            $data['room_id'] = null;
        }

        $data['price'] = collect($data['treatments'])->sum('treatment_price');

        $data['last_updated_by'] = auth()->id();
        $data['end_time'] = Order::getCalculatedEndTime($data['date'], $data['start_time'], $data['treatments'], $place->transport_duration);

        $isAvailable = Order::isAvailable($data, $place->type, $record->id);

        if (!$isAvailable) {
            Notification::make()
                ->warning()
                ->title('Jadwal tidak tersedia!')
                ->body('Silahkan pilih jadwal lain')
                ->send();

            $this->halt();

        }


        // dd($data);

        $record->update($data);

        return $record;
    }

}
