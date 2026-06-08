@extends('layouts.admin')

@section('title', 'Add New Room')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add New Room</h1>
        <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-500 hover:underline">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Room Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room Number *</label>
                    <input type="text" name="room_number" value="{{ old('room_number') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm @error('room_number') border-red-500 @enderror"
                           placeholder="e.g. 101">
                    @error('room_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Room Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room Type *</label>
                    <input type="text" name="room_type" value="{{ old('room_type') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm @error('room_type') border-red-500 @enderror"
                           placeholder="e.g. Single, Double">
                    @error('room_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Monthly Rate --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Rate (₱) *</label>
                    <input type="number" name="monthly_rate" value="{{ old('monthly_rate') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm @error('monthly_rate') border-red-500 @enderror"
                           placeholder="3500" step="0.01">
                    @error('monthly_rate')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Capacity --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity *</label>
                    <input type="number" name="capacity" value="{{ old('capacity') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm @error('capacity') border-red-500 @enderror"
                           placeholder="1" min="1">
                    @error('capacity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Floor Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Floor Number</label>
                    <input type="number" name="floor_number" value="{{ old('floor_number') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm"
                           placeholder="1" min="1">
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm @error('status') border-red-500 @enderror">
                        <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied"  {{ old('status') === 'occupied'  ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Description --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded-lg px-3 py-2 text-sm"
                          placeholder="Describe the room...">{{ old('description') }}</textarea>
            </div>

            {{-- Thumbnail Upload --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Photo</label>
                <input type="file" name="thumbnail" accept="image/*"
                       class="w-full text-sm text-gray-600" id="thumbnailInput">
                <img id="thumbnailPreview" src="#" alt="Preview"
                     class="mt-2 h-32 object-cover rounded hidden">
                @error('thumbnail')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gallery Images Upload --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Gallery Images (max 5)</label>
                <input type="file" name="images[]" accept="image/*" multiple
                       class="w-full text-sm text-gray-600">
                @error('images')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 text-sm font-medium">
                    Save Room
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    // Live preview for thumbnail
    document.getElementById('thumbnailInput').addEventListener('change', function (e) {
        const preview = document.getElementById('thumbnailPreview');
        const file = e.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });
</script>
@endpush

@endsection
