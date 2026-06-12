@extends('layouts.admin')

@section('title', 'Payment Monitoring')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Payment Monitoring</h1>
    <a href="{{ route('admin.payments.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm">
        + Create Bill
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Filters --}}
<div class="bg-white rounded-xl shadow p-4 mb-5 flex flex-wrap gap-3 items-end">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap gap-3 items-end">

        <div>
            <label class="block text-xs text-gray-500 mb-1">Billing Month</label>
            <input type="month" name="month" value="{{ $month }}"
                   class="border rounded-lg px-3 py-1.5 text-sm">
        </div>

        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="border rounded-lg px-3 py-1.5 text-sm">
                @foreach(['all' => 'All', 'unpaid' => 'Unpaid', 'pending_verification' => 'For Verification', 'paid' => 'Paid', 'overdue' => 'Overdue'] as $val => $label)
                    <option value="{{ $val }}" {{ $status === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-gray-800 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
    </form>
</div>

{{-- Payments Table --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Tenant</th>
                <th class="px-4 py-3">Room</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Billing</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Due Date</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold">{{ $payment->user->name }}</td>
                <td class="px-4 py-3">Room {{ $payment->reservation->room->room_number }}</td>
                <td class="px-4 py-3 capitalize">{{ $payment->payment_type }}</td>
                <td class="px-4 py-3">{{ $payment->billingMonthLabel() }}</td>
                <td class="px-4 py-3 font-semibold">₱{{ number_format($payment->amount, 2) }}</td>
                <td class="px-4 py-3 {{ $payment->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                    {{ $payment->due_date->format('M d, Y') }}
                </td>
                <td class="px-4 py-3">
                    @php $c = $payment->statusColor(); @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                        {{ $payment->statusLabel() }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.payments.show', $payment) }}"
                       class="text-blue-600 hover:underline text-xs">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-400">No payment records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">
        {{ $payments->links() }}
    </div>
</div>

@endsection
