<?php

namespace App\Http\Livewire\Order;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SelectLocation extends Component
{
    public $search = '';
    public $kecamatan;

    public function mount() {

        $kecamatan_id = '';

        if(Auth::check() && Auth::user()->role == 'client'){
            $kecamatan_id = Auth::user()->addresses->where('is_main', true)->first()->kecamatan_id;
            session()->put('order.kecamatan_id', $kecamatan_id);
        }

        $this->kecamatan = DB::table('kecamatans')->where('id', session('order.kecamatan_id') ?? $kecamatan_id )->value('name') ?? 'Pilih salah satu';
    }

    public function setLocation($kecamatan_id) {
        $kecamatan = DB::table('kecamatans')->where('id', $kecamatan_id )->first();
        $this->kecamatan = $kecamatan->name;

        session()->put('order.kecamatan_id', $kecamatan_id);
        session()->put('order.kecamatan_distance', $kecamatan->distance);

        return redirect()->route('order.step-1');
    }

    public function render()
    {
        $kabupatens = Kabupaten::query()
                ->where(function ($query) {
                    $query->whereHas('Kecamatans', function ($query) {
                        $query->where('name', 'LIKE', $this->search . '%');
                    });
                })
                ->with('kecamatans', function ($query) {
                    $query->where('name', 'LIKE', $this->search . '%');
                })
                ->get();

        return view('client.order.select-location', [
            'kabupatens' => $kabupatens
        ]);
    }
}