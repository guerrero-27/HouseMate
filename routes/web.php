<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboard;
use App\Http\Controllers\Tenant\RoomController as TenantRoomController;

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

    });

// Tenant routes
Route::prefix('tenant')
    ->name('tenant.')
    ->middleware(['auth', 'role:tenant'])
    ->group(function () {
        Route::get('/dashboard', [TenantDashboard::class, 'index'])->name('dashboard');
        Route::get('/rooms', [TenantRoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/{room}', [TenantRoomController::class, 'show'])->name('rooms.show');
    });

require __DIR__.'/auth.php';
