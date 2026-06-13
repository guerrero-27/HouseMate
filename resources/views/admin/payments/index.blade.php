@extends('layouts.admin')

@section('title', 'Payment Monitoring')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Payment Monitoring</h1>
    <a href="{{ route('admin.payments.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
        + Create Bill
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Filters --}}
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-4 mb-5">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap gap-3 items-end">

        <div>
            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Billing Month</label>
            <input type="month" name="month" value="{{ $month }}"
                   class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-1.5 text-sm">
        </div>

        <div>
            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Status</label>
            <select name="status" class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-1.5 text-sm">
                @foreach(['all' => 'All', 'unpaid' => 'Unpaid', 'pending_verification' => 'For Verification', 'paid' => 'Paid', 'overdue' => 'Overdue'] as $val => $label)
                    <option value="{{ $val }}" {{ $status === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
    </form>
</div>

{{-- Payments Table --}}
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Tenant</th>
                    <th class="px-4 py-3 hidden sm:table-cell">Room</th>
                    <th class="px-4 py-3 hidden md:table-cell">Type</th>
                    <th class="px-4 py-3 hidden md:table-cell">Billing</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3 hidden sm:table-cell">Due Date</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $payment->user->name }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell">Room {{ $payment->reservation->room->room_number }}</td>
                    <td class="px-4 py-3 capitalize text-gray-700 dark:text-gray-300 hidden md:table-cell">{{ $payment->payment_type }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden md:table-cell">{{ $payment->billingMonthLabel() }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">₱{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-4 py-3 hidden sm:table-cell {{ $payment->isOverdue() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        {{ $payment->due_date->format('M d, Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @php $c = $payment->statusColor(); @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                            bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/40 dark:text-{{ $c }}-400">
                            {{ $payment->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.payments.show', $payment) }}"
                           class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">No payment records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
        {{ $payments->links() }}
    </div>
</div>

@endsection
