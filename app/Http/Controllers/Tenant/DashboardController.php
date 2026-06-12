<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user        = auth()->user();
        $reservation = $user->activeReservation();

        $unpaidCount = $user->payments()
            ->whereIn('status', ['unpaid', 'overdue'])
            ->count();

        $nextDue = $user->payments()
            ->whereIn('status', ['unpaid', 'overdue'])
            ->orderBy('due_date')
            ->first();

        return view('tenant.dashboard', compact('reservation', 'unpaidCount', 'nextDue'));
    }
}
