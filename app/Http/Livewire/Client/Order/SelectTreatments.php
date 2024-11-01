<?php

namespace App\Http\Livewire\Client\Order;

use Filament\Notifications\Notification;
use Livewire\Component;
use App\Models\Treatment;
use App\Models\Setting;
use App\Models\Place;
use App\Models\Room;
use App\Models\User;

class SelectTreatments extends Component
{
    public $showAddTreatmentModal = false;
    public $familyId;
    public $currentTreatment;

    public function mount()
    {
        session()->forget('order.treatments');
        session()->put('order.addMinutes', 0);

        $this->familyId = time();

        if (auth()->check()) {
            if (auth()->user()->families()->exists()) {
                session()->put('order.families', auth()->user()->families->toArray());
                session()->push('order.families', [
                    'id' => time(),
                    'name' => auth()->user()->name,
                    'type' => 'Diri Sendiri'
                ]);
            } else {
                session()->put('order.families', [
                    [
                        'id' => time(),
                        'name' => auth()->user()->name,
                        'type' => 'Diri Sendiri'
                    ]
                ]);
            }
        }
    }

    protected $listeners = [
        'familySelected',
        'timeChanged' => '$refresh',
        'treatmentDeleted' => '$refresh',
    ];

    public function familySelected($familyId)
    {
        $this->showAddTreatmentModal = false;
        $this->familyId = $familyId;
        $this->addTreatment();
    }

    public function confirmAddTreatment(Treatment $treatment)
    {
        $treatment->load([
            'prices' => function ($query) {
                $query->where('place_id', session('order.place_id'));
            }
        ]);
        $this->currentTreatment = $treatment->toArray();
        $this->showAddTreatmentModal = true;
    }

    public function addTreatment()
    {
        try {
            if (session()->missing('order.addMinutes')) {
                session()->put('order.addMinutes', 0);
            }

            session()->increment('order.addMinutes', $this->currentTreatment['duration']);

            $family = collect(session('order.families'))->where('id', $this->familyId)->first();

            session()->push('order.treatments', [
                'treatment_id' => $this->currentTreatment['id'],
                'treatment_name' => $this->currentTreatment['name'],
                'treatment_desc' => $this->currentTreatment['desc'],
                'treatment_price' => $this->currentTreatment['prices'][0]['amount'],
                'treatment_duration' => $this->currentTreatment['duration'],
                'family_id' => $this->familyId,
                'family_name' => $family['name'],
            ]);

            $this->emit('treatmentAdded');

        } catch (\Throwable $th) {
            report($th->getMessage());
            Notification::make()->title(Setting::ERROR_MESSAGE)->danger()->send();
        }
    }

    public function deleteTreatment($index, $treatmentId)
    {
        try {
            $treatment = Treatment::find($treatmentId);

            session()->forget('order.treatments.' . $index);
            session()->decrement('order.addMinutes', $treatment->duration);

            $this->emit('treatmentDeleted');

        } catch (\Throwable $th) {
            report($th->getMessage());
            Notification::make()->title(Setting::ERROR_MESSAGE)->danger()->send();
        }
    }

    public function addFamily()
    {
        try {

            session()->push('order.families', [
                'id' => $this->familyId,
                'name' => 'pulan',
                'type' => 'buna',
            ]);

        } catch (\Throwable $th) {
            report($th->getMessage());
            Notification::make()->title(Setting::ERROR_MESSAGE)->danger()->send();
        }
    }

    public function render()
    {

        $availableTreatments = collect();

        if (session('order.place_type') === Place::TYPE_HOMECARE) {
            $midwife = User::find(session('order.midwife_user_id'));
            throw_if(is_null($midwife), \Exception::class, 'Midwife not found');

            $availableTreatments = $midwife->treatments()->whereActive(true)
                ->orderBy('order', 'ASC')
                ->with(['category' => function ($query) {
                    $query->orderBy('order', 'ASC');
                }, 'prices' => function ($query) {
                    $query->where('place_id', session('order.place_id'));
                }])
                ->get()
                ->groupBy('category.name');
        }

        if (session('order.place_type') === Place::TYPE_CLINIC) {
            $availableTreatments = [];

            $room = Room::find(session('order.room_id'));
            throw_if(is_null($room), \Exception::class, 'room not found');

            $availableTreatments = $room->treatments()->whereActive(true)
                ->orderBy('order', 'ASC')
                ->with(['category' => function ($query) {
                    $query->orderBy('order', 'ASC');
                }, 'prices' => function ($query) {
                    $query->where('place_id', session('order.place_id'));
                }])
                ->get()
                ->groupBy('category.name');
        }

        $categories = [];

        if (isset($availableTreatments)) {
            $categories = $availableTreatments->map(function ($item, $key) {
                return [
                    'name' => $key,
                    'treatments' => $item->toArray(),
                ];
            });
        }

        return view('client.order.select-treatments', [
            'categories' => $categories,
        ]);
    }
}
