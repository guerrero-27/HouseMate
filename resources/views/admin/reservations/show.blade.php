@extends('layouts.admin')

@section('title', 'Review Reservation')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.reservations.index') }}" class="text-sm text-gray-500 hover:underline mb-4 block">
        ← Back to Reservations
    </a>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl font-bold text-gray-800">Reservation Review</h1>
            @php $color = $reservation->statusColor(); @endphp
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                bg-{{ $color }}-100 text-{{ $color }}-700">
                {{ $reservation->statusLabel() }}
            </span>
        </div>

        {{-- Reservation Details --}}
        <div class="space-y-3 text-sm mb-6">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Tenant</span>
                <span class="font-semibold">{{ $reservation->user->name }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Email</span>
                <span>{{ $reservation->user->email }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Phone</span>
                <span>{{ $reservation->user->phone ?? '—' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Room</span>
                <span class="font-semibold">Room {{ $reservation->room->room_number }} ({{ $reservation->room->room_type }})</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Monthly Rate</span>
                <span>₱{{ number_format($reservation->room->monthly_rate, 2) }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Move-in Date</span>
                <span class="font-semibold">{{ $reservation->move_in_date->format('F d, Y') }}</span>
            </div>
            @if($reservation->notes)
            <div class="border-b pb-2">
                <span class="text-gray-500 block mb-1">Notes from Tenant</span>
                <p class="text-gray-700 italic">{{ $reservation->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Action Buttons — only show if still pending --}}
        @if($reservation->isPending())
        <div class="border-t pt-5 space-y-4">

            {{-- Approve --}}
            <form method="POST" action="{{ route('admin.reservations.updateStatus', $reservation) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="approved">
                <button type="submit"
                        onclick="return confirm('Approve this reservation? The room will be marked as occupied.')"
                        class="w-full bg-green-600 text-white py-2.5 rounded-lg font-semibold hover:bg-green-700 text-sm">
                    ✓ Approve Reservation
                </button>
            </form>

            {{-- Reject with Reason --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                        class="w-full bg-red-100 text-red-700 py-2.5 rounded-lg font-semibold hover:bg-red-200 text-sm">
                    ✗ Reject Reservation
                </button>

                <div x-show="open" class="mt-3">
                    <form method="POST" action="{{ route('admin.reservations.updateStatus', $reservation) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Reason for Rejection *
                        </label>
                        <textarea name="rejection_reason" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 text-sm @error('rejection_reason') border-red-500 @enderror"
                                  placeholder="Explain why the reservation is being rejected...">{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                                class="mt-2 w-full bg-red-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-700">
                            Confirm Rejection
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @endif

        {{-- Show rejection reason if already rejected --}}
        @if($reservation->isRejected() && $reservation->rejection_reason)
        <div class="border-t pt-4 mt-4">
            <p class="text-sm text-gray-500 mb-1">Rejection Reason:</p>
            <p class="text-red-600 text-sm">{{ $reservation->rejection_reason }}</p>
        </div>
        @endif

    </div>
</div>

@endsection
