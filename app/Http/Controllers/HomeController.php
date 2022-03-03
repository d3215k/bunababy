<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show()
    {
        if (auth()->user()->isAdmin() || auth()->user()->isMidwife())
        {
            return redirect('/dashboard');
        }

        $reservation = auth()->user()->latestReservation;


        return view('client.home', [
            'reservation' => $reservation,
        ]);
    }
}
