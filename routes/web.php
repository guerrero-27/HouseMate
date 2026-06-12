<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboard;
use App\Http\Controllers\Tenant\RoomController as TenantRoomController;
use App\Http\Controllers\Tenant\ReservationController as TenantReservationController;
use App\Http\Controllers\Tenant\PaymentController as TenantPaymentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('rooms', AdminRoomController::class);
        Route::delete('/rooms/images/{image}', [AdminRoomController::class, 'destroyImage'])
            ->name('rooms.images.destroy');

        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
        Route::patch('/reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.updateStatus');

        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [AdminPaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [AdminPaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::patch('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
        Route::get('/payments/reservations-by-tenant', [AdminPaymentController::class, 'getReservationsByTenant'])->name('payments.reservationsByTenant');
    });

// Tenant routes
Route::prefix('tenant')
    ->name('tenant.')
    ->middleware(['auth', 'role:tenant'])
    ->group(function () {
        Route::get('/dashboard', [TenantDashboard::class, 'index'])->name('dashboard');
        Route::get('/rooms', [TenantRoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/{room}', [TenantRoomController::class, 'show'])->name('rooms.show');

        Route::get('/reservations', [TenantReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create/{room}', [TenantReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [TenantReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations/{reservation}', [TenantReservationController::class, 'show'])->name('reservations.show');
        Route::patch('/reservations/{reservation}/cancel', [TenantReservationController::class, 'cancel'])->name('reservations.cancel');

        Route::get('/payments', [TenantPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [TenantPaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{payment}/upload-receipt', [TenantPaymentController::class, 'uploadReceipt'])->name('payments.uploadReceipt');
    });

require __DIR__.'/auth.php';
