@extends('layouts.admin')

@section('title', 'Manage Rooms')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manage Rooms</h1>
    <a href="{{ route('admin.rooms.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
        + Add Room
    </a>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

{{-- Rooms Table --}}
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Photo</th>
                <th class="px-4 py-3">Room No.</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Rate/Month</th>
                <th class="px-4 py-3">Capacity</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($rooms as $room)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                <td class="px-4 py-3">
                    @if($room->thumbnail)
                        <img src="{{ asset('storage/' . $room->thumbnail) }}"
                             class="w-14 h-10 object-cover rounded" alt="Room">
                    @else
                        <div class="w-14 h-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-gray-400 dark:text-gray-500 text-xs">
                            No Photo
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $room->room_number }}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $room->room_type }}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">₱{{ number_format($room->monthly_rate, 2) }}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $room->capacity }} person(s)</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $room->status === 'available' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : '' }}
                        {{ $room->status === 'occupied' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' : '' }}
                        {{ $room->status === 'maintenance' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400' : '' }}">
                        {{ ucfirst($room->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.rooms.edit', $room) }}"
                       class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Edit</a>

                    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                          onsubmit="return confirm('Delete this room?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-400 dark:text-gray-500">
                    No rooms found. <a href="{{ route('admin.rooms.create') }}" class="text-indigo-600 dark:text-indigo-400">Add one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
        {{ $rooms->links() }}
    </div>
</div>

@endsection
