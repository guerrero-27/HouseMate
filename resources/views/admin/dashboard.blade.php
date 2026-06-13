@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Good day, {{ auth()->user()->name }}</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Here's what's happening in your property today.</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Total Rooms</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalRooms }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Available</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $availableRooms }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Occupied</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $occupiedRooms }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Total Tenants</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTenants }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Pending Reservations</p>
        <p class="text-3xl font-bold text-yellow-500 dark:text-yellow-400">{{ $pendingReservations }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">For Verification</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingPayments }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5 col-span-2">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Revenue This Month</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($revenueThisMonth, 2) }}</p>
    </div>
</div>

{{-- Recent Reservations --}}
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Recent Reservations</h3>
        <a href="{{ route('admin.reservations.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide border-b border-gray-200 dark:border-gray-700">
                    <th class="px-5 py-3 text-left font-medium">Tenant</th>
                    <th class="px-5 py-3 text-left font-medium">Room</th>
                    <th class="px-5 py-3 text-left font-medium hidden sm:table-cell">Move-in Date</th>
                    <th class="px-5 py-3 text-left font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($recentReservations as $r)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-5 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $r->user->name }}</td>
                    <td class="px-5 py-3 text-gray-600 dark:text-gray-400">Room {{ $r->room->room_number }}</td>
                    <td class="px-5 py-3 text-gray-600 dark:text-gray-400 hidden sm:table-cell">{{ $r->move_in_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        @php $c = $r->statusColor(); @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/40 dark:text-{{ $c }}-400">
                            {{ $r->statusLabel() }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-400 dark:text-gray-500">No reservations yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
