<?php

namespace App\Http\Livewire\Client\Order;

use App\Models\Family;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SelectFamily extends Component
{
    public $name;
    public $type = 'Anak';

    public $showAddNewFamily = false;

    protected $rules = [
        'name' => 'min:2|max:32'
    ];

    public function mount()
    {
        //
    }

    public function addFamily()
    {
        $this->validate();

        session()->push('order.families', [
            'id' => time(),
            'name' => ucwords($this->name),
            'birthdate' => '',
            'type' => !session('order.families') ? 'Diri Sendiri' : $this->type,
        ]);
        $this->name = '';
        $this->showAddNewFamily = false;
    }

    public function selectFamily($familyId)
    {
        $this->emit('familySelected', $familyId);
    }

    public function render()
    {
        $families = collect(session('order.families') ?? []);

        return view('client.order.select-family', [
            'families' => $families,
        ]);
    }
}
