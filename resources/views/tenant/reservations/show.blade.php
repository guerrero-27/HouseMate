@extends('layouts.tenant')

@section('title', 'Reservation Details')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('tenant.reservations.index') }}" class="text-sm text-gray-500 hover:underline mb-4 block">
        ← Back to My Reservations
    </a>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl font-bold text-gray-800">Reservation Details</h1>
            @php $color = $reservation->statusColor(); @endphp
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                bg-{{ $color }}-100 text-{{ $color }}-700">
                {{ $reservation->statusLabel() }}
            </span>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Room</span>
                <span class="font-semibold">Room {{ $reservation->room->room_number }} ({{ $reservation->room->room_type }})</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Monthly Rate</span>
                <span class="font-semibold">₱{{ number_format($reservation->room->monthly_rate, 2) }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Preferred Move-in Date</span>
                <span class="font-semibold">{{ $reservation->move_in_date->format('F d, Y') }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Submitted On</span>
                <span>{{ $reservation->created_at->format('F d, Y h:i A') }}</span>
            </div>
            @if($reservation->notes)
            <div class="border-b pb-2">
                <span class="text-gray-500 block mb-1">Your Notes</span>
                <p class="text-gray-700">{{ $reservation->notes }}</p>
            </div>
            @endif
            @if($reservation->isRejected() && $reservation->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded p-3">
                <span class="text-red-700 font-semibold text-xs block mb-1">Reason for Rejection:</span>
                <p class="text-red-600 text-sm">{{ $reservation->rejection_reason }}</p>
            </div>
            @endif
            @if($reservation->approved_at)
            <div class="flex justify-between">
                <span class="text-gray-500">Approved On</span>
                <span>{{ $reservation->approved_at->format('F d, Y h:i A') }}</span>
            </div>
            @endif
        </div>

        @if($reservation->isPending())
        <form method="POST"
              action="{{ route('tenant.reservations.cancel', $reservation) }}"
              onsubmit="return confirm('Are you sure you want to cancel this reservation?')"
              class="mt-6">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="w-full bg-red-100 text-red-700 py-2 rounded-lg text-sm font-semibold hover:bg-red-200">
                Cancel Reservation
            </button>
        </form>
        @endif
    </div>
</div>

@endsection
