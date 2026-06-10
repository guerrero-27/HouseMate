@extends('layouts.tenant')

@section('title', 'My Reservations')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">My Reservations</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4 text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Room</th>
                <th class="px-4 py-3">Move-in Date</th>
                <th class="px-4 py-3">Submitted</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reservations as $reservation)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold">
                    Room {{ $reservation->room->room_number }}
                    <span class="text-gray-400 font-normal text-xs block">{{ $reservation->room->room_type }}</span>
                </td>
                <td class="px-4 py-3">{{ $reservation->move_in_date->format('M d, Y') }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $reservation->created_at->format('M d, Y') }}</td>
                <td class="px-4 py-3">
                    @php $color = $reservation->statusColor(); @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        bg-{{ $color }}-100 text-{{ $color }}-700">
                        {{ $reservation->statusLabel() }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2 items-center">
                    <a href="{{ route('tenant.reservations.show', $reservation) }}"
                       class="text-blue-600 hover:underline text-xs">View</a>

                    @if($reservation->isPending())
                    <form method="POST"
                          action="{{ route('tenant.reservations.cancel', $reservation) }}"
                          onsubmit="return confirm('Cancel this reservation?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-red-600 hover:underline text-xs">Cancel</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                    No reservations yet.
                    <a href="{{ route('tenant.rooms.index') }}" class="text-red-600 hover:underline">Browse rooms</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">
        {{ $reservations->links() }}
    </div>
</div>

@endsection
