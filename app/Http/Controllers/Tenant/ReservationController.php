<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservationController extends Controller
{
    // Show all reservations of the logged-in tenant
    public function index(): View
    {
        $reservations = auth()->user()
            ->reservations()
            ->with('room')
            ->latest()
            ->paginate(10);

        return view('tenant.reservations.index', compact('reservations'));
    }

    // Show the reservation form for a specific room
    public function create(Room $room): View
    {
        // Prevent reserving an unavailable room
        abort_if($room->status !== 'available', 403, 'This room is not available for reservation.');

        // Prevent tenant from having multiple pending/approved reservations
        $existing = auth()->user()
            ->reservations()
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->exists();

        if ($existing) {
            return redirect()->route('tenant.reservations.index')
                ->with('error', 'You already have an active or pending reservation.');
        }

        return view('tenant.reservations.create', compact('room'));
    }

    // Submit the reservation
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        // Double-check the room is still available at submit time
        $room = Room::findOrFail($request->room_id);

        abort_if($room->status !== 'available', 403, 'This room is no longer available.');

        Reservation::create([
            'user_id'      => auth()->id(),
            'room_id'      => $request->room_id,
            'move_in_date' => $request->move_in_date,
            'notes'        => $request->notes,
            'status'       => 'pending',
        ]);

        return redirect()->route('tenant.reservations.index')
            ->with('success', 'Reservation submitted successfully. Please wait for admin approval.');
    }

    // View a single reservation
    public function show(Reservation $reservation): View
    {
        // Security: tenant can only view their own reservation
        abort_if($reservation->user_id !== auth()->id(), 403);

        $reservation->load('room');
        return view('tenant.reservations.show', compact('reservation'));
    }

    // Cancel a pending reservation
    public function cancel(Reservation $reservation): RedirectResponse
    {
        abort_if($reservation->user_id !== auth()->id(), 403);
        abort_if(!$reservation->isPending(), 403, 'Only pending reservations can be cancelled.');

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('tenant.reservations.index')
            ->with('success', 'Reservation cancelled.');
    }
}
