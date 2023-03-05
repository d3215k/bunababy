<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create(): RedirectResponse|Redirector|View
    {
        if (auth()->check() && !auth()->user()->isClient()) {
            return redirect()->route('dashboard');
        }

        if (auth()->check() && is_null(auth()->user()->address)) {
            Notification::make()
                ->title('Tambah Alamat dulu ya')
                ->success()
                ->send();

            return to_route('home');
        }

        if (auth()->check() && (auth()->user()->latestReservation && !auth()->user()->latestReservation->isPaid())) {
            Notification::make()
                ->title('Ada Reservasi Aktif')
                ->success()
                ->send();

            return to_route('home');
        }

        return view('client.order.create');
    }

    public function show($no_reg)
    {
        $order = Order::query()
            ->with('treatments', 'place')
            ->where('no_reg', $no_reg)
            ->firstOrFail();

        if (!auth()->check() or $order->client_user_id !== auth()->id()) {
            return to_route('home');
        }

        return view('client.order.show', [
            'order' => $order,
            'options' => DB::table('options')->select('account', 'account_name', 'site_location', 'timeout')->first(),
            'hasPayments' => $order->payments()->exists(),
            'isDpPaid' => $order->dp(),
            'dpPaidAt' => $order->dp() ? $order->payments()->verified()->first()->created_at->isoFormat('DD MMM YYYY HH:mm') : '-',
            'isFinished' => $order->status === Order::STATUS_FINISHED,
            'finishedAt' => $order->status === Order::STATUS_FINISHED ? $order->finished_at->isoFormat('DD MMM YYYY HH:mm') : '-',
            'isPaid' => $order->isPaid(),
            'paidAt' => $order->isPaid() ? $order->payments()->verified()->latest()->first()->created_at->isoFormat('DD MMM YYYY HH:mm') : '-',
            'isReviewed' => $order->testimonial()->exists(),
            'reviewedAt' => $order->testimonial()->exists() ? $order->testimonial->created_at->isoFormat('DD MMM YYYY HH:mm') : '-',
        ]);
    }
}
