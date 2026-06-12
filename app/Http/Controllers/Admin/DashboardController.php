<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalRooms'           => Room::count(),
            'availableRooms'       => Room::where('status', 'available')->count(),
            'occupiedRooms'        => Room::where('status', 'occupied')->count(),
            'pendingReservations'  => Reservation::where('status', 'pending')->count(),
            'totalTenants'         => User::where('role', 'tenant')->count(),
            'pendingPayments'      => Payment::where('status', 'pending_verification')->count(),
            'revenueThisMonth'     => Payment::where('status', 'paid')
                                        ->where('billing_month', now()->format('Y-m'))
                                        ->sum('amount'),
            'recentReservations'   => Reservation::with(['user', 'room'])
                                        ->latest()->take(5)->get(),
        ]);
    }
}
