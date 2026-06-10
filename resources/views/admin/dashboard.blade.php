@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome, {{ auth()->user()->name }} 👋</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-600">
        <p class="text-xs text-gray-500 uppercase">Total Rooms</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalRooms }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase">Available</p>
        <p class="text-3xl font-bold text-gray-800">{{ $availableRooms }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-gray-500">
        <p class="text-xs text-gray-500 uppercase">Occupied</p>
        <p class="text-3xl font-bold text-gray-800">{{ $occupiedRooms }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
        <p class="text-xs text-gray-500 uppercase">Pending</p>
        <p class="text-3xl font-bold text-gray-800">{{ $pendingReservations }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase">Total Tenants</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalTenants }}</p>
    </div>
</div>

{{-- Recent Reservations --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-5 py-4 border-b">
        <h2 class="font-semibold text-gray-700">Recent Reservations</h2>
    </div>
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Tenant</th>
                <th class="px-4 py-3 text-left">Room</th>
                <th class="px-4 py-3 text-left">Move-in</th>
                <th class="px-4 py-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($recentReservations as $r)
            <tr>
                <td class="px-4 py-3">{{ $r->user->name }}</td>
                <td class="px-4 py-3">Room {{ $r->room->room_number }}</td>
                <td class="px-4 py-3">{{ $r->move_in_date->format('M d, Y') }}</td>
                <td class="px-4 py-3">
                    @php $c = $r->statusColor(); @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-700">
                        {{ $r->statusLabel() }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">No reservations yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
