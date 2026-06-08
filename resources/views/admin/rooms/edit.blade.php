@extends('layouts.admin')

@section('title', 'Edit Room')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Room — {{ $room->room_number }}</h1>
        <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-500 hover:underline">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room Number *</label>
                    <input type="text" name="room_number"
                           value="{{ old('room_number', $room->room_number) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm @error('room_number') border-red-500 @enderror">
                    @error('room_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room Type *</label>
                    <input type="text" name="room_type"
                           value="{{ old('room_type', $room->room_type) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Rate (₱) *</label>
                    <input type="number" name="monthly_rate"
                           value="{{ old('monthly_rate', $room->monthly_rate) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm" step="0.01">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity *</label>
                    <input type="number" name="capacity"
                           value="{{ old('capacity', $room->capacity) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm" min="1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Floor Number</label>
                    <input type="number" name="floor_number"
                           value="{{ old('floor_number', $room->floor_number) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm" min="1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(['available', 'occupied', 'maintenance'] as $s)
                            <option value="{{ $s }}" {{ old('status', $room->status) === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('description', $room->description) }}</textarea>
            </div>

            {{-- Current Thumbnail --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Photo</label>
                @if($room->thumbnail)
                    <img src="{{ asset('storage/' . $room->thumbnail) }}"
                         class="h-32 object-cover rounded mb-2" alt="Current thumbnail">
                    <p class="text-xs text-gray-400 mb-1">Upload a new image to replace the current one.</p>
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm text-gray-600">
            </div>

            {{-- Existing Gallery Images --}}
            @if($room->images->isNotEmpty())
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Gallery</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($room->images as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             class="h-20 w-24 object-cover rounded" alt="Gallery">
                        {{-- Delete individual image --}}
                        <form method="POST"
                              action="{{ route('admin.rooms.images.destroy', $image) }}"
                              onsubmit="return confirm('Remove this image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                ×
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Add More Gallery Images --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Add More Gallery Images</label>
                <input type="file" name="images[]" accept="image/*" multiple
                       class="w-full text-sm text-gray-600">
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 text-sm font-medium">
                    Update Room
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
