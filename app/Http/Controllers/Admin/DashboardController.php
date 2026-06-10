<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalRooms'          => Room::count(),
            'availableRooms'      => Room::where('status', 'available')->count(),
            'occupiedRooms'       => Room::where('status', 'occupied')->count(),
            'pendingReservations' => Reservation::where('status', 'pending')->count(),
            'totalTenants'        => User::where('role', 'tenant')->count(),
            'recentReservations'  => Reservation::with(['user', 'room'])
                                        ->latest()->take(5)->get(),
        ]);
    }
}
