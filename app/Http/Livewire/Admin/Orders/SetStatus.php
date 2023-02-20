<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;

class SetStatus extends Component
{
    public $order;

    public $finished_at;

    protected $rules = [
        'finished_at' => 'required'
    ];

    protected $validationAttributes = [
        'finished_at' => 'Waktu selesai'
    ];

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function save()
    {
        $this->validate();

        $this->order->update([
            'finished_at' => Carbon::createFromFormat('H:i', $this->finished_at),
            'status' => Order::STATUS_FINISHED,
        ]);

        $this->emit('saved');
    }

    public function render()
    {
        return view('admin.orders.set-status');
    }
}