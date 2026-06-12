@extends('layouts.tenant')

@section('title', 'My Payments')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">My Payments</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Summary Cards --}}
@php
    $unpaid  = auth()->user()->payments()->whereIn('status', ['unpaid', 'overdue'])->count();
    $pending = auth()->user()->payments()->where('status', 'pending_verification')->count();
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white rounded-xl shadow p-4 border-l-4 border-red-500">
        <p class="text-xs text-gray-500 uppercase">Unpaid Bills</p>
        <p class="text-3xl font-bold text-gray-800">{{ $unpaid }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-400">
        <p class="text-xs text-gray-500 uppercase">For Verification</p>
        <p class="text-3xl font-bold text-gray-800">{{ $pending }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase">Paid Bills</p>
        <p class="text-3xl font-bold text-gray-800">
            {{ auth()->user()->payments()->where('status', 'paid')->count() }}
        </p>
    </div>
</div>

{{-- Payments Table --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Billing Period</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Due Date</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold">{{ $payment->billingMonthLabel() }}</td>
                <td class="px-4 py-3 capitalize">{{ $payment->payment_type }}</td>
                <td class="px-4 py-3 font-semibold">₱{{ number_format($payment->amount, 2) }}</td>
                <td class="px-4 py-3 {{ $payment->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                    {{ $payment->due_date->format('M d, Y') }}
                    @if($payment->isOverdue())
                        <span class="text-xs block">OVERDUE</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @php $c = $payment->statusColor(); @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                        {{ $payment->statusLabel() }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('tenant.payments.show', $payment) }}"
                       class="text-blue-600 hover:underline text-xs">
                        {{ $payment->isUnpaid() || $payment->isOverdue() ? 'Pay Now' : 'View' }}
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">No bills yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">
        {{ $payments->links() }}
    </div>
</div>

@endsection
