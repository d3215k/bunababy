<?php

namespace App\Http\Livewire\Admin\Calendar;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Place;
use App\Models\Room;
use App\Models\User;

class ShowCalendar extends Component
{
    public $date;
    public $titles;
    public $colStart;
    public $rowStart;
    public $times;
    public $selectedDay;

    public function mount()
    {
        $this->selectedDay = today()->toDateString();

        $this->times = $this->getTimes();

        $midwives = User::active()->midwives()
            ->select(['id', 'name'])
            ->orderBy('id', 'ASC')
            ->get();

        $rooms = Room::active()->orderBy('id', 'ASC')
            ->with('place:id,name')
            ->get();

        $this->titles = collect();

        $this->colStart = collect([
            'midwives' => collect([]),
            'clinics' => collect([]),
        ]);

        $i = 2 ;
        foreach ($midwives as $midwife) {
            $this->colStart['midwives']->put($midwife->id, $i);
            $this->titles->push(['col-start' => $i, 'name' => $midwife->name]);
            $i++;
        }

        $i = 2 + $midwives->count();
        foreach ($rooms as $room) {
            $this->colStart['clinics']->put($room->id, $i);
            $this->titles->push(['col-start' => $i, 'name' => $room->name . ' - ' . $room->place->name]);
            $i++;
        }

        $this->colStart->toArray();

        $this->rowStart = $this->getRowStart();
    }

    private function getRowStart()
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

    private function getTimes()
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

    public function prevDay()
    {
        $this->selectedDay = Carbon::parse($this->selectedDay)->subDay()->toDateString();
    }

    public function nextDay()
    {
        $this->selectedDay = Carbon::parse($this->selectedDay)->addDay()->toDateString();
    }

    public function render()
    {
        $schedules = collect();

        $orders = Order::query()
            ->whereDate('date', $this->selectedDay)
            ->with(
                'client:id,name',
                'treatments:id,name',
                'address.kecamatan:id,name',
                'place:id,name,type',
                'room:id,name',
            )
            ->select([
                'id',
                'client_user_id',
                'midwife_user_id',
                'date',
                'start_time',
                'end_time',
                'status',
                'finished_at',
                'address_id',
                'place_id',
                'room_id',
            ])
            ->get();

        $bg = [
            Order::STATUS_UNPAID => 'bg-red-400/20 border border-red-700/10',
            Order::STATUS_LOCKED => 'bg-green-400/20 border border-green-700/10',
            Order::STATUS_FINISHED => 'bg-blue-400/20 border border-blue-700/10',
        ];

        foreach ($orders as $order) {
            $colStart = ($order->place->type === Place::TYPE_CLINIC && $order->midwife_user_id === null)
                    ? $this->colStart['clinics'][$order->room_id]
                    : $this->colStart['midwives'][$order->midwife_user_id];

            $rowStart = $this->rowStart[$order->startDateTime->isoFormat('HH:mm')];

            $rowSpan = (int) round($order->startDateTime
                    ->diffInMinutes(
                        $order->endDateTime->subMinutes($order->place->transport_duration)
                    ) / 15);

            $schedules->push([
                'classes' => "{$bg[$order->status]} col-start-{$colStart} row-start-{$rowStart} row-span-{$rowSpan} ",
                'id' => $order->id,
                'client_name' => $order->client->name,
                'time' => $order->getLongTime(),
                'treatments' => $order->treatments->implode('name', ', '),
                'status' => $order->getStatusString(),
                'finished_at' => $order->finished_at,
                'address' => $order->address->kecamatan->name ?? '-',
                'place' => $order->place->name,
                'room' => $order->room->name ?? '',
            ]);
        }

        return view('admin.calendar.show-calendar', [
            'schedules' => $schedules,
        ]);
    }
}
