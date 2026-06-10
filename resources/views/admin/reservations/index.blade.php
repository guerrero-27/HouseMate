@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Reservations</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Status Filter Tabs --}}
<div class="flex gap-2 mb-5 flex-wrap">
    @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $value => $label)
    <a href="{{ route('admin.reservations.index', ['status' => $value]) }}"
       class="px-4 py-1.5 rounded-full text-sm font-medium border
              {{ $status === $value
                ? 'bg-red-600 text-white border-red-600'
                : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Tenant</th>
                <th class="px-4 py-3">Room</th>
                <th class="px-4 py-3">Move-in Date</th>
                <th class="px-4 py-3">Submitted</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reservations as $reservation)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <span class="font-semibold">{{ $reservation->user->name }}</span>
                    <span class="text-gray-400 block text-xs">{{ $reservation->user->email }}</span>
                </td>
                <td class="px-4 py-3">
                    Room {{ $reservation->room->room_number }}
                    <span class="text-gray-400 block text-xs">{{ $reservation->room->room_type }}</span>
                </td>
                <td class="px-4 py-3">{{ $reservation->move_in_date->format('M d, Y') }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $reservation->created_at->diffForHumans() }}</td>
                <td class="px-4 py-3">
                    @php $color = $reservation->statusColor(); @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        bg-{{ $color }}-100 text-{{ $color }}-700">
                        {{ $reservation->statusLabel() }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.reservations.show', $reservation) }}"
                       class="text-blue-600 hover:underline text-xs">Review</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">No reservations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">
        {{ $reservations->links() }}
    </div>
</div>

@endsection
