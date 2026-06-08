@extends('layouts.admin')

@section('title', 'Manage Rooms')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manage Rooms</h1>
    <a href="{{ route('admin.rooms.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm">
        + Add Room
    </a>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

{{-- Rooms Table --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
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
        <tbody class="divide-y divide-gray-100">
            @forelse($rooms as $room)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    @if($room->thumbnail)
                        <img src="{{ asset('storage/' . $room->thumbnail) }}"
                             class="w-14 h-10 object-cover rounded" alt="Room">
                    @else
                        <div class="w-14 h-10 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                            No Photo
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 font-semibold">{{ $room->room_number }}</td>
                <td class="px-4 py-3">{{ $room->room_type }}</td>
                <td class="px-4 py-3">₱{{ number_format($room->monthly_rate, 2) }}</td>
                <td class="px-4 py-3">{{ $room->capacity }} person(s)</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $room->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $room->status === 'occupied' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $room->status === 'maintenance' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                        {{ ucfirst($room->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.rooms.edit', $room) }}"
                       class="text-blue-600 hover:underline text-xs">Edit</a>

                    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                          onsubmit="return confirm('Delete this room?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-xs">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                    No rooms found. <a href="{{ route('admin.rooms.create') }}" class="text-red-600">Add one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="px-4 py-3 border-t">
        {{ $rooms->links() }}
    </div>
</div>

@endsection
