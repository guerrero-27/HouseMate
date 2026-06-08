@extends('layouts.tenant')

@section('title', 'Room ' . $room->room_number)

@section('content')

<div class="max-w-3xl mx-auto">
    <a href="{{ route('tenant.rooms.index') }}" class="text-sm text-gray-500 hover:underline mb-4 block">← Back to Rooms</a>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        {{-- Main Image --}}
        @if($room->thumbnail)
            <img src="{{ asset('storage/' . $room->thumbnail) }}"
                 class="w-full h-64 object-cover" alt="Room {{ $room->room_number }}">
        @endif

        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Room {{ $room->room_number }}</h1>
                <span class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">
                    {{ ucfirst($room->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                <div><span class="font-semibold">Type:</span> {{ $room->room_type }}</div>
                <div><span class="font-semibold">Rate:</span> ₱{{ number_format($room->monthly_rate, 2) }}/month</div>
                <div><span class="font-semibold">Capacity:</span> {{ $room->capacity }} person(s)</div>
                <div><span class="font-semibold">Floor:</span> {{ $room->floor_number ?? '—' }}</div>
            </div>

            @if($room->description)
            <p class="text-sm text-gray-600 mb-4">{{ $room->description }}</p>
            @endif

            {{-- Gallery --}}
            @if($room->images->isNotEmpty())
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Gallery</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($room->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             class="h-24 w-28 object-cover rounded cursor-pointer hover:opacity-90"
                             alt="Room Image">
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Reserve Button -- will be linked in Phase 4 --}}
            @if($room->status === 'available')
            <div class="mt-6">
                <a href="#" class="block text-center bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700">
                    Reserve This Room
                </a>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection
