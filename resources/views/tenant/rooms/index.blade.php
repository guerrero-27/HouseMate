@extends('layouts.tenant')

@section('title', 'Browse Rooms')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Available Rooms</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($rooms as $room)
    <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-md transition">

        {{-- Room Image --}}
        @if($room->thumbnail)
            <img src="{{ asset('storage/' . $room->thumbnail) }}"
                 class="w-full h-40 object-cover" alt="{{ $room->room_number }}">
        @else
            <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                No Photo
            </div>
        @endif

        <div class="p-4">
            <div class="flex justify-between items-start mb-1">
                <h2 class="font-bold text-gray-800">Room {{ $room->room_number }}</h2>
                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                    Available
                </span>
            </div>
            <p class="text-xs text-gray-500 mb-2">{{ $room->room_type }}</p>
            <p class="text-red-600 font-bold text-lg">₱{{ number_format($room->monthly_rate, 2) }}/mo</p>
            <p class="text-xs text-gray-400 mt-1">Capacity: {{ $room->capacity }} person(s)</p>

            <a href="{{ route('tenant.rooms.show', $room) }}"
               class="mt-3 block text-center bg-red-600 text-white py-2 rounded-lg text-sm hover:bg-red-700">
                View Details
            </a>
        </div>

    </div>
    @empty
    <div class="col-span-3 text-center text-gray-400 py-12">
        No available rooms at the moment.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $rooms->links() }}
</div>

@endsection
