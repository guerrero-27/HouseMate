<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboard;

// Public landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin routes — protected by auth + role:admin middleware
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    });

// Tenant routes — protected by auth + role:tenant middleware
Route::prefix('tenant')
    ->name('tenant.')
    ->middleware(['auth', 'role:tenant'])
    ->group(function () {
        Route::get('/dashboard', [TenantDashboard::class, 'index'])->name('dashboard');
    });

// Breeze auth routes (login, register, logout, etc.)
require __DIR__.'/auth.php';