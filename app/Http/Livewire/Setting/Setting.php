<?php

namespace App\Http\Livewire\Setting;

use App\Models\Option;
use Livewire\Component;

class Setting extends Component
{
    public $state = [];

    public $rules = [
        'state.timeout' => 'required',
    ];

    public function mount()
    {
        $this->state = Option::first()->toArray();
    }

    public function save()
    {
        $this->validate();

        Option::where('id', 1)->update([
            'timeout' => $this->state['timeout'],
        ]);

        $this->emit('saved');
    }

    public function render()
    {
        return view('setting.setting');
    }
}