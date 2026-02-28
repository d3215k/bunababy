<?php

namespace App\Http\Controllers\Midwife;

use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (auth()->user()->type !== UserType::MIDWIFE) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        $orders = Order::query()
            ->where('date', today())
            ->where('midwife_id', auth()->user()->midwife_id)
            ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::PENDING])
            ->orderBy('start_time', 'ASC')
            ->get();

        return view('midwife.dashboard', [
            'orders' => $orders,
        ]);
    }
}
