@extends('layouts.tenant')

@section('title', 'My Dashboard')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Hello, {{ auth()->user()->name }}</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Here's a summary of your account.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Rental Status</p>
        <p class="text-lg font-bold text-gray-900 dark:text-white">
            {{ $reservation ? 'Active — Room ' . $reservation->room->room_number : 'No Active Lease' }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Next Due Date</p>
        <p class="text-lg font-bold text-gray-900 dark:text-white">
            {{ $nextDue ? $nextDue->due_date->format('M d, Y') : '—' }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Unpaid Bills</p>
        <p class="text-lg font-bold {{ $unpaidCount > 0 ? 'text-red-500 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
            {{ $unpaidCount }}
        </p>
    </div>

</div>

@endsection
