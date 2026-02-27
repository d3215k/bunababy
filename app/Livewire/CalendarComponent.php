<?php

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Enums\PlaceType;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class CalendarComponent extends Component
{
    public $date;

    public $titles;

    public $colStart;

    public $rowStart;

    public $times;

    #[Url]
    public $selectedDay;

    #[Url]
    public $type = 'midwife';

    public $rooms;

    public function mount(?string $type = null): void
    {
        if (! $this->selectedDay) {
            $this->selectedDay = today()->toDateString();
        }

        if (in_array($type, ['midwife', 'clinic'], true)) {
            $this->type = $type;
        }

        $this->times = $this->getTimes();
        $this->rowStart = $this->getRowStart();
        $this->titles = collect();
        $this->colStart = collect();

        if ($this->isClinic()) {
            $this->bootClinicCalendar();

            return;
        }

        $this->bootMidwifeCalendar();
    }

    public function updatedType(string $type): void
    {
        if (! in_array($type, ['midwife', 'clinic'], true)) {
            return;
        }

        $this->type = $type;
        $this->titles = collect();
        $this->colStart = collect();
        $this->rooms = null;

        if ($this->isClinic()) {
            $this->bootClinicCalendar();

            return;
        }

        $this->bootMidwifeCalendar();
    }

    private function bootMidwifeCalendar(): void
    {
        $midwives = Midwife::select(['id', 'name'])
            ->orderBy('id', 'ASC')
            ->get();

        $this->colStart = collect([
            'midwives' => collect([]),
        ]);

        $i = 2;
        foreach ($midwives as $midwife) {
            $this->colStart['midwives']->put($midwife->id, $i);
            $this->titles->push(['col-start' => $i, 'name' => $midwife->name]);
            $i++;
        }
    }

    private function bootClinicCalendar(): void
    {
        $rooms = Room::select(['id', 'name', 'place_id'])
            ->with('place:id,name')
            ->get();

        $this->rooms = $rooms;

        $this->colStart = collect([
            'rooms' => collect([]),
        ]);

        $i = 2;
        foreach ($rooms as $room) {
            $this->colStart['rooms']->put($room->id, $i);
            $this->titles->push([
                'col-start' => $i,
                'name' => $room->name.' ('.$room->place?->name.')',
            ]);
            $i++;
        }
    }

    private function getRowStart(): array
    {
        return collect([
            '08:00' => '2',
            '08:15' => '3',
            '08:30' => '4',
            '08:45' => '5',
            '09:00' => '6',
            '09:15' => '7',
            '09:30' => '8',
            '09:45' => '9',
            '10:00' => '10',
            '10:15' => '11',
            '10:30' => '12',
            '10:45' => '13',
            '11:00' => '14',
            '11:15' => '15',
            '11:30' => '16',
            '11:45' => '17',
            '12:00' => '18',
            '12:15' => '19',
            '12:30' => '20',
            '12:45' => '21',
            '13:00' => '22',
            '13:15' => '23',
            '13:30' => '24',
            '13:45' => '25',
            '14:00' => '26',
            '14:15' => '27',
            '14:30' => '28',
            '14:45' => '29',
            '15:00' => '30',
            '15:15' => '31',
            '15:30' => '32',
            '15:45' => '33',
            '16:00' => '34',
            '16:15' => '35',
            '16:30' => '36',
            '16:45' => '37',
            '17:00' => '38',
            '17:15' => '39',
            '17:30' => '40',
            '17:45' => '41',
            '18:00' => '42',
            '18:15' => '43',
            '18:30' => '44',
            '18:45' => '45',
            '19:00' => '46',
            '19:15' => '47',
            '19:30' => '48',
            '19:45' => '49',
            '20:00' => '50',
            '20:15' => '51',
            '20:30' => '52',
            '20:45' => '53',
            '21:00' => '54',
            '21:15' => '55',
            '21:30' => '56',
            '21:45' => '57',
            '22:00' => '58',
            '22:15' => '59',
            '22:30' => '60',
            '22:45' => '61',
            '23:00' => '62',
        ])->toArray();
    }

    private function getTimes(): array
    {
        return collect([
            ['time' => '08:00', 'row-start' => '2'],
            ['time' => '', 'row-start' => '3'],
            ['time' => '08:30', 'row-start' => '4'],
            ['time' => '', 'row-start' => '5'],
            ['time' => '09:00', 'row-start' => '6'],
            ['time' => '', 'row-start' => '7'],
            ['time' => '09:30', 'row-start' => '8'],
            ['time' => '', 'row-start' => '9'],
            ['time' => '10:00', 'row-start' => '10'],
            ['time' => '', 'row-start' => '11'],
            ['time' => '10:30', 'row-start' => '12'],
            ['time' => '', 'row-start' => '13'],
            ['time' => '11:00', 'row-start' => '14'],
            ['time' => '', 'row-start' => '15'],
            ['time' => '11:30', 'row-start' => '16'],
            ['time' => '', 'row-start' => '17'],
            ['time' => '12:00', 'row-start' => '18'],
            ['time' => '', 'row-start' => '19'],
            ['time' => '12:30', 'row-start' => '20'],
            ['time' => '', 'row-start' => '21'],
            ['time' => '13:00', 'row-start' => '22'],
            ['time' => '', 'row-start' => '23'],
            ['time' => '13:30', 'row-start' => '24'],
            ['time' => '', 'row-start' => '25'],
            ['time' => '14:00', 'row-start' => '26'],
            ['time' => '', 'row-start' => '27'],
            ['time' => '14:30', 'row-start' => '28'],
            ['time' => '', 'row-start' => '29'],
            ['time' => '15:00', 'row-start' => '30'],
            ['time' => '', 'row-start' => '31'],
            ['time' => '15:30', 'row-start' => '32'],
            ['time' => '', 'row-start' => '33'],
            ['time' => '16:00', 'row-start' => '34'],
            ['time' => '', 'row-start' => '35'],
            ['time' => '16:30', 'row-start' => '36'],
            ['time' => '', 'row-start' => '37'],
            ['time' => '17:00', 'row-start' => '38'],
            ['time' => '', 'row-start' => '39'],
            ['time' => '17:30', 'row-start' => '40'],
            ['time' => '', 'row-start' => '41'],
            ['time' => '18:00', 'row-start' => '42'],
            ['time' => '', 'row-start' => '43'],
            ['time' => '18:30', 'row-start' => '44'],
            ['time' => '', 'row-start' => '45'],
            ['time' => '19:00', 'row-start' => '46'],
            ['time' => '', 'row-start' => '47'],
            ['time' => '19:30', 'row-start' => '48'],
            ['time' => '', 'row-start' => '49'],
            ['time' => '20:00', 'row-start' => '50'],
            ['time' => '', 'row-start' => '51'],
            ['time' => '20:30', 'row-start' => '52'],
            ['time' => '', 'row-start' => '53'],
            ['time' => '21:00', 'row-start' => '54'],
            ['time' => '', 'row-start' => '55'],
            ['time' => '21:30', 'row-start' => '56'],
            ['time' => '', 'row-start' => '57'],
            ['time' => '22:00', 'row-start' => '58'],
            ['time' => '', 'row-start' => '59'],
            ['time' => '22:30', 'row-start' => '60'],
            ['time' => '', 'row-start' => '61'],
            ['time' => '23:00', 'row-start' => '62'],
        ])->toArray();
    }

    public function prevDay(): void
    {
        $this->selectedDay = Carbon::parse($this->selectedDay)->subDay()->toDateString();
    }

    public function nextDay(): void
    {
        $this->selectedDay = Carbon::parse($this->selectedDay)->addDay()->toDateString();
    }

    private function isClinic(): bool
    {
        return $this->type === 'clinic';
    }

    public function render(): View
    {
        $schedules = collect();

        $ordersQuery = Order::query()
            ->whereDate('date', $this->selectedDay)
            ->with(
                'customer:id,name',
                'address.kecamatan:id,name',
                'place:id,name,type,transport_duration',
                'room:id,name',
            )
            ->select([
                'id',
                'customer_id',
                'midwife_id',
                'date',
                'start_time',
                'end_time',
                'status',
                'finished_at',
                'address_id',
                'place_id',
                'room_id',
                'treatments',
            ]);

        if ($this->isClinic()) {
            $ordersQuery->whereIn('room_id', $this->rooms->pluck('id'))
                ->with('midwife:id,name');
        }

        $orders = $ordersQuery->get();

        $bg = [
            OrderStatus::CANCELLED->value => 'background-color: rgba(248, 113, 113, 0.5);',
            OrderStatus::PENDING->value => 'background-color: rgba(248, 113, 113, 0.5);',
            OrderStatus::BOOKED->value => 'background-color: rgba(74, 222, 128, 0.5);',
            OrderStatus::ON_HOLD->value => 'background-color: rgba(250, 204, 21, 0.5);',
            OrderStatus::IN_SERVICE->value => 'background-color: rgba(96, 165, 250, 0.5);',
            OrderStatus::FINISHED->value => 'background-color: rgba(244, 114, 182, 0.5);',
            OrderStatus::COMPLETED->value => 'background-color: rgba(96, 165, 250, 0.5);',
        ];

        foreach ($orders as $order) {
            $columnGroup = $this->isClinic() ? 'rooms' : 'midwives';
            $columnKey = $this->isClinic() ? $order->room_id : $order->midwife_id;
            $colStart = $this->colStart[$columnGroup][$columnKey];

            $rowStart = $this->rowStart[$order->startDateTime->format('H:i')];

            $rowSpan = (int) round($order->startDateTime
                ->diffInMinutes(
                    $order->endDateTime->subMinutes($order->place->transport_duration)
                ) / 15);

            $schedule = [
                'classes' => "{$bg[$order->status->value]} grid-column-start: {$colStart}; grid-row: {$rowStart} / span {$rowSpan};",
                'id' => $order->id,
                'customer_name' => $order->customer->name,
                'time' => $order->getLongTime(),
                'treatments' => $order->listTreatments,
                'status' => $order->status->getLabel(),
                'finished_at' => $order->finished_at,
                'place' => $order->place->type === PlaceType::HOMECARE
                    ? ($order->place->name.', '.$order->address->kecamatan->name ?? '-')
                    : ($order->place->name.', '.$order->room->name ?? '-'),
            ];

            if ($this->isClinic()) {
                $schedule['midwife_name'] = $order->midwife->name;
            }

            $schedules->push($schedule);
        }

        return view('livewire.calendar-component', [
            'schedules' => $schedules,
            'headerTitle' => $this->isClinic() ? 'Klinik' : 'Bidan',
            'titleTextClass' => $this->isClinic() ? 'text-xs' : 'text-sm',
            'titleClamp' => $this->isClinic(),
            'scheduleFooterKey' => $this->isClinic() ? 'midwife_name' : 'place',
        ]);
    }
}
