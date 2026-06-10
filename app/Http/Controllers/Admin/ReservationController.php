<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReservationStatusRequest;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservationController extends Controller
{
    // List all reservations with filters
    public function index(): View
    {
        $status = request('status', 'pending'); // default filter: pending

        $reservations = Reservation::with(['user', 'room'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('admin.reservations.index', compact('reservations', 'status'));
    }

    // View a single reservation detail
    public function show(Reservation $reservation): View
    {
        $reservation->load(['user', 'room']);
        return view('admin.reservations.show', compact('reservation'));
    }

    // Approve or Reject a reservation
    public function updateStatus(UpdateReservationStatusRequest $request, Reservation $reservation): RedirectResponse
    {
        abort_if(!$reservation->isPending(), 403, 'Only pending reservations can be updated.');

        if ($request->status === 'approved') {
            $reservation->update([
                'status'      => 'approved',
                'approved_at' => now(),
            ]);

            // Mark the room as occupied when approved
            $reservation->room->update(['status' => 'occupied']);

        } elseif ($request->status === 'rejected') {
            $reservation->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_at'      => now(),
            ]);

            // Room stays available when rejected
        } elseif ($request->status === 'cancelled') {
            $reservation->update(['status' => 'cancelled']);

            // Free up the room if it was approved
            if ($reservation->room->status === 'occupied') {
                $reservation->room->update(['status' => 'available']);
            }
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation status updated.');
    }
}
