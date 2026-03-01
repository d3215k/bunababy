<?php

use App\Http\Controllers\Midwife\DashboardController;
use App\Http\Controllers\Midwife\ShowOrderController;
use App\Http\Controllers\Midwife\TimetablesController;
use App\Http\Controllers\OrderInvoiceController;
use Illuminate\Support\Facades\Route;

Route::redirect('/laravel/login', '/login')->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/bidan', DashboardController::class)->name('midwife.dashboard');
    Route::get('/bidan/timetables', TimetablesController::class)->name('midwife.timetables');
    Route::get('/bidan/order/{order}', ShowOrderController::class)->name('midwife.order.show');
    Route::get('/invoice/{order}/print', OrderInvoiceController::class)->name('order.invoice.print');
});
