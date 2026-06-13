@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Reservations</h1>

@if(session('success'))
    <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Status Filter Tabs --}}
<div class="flex gap-2 mb-5 flex-wrap">
    @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $value => $label)
    <a href="{{ route('admin.reservations.index', ['status' => $value]) }}"
       class="px-4 py-1.5 rounded-full text-sm font-medium border transition-colors
              {{ $status === $value
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Tenant</th>
                    <th class="px-4 py-3">Room</th>
                    <th class="px-4 py-3 hidden sm:table-cell">Move-in Date</th>
                    <th class="px-4 py-3 hidden md:table-cell">Submitted</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($reservations as $reservation)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->user->name }}</span>
                        <span class="text-gray-400 dark:text-gray-500 block text-xs">{{ $reservation->user->email }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        Room {{ $reservation->room->room_number }}
                        <span class="text-gray-400 dark:text-gray-500 block text-xs">{{ $reservation->room->room_type }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell">{{ $reservation->move_in_date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $reservation->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-3">
                        @php $color = $reservation->statusColor(); @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            bg-{{ $color }}-100 text-{{ $color }}-700 dark:bg-{{ $color }}-900/40 dark:text-{{ $color }}-400">
                            {{ $reservation->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.reservations.show', $reservation) }}"
                           class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Review</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">No reservations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
        {{ $reservations->links() }}
    </div>
</div>

@endsection
