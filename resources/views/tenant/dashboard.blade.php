@extends('layouts.tenant')

@section('title', 'My Dashboard')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Good day, {{ auth()->user()->name }}</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Here's a summary of your account.</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Rental Status</p>
        <p class="text-lg font-bold {{ $reservation ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
            {{ $reservation ? 'Active' : 'No Lease' }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Room</p>
        <p class="text-lg font-bold text-gray-900 dark:text-white">
            {{ $reservation ? 'Room ' . $reservation->room->room_number : '—' }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-5">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Next Due Date</p>
        <p class="text-lg font-bold {{ $nextDue ? 'text-yellow-500 dark:text-yellow-400' : 'text-gray-900 dark:text-white' }}">
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

{{-- Recent Payments --}}
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Recent Payments</h3>
        <a href="{{ route('tenant.payments.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide border-b border-gray-200 dark:border-gray-700">
                    <th class="px-5 py-3 text-left font-medium">Billing</th>
                    <th class="px-5 py-3 text-left font-medium hidden sm:table-cell">Type</th>
                    <th class="px-5 py-3 text-left font-medium">Amount</th>
                    <th class="px-5 py-3 text-left font-medium hidden sm:table-cell">Due Date</th>
                    <th class="px-5 py-3 text-left font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($recentPayments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-5 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $payment->billingMonthLabel() }}</td>
                    <td class="px-5 py-3 text-gray-600 dark:text-gray-400 capitalize hidden sm:table-cell">{{ $payment->payment_type }}</td>
                    <td class="px-5 py-3 text-gray-800 dark:text-gray-200 font-medium">₱{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-5 py-3 hidden sm:table-cell {{ $payment->isOverdue() ? 'text-red-500 dark:text-red-400 font-medium' : 'text-gray-600 dark:text-gray-400' }}">
                        {{ $payment->due_date->format('M d, Y') }}
                    </td>
                    <td class="px-5 py-3">
                        @php $c = $payment->statusColor(); @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/40 dark:text-{{ $c }}-400">
                            {{ $payment->statusLabel() }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-6 text-center text-sm text-gray-400 dark:text-gray-500">No payment records yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
