@extends('layouts.admin')

@section('title', 'Review Reservation')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.reservations.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline mb-4 block">
        ← Back to Reservations
    </a>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Reservation Review</h1>
            @php $color = $reservation->statusColor(); @endphp
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                bg-{{ $color }}-100 text-{{ $color }}-700 dark:bg-{{ $color }}-900/40 dark:text-{{ $color }}-400">
                {{ $reservation->statusLabel() }}
            </span>
        </div>

        <div class="space-y-3 text-sm mb-6">
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Tenant</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->user->name }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Email</span>
                <span class="text-gray-700 dark:text-gray-300">{{ $reservation->user->email }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Phone</span>
                <span class="text-gray-700 dark:text-gray-300">{{ $reservation->user->phone ?? '—' }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Room</span>
                <span class="font-semibold text-gray-900 dark:text-white">Room {{ $reservation->room->room_number }} ({{ $reservation->room->room_type }})</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Monthly Rate</span>
                <span class="text-gray-700 dark:text-gray-300">₱{{ number_format($reservation->room->monthly_rate, 2) }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Move-in Date</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->move_in_date->format('F d, Y') }}</span>
            </div>
            @if($reservation->notes)
            <div class="border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400 block mb-1">Notes from Tenant</span>
                <p class="text-gray-700 dark:text-gray-300 italic">{{ $reservation->notes }}</p>
            </div>
            @endif
        </div>

        @if($reservation->isPending())
        <div class="border-t border-gray-200 dark:border-gray-700 pt-5 space-y-4" x-data="{ approveModal: false }">

            {{-- Approve Form --}}
            <form id="approveForm" method="POST" action="{{ route('admin.reservations.updateStatus', $reservation) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="approved">
                <button type="button" @click="approveModal = true"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold text-sm">
                    ✓ Approve Reservation
                </button>
            </form>

            {{-- Approve Confirmation Modal --}}
            <div x-show="approveModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div x-show="approveModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl p-8 max-w-sm w-full mx-4 text-center">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Approve Reservation?</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">The room will be marked as <span class="font-medium text-gray-700 dark:text-gray-300">occupied</span> once approved.</p>
                    <div class="flex gap-3">
                        <button @click="approveModal = false"
                                class="flex-1 px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                            Cancel
                        </button>
                        <button @click="$el.closest('[x-data]').querySelector('#approveForm').submit()"
                                class="flex-1 px-4 py-2 rounded-lg text-sm font-medium bg-green-600 hover:bg-green-700 text-white">
                            Yes, Approve
                        </button>
                    </div>
                </div>
            </div>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                        class="w-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 py-2.5 rounded-lg font-semibold hover:bg-red-200 dark:hover:bg-red-900/50 text-sm">
                    ✗ Reject Reservation
                </button>

                <div x-show="open" class="mt-3">
                    <form method="POST" action="{{ route('admin.reservations.updateStatus', $reservation) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Reason for Rejection *
                        </label>
                        <textarea name="rejection_reason" rows="3"
                                  class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm @error('rejection_reason') border-red-500 @enderror"
                                  placeholder="Explain why the reservation is being rejected...">{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                                class="mt-2 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold">
                            Confirm Rejection
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @endif

        @if($reservation->isRejected() && $reservation->rejection_reason)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Rejection Reason:</p>
            <p class="text-red-600 dark:text-red-400 text-sm">{{ $reservation->rejection_reason }}</p>
        </div>
        @endif

    </div>
</div>

@endsection
