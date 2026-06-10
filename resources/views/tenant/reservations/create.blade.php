@extends('layouts.tenant')

@section('title', 'Reserve Room ' . $room->room_number)

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('tenant.rooms.show', $room) }}" class="text-sm text-gray-500 hover:underline mb-4 block">
        ← Back to Room Details
    </a>

    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Reserve Room {{ $room->room_number }}</h1>
        <p class="text-sm text-gray-500 mb-6">{{ $room->room_type }} — ₱{{ number_format($room->monthly_rate, 2) }}/month</p>

        <form method="POST" action="{{ route('tenant.reservations.store') }}">
            @csrf

            {{-- Hidden room_id --}}
            <input type="hidden" name="room_id" value="{{ $room->id }}">

            {{-- Move-in Date --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Move-in Date *</label>
                <input type="date" name="move_in_date"
                       value="{{ old('move_in_date') }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm @error('move_in_date') border-red-500 @enderror">
                @error('move_in_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full border rounded-lg px-3 py-2 text-sm"
                          placeholder="Any special requests or notes for the landlord...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Box --}}
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm px-4 py-3 rounded mb-5">
                <strong>Note:</strong> Your reservation will be reviewed by the admin. You will be notified once it is approved or rejected.
            </div>

            <button type="submit"
                    class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 text-sm">
                Submit Reservation
            </button>
        </form>
    </div>
</div>

@endsection
