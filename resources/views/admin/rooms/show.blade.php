@extends('layouts.admin')

@section('title', 'Room Details')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Room {{ $room->room_number }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.rooms.edit', $room) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50">
                Edit
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">← Back</a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">

        {{-- Thumbnail --}}
        @if($room->thumbnail)
            <img src="{{ asset('storage/' . $room->thumbnail) }}"
                 class="w-full h-56 object-cover" alt="Room thumbnail">
        @else
            <div class="w-full h-56 bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-500 text-sm">
                No Photo
            </div>
        @endif

        <div class="p-6">
            {{-- Details --}}
            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Room Type</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $room->room_type }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Monthly Rate</p>
                    <p class="font-medium text-gray-900 dark:text-white">₱{{ number_format($room->monthly_rate, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Capacity</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $room->capacity }} person(s)</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Floor</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $room->floor_number ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Status</p>
                    @php $c = $room->statusColor(); @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/40 dark:text-{{ $c }}-400">
                        {{ ucfirst($room->status) }}
                    </span>
                </div>
            </div>

            @if($room->description)
            <div class="mb-6">
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Description</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $room->description }}</p>
            </div>
            @endif

            {{-- Gallery --}}
            @if($room->images->isNotEmpty())
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Gallery</p>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                    @foreach($room->images as $image)
                    <img src="{{ asset('storage/' . $image->image_path) }}"
                         class="h-20 w-full object-cover rounded border border-gray-200 dark:border-gray-700" alt="Gallery">
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
